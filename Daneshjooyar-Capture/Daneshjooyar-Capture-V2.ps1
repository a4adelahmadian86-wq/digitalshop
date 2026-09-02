# ============================================================

# DANESHJOOYAR CAPTURE V2

# Manual floating capture button

# Firefox 154+ / WebDriver BiDi

# ============================================================

$ErrorActionPreference = "Stop"

$Root       = Join-Path $env:USERPROFILE "Desktop\Daneshjooyar-Capture"
$Pages      = Join-Path $Root "Pages"
$Assets     = Join-Path $Root "Assets"
$Screens    = Join-Path $Root "Screenshots"
$Reports    = Join-Path $Root "Reports"

New-Item -ItemType Directory -Force -Path $Root,$Pages,$Assets,$Screens,$Reports | Out-Null

$Endpoint = "ws://127.0.0.1:9222/session"
$HomeUrl  = "https://www.daneshjooyar.com/"

$LogFile = Join-Path $Root "capture-v2.log"

# ------------------------------------------------------------

# LOG

# ------------------------------------------------------------

function Log {
param([string]$Text)

$line = "[{0}] {1}" -f `
    (Get-Date -Format "yyyy-MM-dd HH:mm:ss"), `
    $Text

Write-Host $line
Add-Content -LiteralPath $LogFile -Value $line -Encoding UTF8

}

# ------------------------------------------------------------

# WEBSOCKET

# ------------------------------------------------------------
Add-Type -AssemblyName System

$script:WS = $null
$script:CommandId = 0

function Receive-Message {

$buffer = New-Object byte[] 65536
$stream = New-Object System.IO.MemoryStream

try {

    do {

        $segment = New-Object `
            System.ArraySegment[byte] `
            -ArgumentList @(,$buffer)

        $result = $script:WS.ReceiveAsync(
            $segment,
            [Threading.CancellationToken]::None
        ).GetAwaiter().GetResult()

        if (
            $result.MessageType -eq
            [System.Net.WebSockets.WebSocketMessageType]::Close
        ) {
            throw "Firefox closed the BiDi connection."
        }

        if ($result.Count -gt 0) {
            $stream.Write(
                $buffer,
                0,
                $result.Count
            )
        }

    }
    while (-not $result.EndOfMessage)

    $text = [Text.Encoding]::UTF8.GetString(
        $stream.ToArray()
    )

    return ($text | ConvertFrom-Json)

}
finally {
    $stream.Dispose()
}

}

function Send-Command {

param(
    [string]$Method,
    [hashtable]$Params = @{}
)

if (
    -not $script:WS -or
    $script:WS.State -ne
    [System.Net.WebSockets.WebSocketState]::Open
) {
    throw "Firefox BiDi connection is not open."
}

$script:CommandId++

$id = $script:CommandId

$message = @{
    id     = $id
    method = $Method
    params = $Params
}

$json = $message | ConvertTo-Json -Depth 40 -Compress
$bytes = [Text.Encoding]::UTF8.GetBytes($json)

$segment = New-Object `
    System.ArraySegment[byte] `
    -ArgumentList @(,$bytes)

$script:WS.SendAsync(
    $segment,
    [System.Net.WebSockets.WebSocketMessageType]::Text,
    $true,
    [Threading.CancellationToken]::None
).GetAwaiter().GetResult()

while ($true) {

    $response = Receive-Message

    if (
        $null -ne $response.id -and
        [int]$response.id -eq $id
    ) {

        if ($response.error) {

            $msg = [string]$response.error

            if ($response.message) {
                $msg += " | " + [string]$response.message
            }

            throw "BiDi [$Method] failed: $msg"
        }

        return $response
    }

    # Other messages are events.
    # They are intentionally ignored.
}

}

# ------------------------------------------------------------

# CONNECT

# ------------------------------------------------------------

function Connect-Firefox {

Log "Connecting to Firefox..."

$script:WS =
    New-Object System.Net.WebSockets.ClientWebSocket

$script:WS.ConnectAsync(
    [Uri]$Endpoint,
    [Threading.CancellationToken]::None
).GetAwaiter().GetResult()

if (
    $script:WS.State -ne
    [System.Net.WebSockets.WebSocketState]::Open
) {
    throw "Could not open Firefox BiDi connection."
}

Log "Firefox BiDi connected."

}

function Wait-ForFirefox {

Log "Waiting for Firefox remote debugging..."

for ($i = 0; $i -lt 60; $i++) {

    try {

        Connect-Firefox
        return

    }
    catch {

        try {
            if ($script:WS) {
                $script:WS.Dispose()
            }
        }
        catch {}

        Start-Sleep -Milliseconds 500
    }
}

throw "Firefox BiDi endpoint was not available on port 9222."

}

# ------------------------------------------------------------

# CONTEXTS

# ------------------------------------------------------------

function Get-Contexts {

$r = Send-Command `
    "browsingContext.getTree" `
    @{
        maxDepth = 10
    }

$out = @()

foreach ($c in @($r.result.contexts)) {

    $out += $c

    foreach ($child in @($c.children)) {
        $out += $child
    }
}

return $out

}

# ------------------------------------------------------------

# JAVASCRIPT

# ------------------------------------------------------------

function JS {

param(
    [string]$Context,
    [string]$Expression
)

$r = Send-Command `
    "script.evaluate" `
    @{
        expression = $Expression
        target = @{
            context = $Context
        }
        awaitPromise = $true
        resultOwnership = "none"
    }

$remote = $r.result.result

if ($remote.type -ne "string") {
    throw "Unexpected JavaScript return type: $($remote.type)"
}

return [string]$remote.value

}
# ------------------------------------------------------------

# SAFE FILE NAME

# ------------------------------------------------------------

function SafeName {

param([string]$Text)

if ([string]::IsNullOrWhiteSpace($Text)) {
    return "unknown"
}

$Text = $Text -replace '[<>:"/\\|?*]', '_'
$Text = $Text -replace '\s+', '_'

if ($Text.Length -gt 100) {
    $Text = $Text.Substring(0,100)
}

return $Text

}

# ------------------------------------------------------------

# INJECT FLOATING BUTTON

# ------------------------------------------------------------

function Install-CaptureButton {

param([string]$Context)

$script = @'

(function(){

if (document.getElementById("__daneshjooyar_capture_button")) {
    return "already-installed";
}



window.__DJ_CAPTURE_REQUEST = false;
window.__DJ_CAPTURE_STATE = "ready";

const button = document.createElement("button");

button.id = "__daneshjooyar_capture_button";

button.type = "button";

button.textContent = "📥 کپی این صفحه";

button.style.cssText = `
    position:fixed !important;
    right:18px !important;
    bottom:18px !important;
    z-index:2147483647 !important;
    border:0 !important;
    border-radius:12px !important;
    padding:9px 13px !important;
    background:#222 !important;
    color:#fff !important;
    font-family:Tahoma,Arial,sans-serif !important;
    font-size:12px !important;
    line-height:1.4 !important;
    cursor:pointer !important;
    box-shadow:0 4px 18px rgba(0,0,0,.30) !important;
    opacity:.92 !important;
    direction:rtl !important;
    transition:all .2s ease !important;
`;

function setState(state,text){

    window.__DJ_CAPTURE_STATE = state;

    button.textContent = text;

    if(state === "working"){
        button.style.background = "#b77900";
        button.style.cursor = "wait";
    }

    else if(state === "success"){
        button.style.background = "#198754";
        button.style.cursor = "default";
    }

    else if(state === "error"){
        button.style.background = "#c62828";
        button.style.cursor = "pointer";
    }

    else {
        button.style.background = "#222";
        button.style.cursor = "pointer";
    }
}

button.addEventListener("click",function(){

    if(window.__DJ_CAPTURE_STATE === "working"){
        return;
    }

    if(window.__DJ_CAPTURE_STATE === "success"){
        setState("ready","📥 کپی مجدد");
    }

    window.__DJ_CAPTURE_REQUEST = String(Date.now());

    setState(
        "working",
        "⏳ در حال کپی..."
    );
});

document.documentElement.appendChild(button);

window.__DJ_CAPTURE_SET_STATE = setState;

return "installed";

})()
'@

try {
    JS `
        -Context $Context `
        -Expression $script |
        Out-Null

    Log "Floating capture button installed."

}
catch {
    Log "Button installation failed: $($_.Exception.Message)"
}

}

# ------------------------------------------------------------

# SET BUTTON STATUS

# ------------------------------------------------------------

function Set-Button {

param(
    [string]$Context,
    [string]$State,
    [string]$Text
)

$safeState = $State.Replace("'","")
$safeText  = $Text.Replace("\","\\").Replace("'","\'")

$expr = @"

(function(){

const b = document.getElementById(
    "__daneshjooyar_capture_button"
);

if(!b){
    return "button-not-found";
}

if(window.__DJ_CAPTURE_SET_STATE){
    window.__DJ_CAPTURE_SET_STATE(
        '$safeState',
        '$safeText'
    );
    return "updated";
}

return "setter-not-found";

})()
"@

try {
    JS `
        -Context $Context `
        -Expression $expr |
        Out-Null
}
catch {}

}

# ------------------------------------------------------------

# GET PAGE DATA

# ------------------------------------------------------------

function Get-PageData {

param([string]$Context)

$expr = @'

(async function(){

function cleanURL(value){

    if(!value){
        return "";
    }

    try{

        const u = new URL(value);

        const blocked = [
            "token",
            "nonce",
            "auth",
            "authorization",
            "password",
            "secret",
            "key",
            "code",
            "session"
        ];

        [...u.searchParams.keys()].forEach(k=>{

            if(
                blocked.includes(
                    String(k).toLowerCase()
                )
            ){
                u.searchParams.set(
                    k,
                    "[REDACTED]"
                );
            }

        });

        return u.toString();

    }catch(e){

        return String(value);
    }
}

function cleanText(value){

    if(!value){
        return "";
    }

    return String(value)
        .replace(/\r/g,"")
        .substring(0,5000);
}

function attrs(el){

    const result = {};

    if(!el || !el.attributes){
        return result;
    }

    for(const a of el.attributes){

        const n = a.name.toLowerCase();

        if(
            n.includes("token") ||
            n.includes("nonce") ||
            n.includes("password") ||
            n.includes("secret") ||
            n.includes("authorization") ||
            n === "value"
        ){
            result[a.name] = "[REDACTED]";
        }else{
            result[a.name] = a.value;
        }
    }

    return result;
}

function element(el){

    return {
        tag: el.tagName || "",
        id: el.id || "",
        name: el.name || "",
        type: el.type || "",
        text: cleanText(el.innerText || ""),
        href: cleanURL(el.href || ""),
        src: cleanURL(el.src || ""),
        classes: typeof el.className === "string"
            ? el.className
            : "",
        attributes: attrs(el)
    };
}

const html =
    document.documentElement
    ? document.documentElement.outerHTML
    : "";

const text =
    document.body
    ? document.body.innerText || ""
    : "";

const links = [
    ...document.querySelectorAll("a")
].map(element);

const buttons = [
    ...document.querySelectorAll("button"),
    ...document.querySelectorAll(
        'input[type="button"],input[type="submit"]'
    )
].map(element);

const forms = [
    ...document.querySelectorAll("form")
].map(form=>{

    const fields = [
        ...form.elements
    ].map(el=>{

        const type =
            String(el.type || "").toLowerCase();

        return {
            tag: el.tagName || "",
            type: type,
            name: el.name || "",
            id: el.id || "",
            placeholder: el.placeholder || "",
            value:
                type === "password" ||
                type === "hidden"
                ? "[REDACTED]"
                : cleanText(el.value || "")
        };
    });

    return {
        action: cleanURL(form.action || ""),
        method: form.method || "get",
        fields: fields
    };
});

const css = [
    ...document.querySelectorAll(
        'link[rel="stylesheet"]'
    )
]
.map(x=>cleanURL(x.href))
.filter(Boolean);

const scripts = [
    ...document.scripts
]
.map(x=>cleanURL(x.src))
.filter(Boolean);

const images = [
    ...document.images
]
.map(x=>cleanURL(
    x.currentSrc ||
    x.src ||
    ""
))
.filter(Boolean);

const fonts = [];

try{

    for(const sheet of document.styleSheets){

        try{

            for(const rule of sheet.cssRules || []){

                const cssText =
                    rule.cssText || "";

                const matches =
                    cssText.match(
                        /url\((['"]?)(.*?)\1\)/g
                    ) || [];

                for(const m of matches){

                    const url =
                        m.replace(
                            /^url\((['"]?)/,
                            ""
                        ).replace(
                            /(['"]?)\)$/,
                            ""
                        );

                    if(
                        /\.(woff2?|ttf|otf)(\?|$)/i
                    .test(url))
                    {
                        try{

                            fonts.push(
                                new URL(
                                    url,
                                    location.href
                                ).href
                            );

                        }catch(e){}
                    }
                }
            }

        }catch(e){}
    }

}catch(e){}

const videos = [
    ...document.querySelectorAll("video")
]
.map(x=>cleanURL(
    x.currentSrc ||
    x.src ||
    ""
))
.filter(Boolean);

const iframes = [
    ...document.querySelectorAll("iframe")
]
.map(x=>cleanURL(x.src || ""))
.filter(Boolean);

return JSON.stringify({

    url: cleanURL(location.href),

    title: document.title || "",

    html: html,

    visibleText: text,

    links: links,

    buttons: buttons,

    forms: forms,

    stylesheets: [...new Set(css)],

    scripts: [...new Set(scripts)],

    images: [...new Set(images)],

    fonts: [...new Set(fonts)],

    videos: [...new Set(videos)],

    iframes: [...new Set(iframes)],

    meta: {

        lang:
            document.documentElement
            ? document.documentElement.lang || ""
            : "",

        direction:
            document.documentElement
            ? getComputedStyle(
                document.documentElement
              ).direction
            : "",

        viewport: {
            width: window.innerWidth,
            height: window.innerHeight,
            devicePixelRatio:
                window.devicePixelRatio
        }
    }

});

})()
'@

$json = JS `
    -Context $Context `
    -Expression $expr

return ($json | ConvertFrom-Json)

}

# ------------------------------------------------------------

# SAVE PAGE

# ------------------------------------------------------------

function Save-Page {

param(
    [string]$Context,
    [string]$Reason = "manual"
)

Log "Starting page capture..."

Set-Button `
    $Context `
    "working" `
    "⏳ دریافت HTML..."

$data = Get-PageData $Context

if(
    [string]::IsNullOrWhiteSpace(
        [string]$data.html
    )
){
    throw "HTML is empty."
}

$safe = SafeName $data.url

$stamp =
    Get-Date -Format "yyyyMMdd_HHmmss_fff"

$dir =
    Join-Path `
        $Pages `
        "${safe}_${stamp}"

New-Item `
    -ItemType Directory `
    -Force `
    -Path $dir |
    Out-Null

# --------------------------------------------------------
# HTML
# --------------------------------------------------------

Set-Button `
    $Context `
    "working" `
    "⏳ ذخیره HTML..."

[IO.File]::WriteAllText(
    (Join-Path $dir "page.html"),
    [string]$data.html,
    [Text.UTF8Encoding]::new($false)
)

# --------------------------------------------------------
# TEXT
# --------------------------------------------------------

[IO.File]::WriteAllText(
    (Join-Path $dir "visible-text.txt"),
    [string]$data.visibleText,
    [Text.UTF8Encoding]::new($false)
)

# --------------------------------------------------------
# STRUCTURE
# --------------------------------------------------------

@(
    "links",
    "buttons",
    "forms",
    "stylesheets",
    "scripts",
    "images",
    "fonts",
    "videos",
    "iframes"
) | ForEach-Object {

    $property = $_

    $value =
        $data.$property

    $value |
        ConvertTo-Json -Depth 40 |
        Out-File `
            -LiteralPath (
                Join-Path `
                    $dir `
                    "$property.json"
            ) `
            -Encoding UTF8
}

# --------------------------------------------------------
# PAGE INFO
# --------------------------------------------------------

@{
    captured_at =
        (Get-Date).ToString(
            "yyyy-MM-dd HH:mm:ss"
        )

    reason = $Reason

    url = $data.url

    title = $data.title

    context = $Context

    html_characters =
        $data.html.Length

    visible_text_characters =
        $data.visibleText.Length

    links =
        @($data.links).Count

    buttons =
        @($data.buttons).Count

    forms =
        @($data.forms).Count

    stylesheets =
        @($data.stylesheets).Count

    scripts =
        @($data.scripts).Count

    images =
        @($data.images).Count

    fonts =
        @($data.fonts).Count
} |
    ConvertTo-Json -Depth 20 |
    Out-File `
        -LiteralPath (
            Join-Path $dir "page-info.json"
        ) `
        -Encoding UTF8

# --------------------------------------------------------
# DOWNLOAD STATIC ASSETS
# --------------------------------------------------------

Set-Button `
    $Context `
    "working" `
    "⏳ کپی وابستگی‌ها..."

$assetUrls = @()

$assetUrls += @($data.stylesheets)
$assetUrls += @($data.scripts)
$assetUrls += @($data.images)
$assetUrls += @($data.fonts)

$assetUrls =
    $assetUrls |
    Where-Object {
        $_ -and
        $_ -match '^https://www\.daneshjooyar\.com/'
    } |
    Select-Object -Unique

$assetDir =
    Join-Path `
        $Assets `
        "${safe}_${stamp}"

New-Item `
    -ItemType Directory `
    -Force `
    -Path $assetDir |
    Out-Null

$assetRecords = @()

$total = @($assetUrls).Count
$number = 0
$downloaded = 0

foreach($url in $assetUrls){

    $number++

    try{

        $uri = [Uri]$url

        $file =
            [IO.Path]::GetFileName(
                $uri.AbsolutePath
            )

        if(
            [string]::IsNullOrWhiteSpace($file)
        ){
            continue
        }

        $file = SafeName $file

        $ext =
            [IO.Path]::GetExtension(
                $uri.AbsolutePath
            ).ToLowerInvariant()

        $allowed = @(
            ".css",
            ".js",
            ".png",
            ".jpg",
            ".jpeg",
            ".gif",
            ".webp",
            ".svg",
            ".ico",
            ".woff",
            ".woff2",
            ".ttf",
            ".otf",
            ".eot"
        )

        if(
            $allowed -notcontains $ext
        ){
            continue
        }

        $target =
            Join-Path `
                $assetDir `
                $file

        if(
            Test-Path -LiteralPath $target
        ){

            $base =
                [IO.Path]::GetFileNameWithoutExtension(
                    $file
                )

            $target =
                Join-Path `
                    $assetDir `
                    "${base}_${number}${ext}"
        }

        Set-Button `
            $Context `
            "working" `
            "⏳ وابستگی $number از $total"

        try{

            Invoke-WebRequest `
                -Uri $url `
                -OutFile $target `
                -UseBasicParsing `
                -TimeoutSec 30 `
                -ErrorAction Stop

            if(
                Test-Path -LiteralPath $target
            ){

                $size =
                    (Get-Item -LiteralPath $target).Length

                if($size -gt 0){
                    $downloaded++
                }
            }

            $assetRecords += @{
                url = $url
                file = $target
                status = "downloaded"
                size =
                    (Get-Item -LiteralPath $target).Length
            }

        }
        catch{

            $assetRecords += @{
                url = $url
                file = ""
                status = "failed"
                error =
                    $_.Exception.Message
            }

            Log "Asset failed: $url"
        }

    }
    catch{

        Log "Invalid asset: $url"
    }
}

$assetRecords |
    ConvertTo-Json -Depth 30 |
    Out-File `
        -LiteralPath (
            Join-Path `
                $dir `
                "downloaded-assets.json"
        ) `
        -Encoding UTF8

# --------------------------------------------------------
# SCREENSHOT
# --------------------------------------------------------

Set-Button `
    $Context `
    "working" `
    "⏳ گرفتن Screenshot..."

try{

    $shot =
        Send-Command `
            "browsingContext.captureScreenshot" `
            @{
                context = $Context
                origin = "document"

                format = @{
                    type = "image/png"
                }
            }

    if($shot.result.data){

        $shotFile =
            Join-Path `
                $Screens `
                "${safe}_${stamp}.png"

        $bytes =
            [Convert]::FromBase64String(
                $shot.result.data
            )

        [IO.File]::WriteAllBytes(
            $shotFile,
            $bytes
        )

    }

}
catch{

    Log "Screenshot failed: $($_.Exception.Message)"
}

# --------------------------------------------------------
# INDEX
# --------------------------------------------------------

$record = @{
    captured_at =
        (Get-Date).ToString(
            "yyyy-MM-dd HH:mm:ss"
        )

    url = $data.url

    title = $data.title

    reason = $Reason

    directory = $dir

    html_size =
        $data.html.Length

    text_size =
        $data.visibleText.Length

    assets_found =
        $total

    assets_downloaded =
        $downloaded
}

$record |
    ConvertTo-Json -Compress |
    Add-Content `
        -LiteralPath (
            Join-Path `
                $Root `
                "capture-index.jsonl"
        ) `
        -Encoding UTF8

Log "------------------------------------------------------------"
Log "CAPTURE COMPLETE"
Log "URL: $($data.url)"
Log "HTML: $($data.html.Length) characters"
Log "Assets found: $total"
Log "Assets downloaded: $downloaded"
Log "Directory: $dir"
Log "------------------------------------------------------------"

Set-Button `
    $Context `
    "success" `
    "✓ کپی کامل شد"

return $data

}

# ------------------------------------------------------------

# INITIALIZE

# ------------------------------------------------------------

try{

Write-Host ""
Write-Host "============================================================"
Write-Host "        DANESHJOOYAR CAPTURE V2"
Write-Host "============================================================"
Write-Host ""

Log "Capture V2 started."

# Security information
@{
    application =
        "Daneshjooyar Capture V2"

    started_at =
        (Get-Date).ToString(
            "yyyy-MM-dd HH:mm:ss"
        )

    security = @{
        cookies_saved = $false
        passwords_saved = $false
        authorization_saved = $false
        session_tokens_saved = $false
        csrf_tokens_saved = $false
        nonce_values_saved = $false
        request_bodies_saved = $false
        response_bodies_saved = $false
    }
} |
    ConvertTo-Json -Depth 20 |
    Out-File `
        -LiteralPath (
            Join-Path `
                $Root `
                "capture-info-v2.json"
        ) `
        -Encoding UTF8

# --------------------------------------------------------
# FIREFOX
# --------------------------------------------------------

$firefox =
    @(
        "$env:ProgramFiles\Mozilla Firefox\firefox.exe",
        "${env:ProgramFiles(x86)}\Mozilla Firefox\firefox.exe",
        "$env:LOCALAPPDATA\Mozilla Firefox\firefox.exe"
    ) |
    Where-Object {
        $_ -and
        (Test-Path -LiteralPath $_)
    } |
    Select-Object -First 1

if(-not $firefox){

    $cmd =
        Get-Command firefox.exe `
            -ErrorAction SilentlyContinue

    if($cmd){
        $firefox = $cmd.Source
    }
}

if(-not $firefox){
    throw "Firefox.exe was not found."
}

$process =
    Get-Process firefox `
        -ErrorAction SilentlyContinue

if(-not $process){

    Log "Starting Firefox..."

    Start-Process `
        -FilePath $firefox `
        -ArgumentList "--remote-debugging-port=9222"

    Start-Sleep -Seconds 3

}
else{

    Log "Firefox is already running."
}

# --------------------------------------------------------
# CONNECT
# --------------------------------------------------------

Wait-ForFirefox

# --------------------------------------------------------
# SESSION
# --------------------------------------------------------

Log "Creating BiDi session..."

Send-Command `
    "session.new" `
    @{
        capabilities = @{
            alwaysMatch = @{}
        }
    } |
    Out-Null

Log "BiDi session ready."

# --------------------------------------------------------
# FIND DANESHJOOYAR TAB
# --------------------------------------------------------

$contexts = Get-Contexts

if($contexts.Count -eq 0){
    throw "No Firefox tab was found."
}

$target = $null

foreach($ctx in $contexts){

    if(
        $ctx.url -and
        $ctx.url -match
        '^https://www\.daneshjooyar\.com'
    ){

        $target = $ctx.context
        break
    }
}

if(-not $target){

    $target = $contexts[0].context

    Log "Opening Daneshjooyar homepage..."

    Send-Command `
        "browsingContext.navigate" `
        @{
            context = $target
            url = $HomeUrl
            wait = "complete"
        } |
        Out-Null

    Start-Sleep -Seconds 5
}

# --------------------------------------------------------
# BUTTON
# --------------------------------------------------------

Install-CaptureButton $target

Write-Host ""
Write-Host "============================================================"
Write-Host " BUTTON READY"
Write-Host "============================================================"
Write-Host ""
Write-Host "On the Daneshjooyar page you should now see:"
Write-Host ""
Write-Host "             [ 📥 کپی این صفحه ]"
Write-Host ""
Write-Host "Click it when you want to capture the current page."
Write-Host ""
Write-Host "DO NOT LEAVE THE PAGE UNTIL IT SAYS:"
Write-Host ""
Write-Host "             ✓ کپی کامل شد"
Write-Host ""
Write-Host "============================================================"
Write-Host ""

# --------------------------------------------------------
# MONITOR
# --------------------------------------------------------

$lastUrl = ""
$lastContext = ""

while($true){

    try{

        $contexts = Get-Contexts

        foreach($ctx in $contexts){

            $id = $ctx.context

            try{

                $infoJson =
                    JS `
                        -Context $id `
                        -Expression @'

JSON.stringify({
url: location.href,
request: window.__DJ_CAPTURE_REQUEST || false,
state: window.__DJ_CAPTURE_STATE || ""
})
'@

                $info =
                    $infoJson |
                    ConvertFrom-Json

                if(
                    -not $info.url -or
                    $info.url -notmatch
                    '^https://www\.daneshjooyar\.com'
                ){
                    continue
                }

                # Install button again after SPA navigation.
                Install-CaptureButton $id

                $request =
                    [string]$info.request

                if(
                    $request -and
                    $request -ne "false" -and
                    $request -ne "0"
                ){

                    Log "Manual capture requested."

                    # Clear request immediately.
                    try{

                        JS `
                            -Context $id `
                            -Expression @'

window.__DJ_CAPTURE_REQUEST = false;
"cleared"
'@ |
Out-Null

                    }
                    catch{}

                    try{

                        Start-Sleep -Milliseconds 500

                        Save-Page `
                            -Context $id `
                            -Reason "manual-button"

                    }
                    catch{

                        Log "CAPTURE ERROR: $($_.Exception.Message)"

                        Set-Button `
                            $id `
                            "error" `
                            "✕ خطا - دوباره تلاش کن"
                    }
                }

            }
            catch{
                Log "Context check failed: $($_.Exception.Message)"
            }
        }

    }
    catch{

        Log "Monitor error: $($_.Exception.Message)"
    }

    Start-Sleep -Milliseconds 1200
}

}
catch{

Write-Host ""
Write-Host "============================================================"
Write-Host "                 FATAL CAPTURE ERROR"
Write-Host "============================================================"
Write-Host ""
Write-Host $_.Exception.Message
Write-Host ""
Write-Host "Log:"
Write-Host $LogFile
Write-Host ""

try{

    @{
        failed_at =
            (Get-Date).ToString(
                "yyyy-MM-dd HH:mm:ss"
            )

        error =
            $_.Exception.Message

        details =
            "$_"

    } |
        ConvertTo-Json -Depth 30 |
        Out-File `
            -LiteralPath (
                Join-Path `
                    $Reports `
                    "fatal-error-v2.json"
            ) `
            -Encoding UTF8

}
catch{}

pause
exit 1

}
