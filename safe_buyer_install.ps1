$ErrorActionPreference = "Stop"

$root = "C:\xampp\htdocs\digitalshop"
$zip = Join-Path $root "buyer-panel-v2.zip"
$stamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backup = Join-Path $root "SAFE_BACKUP_$stamp"
$tmp = Join-Path $env:TEMP "BUYER_PANEL_$stamp"

$targets = @(
    "app\Http\Controllers\AccountController.php",
    "resources\views\account\layout.blade.php",
    "resources\views\account\dashboard.blade.php",
    "resources\views\account\orders.blade.php",
    "resources\views\account\order-show.blade.php",
    "resources\views\account\files.blade.php",
    "resources\views\account\wallet.blade.php",
    "resources\views\account\notifications.blade.php",
    "resources\views\account\profile.blade.php",
    "resources\views\account\security.blade.php",
    "public\css\account.css"
)

$protected = @(
    "routes\web.php",
    "app\Http\Controllers\AuthController.php",
    "app\Http\Controllers\CheckoutController.php",
    "app\Http\Controllers\AdminUserController.php",
    "app\Models\User.php"
)

try {

    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host " SAFE BUYER PANEL CHECK / INSTALL" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan

    if (!(Test-Path $root)) {
        throw "Project folder not found."
    }

    if (!(Test-Path $zip)) {
        throw "buyer-panel-v2.zip not found."
    }

    New-Item -ItemType Directory -Path $backup -Force | Out-Null
    New-Item -ItemType Directory -Path $tmp -Force | Out-Null

    Write-Host ""
    Write-Host "[1] BACKUP" -ForegroundColor Yellow

    foreach ($file in ($targets + $protected)) {

        $source = Join-Path $root $file

        if (Test-Path $source) {

            $destination = Join-Path $backup $file

            New-Item `
                -ItemType Directory `
                -Path (Split-Path $destination) `
                -Force | Out-Null

            Copy-Item `
                -LiteralPath $source `
                -Destination $destination `
                -Force

            Write-Host "BACKUP OK: $file" -ForegroundColor Green
        }
    }

    Write-Host ""
    Write-Host "[2] EXTRACT ZIP" -ForegroundColor Yellow

    Expand-Archive `
        -LiteralPath $zip `
        -DestinationPath $tmp `
        -Force

    Write-Host "ZIP OK" -ForegroundColor Green

    $zipFiles = Get-ChildItem `
        -LiteralPath $tmp `
        -Recurse `
        -File

    Write-Host ""
    Write-Host "[3] CHECK FILES" -ForegroundColor Yellow

    $found = @{}

    foreach ($file in $targets) {

        $leaf = Split-Path $file -Leaf

        $match = $zipFiles |
            Where-Object {
                $_.Name -eq $leaf
            } |
            Select-Object -First 1

        if (!$match) {
            throw "Required file missing from ZIP: $file"
        }

        $found[$file] = $match.FullName

        Write-Host "FOUND: $file" -ForegroundColor Green
    }

    Write-Host ""
    Write-Host "[4] PHP SYNTAX CHECK" -ForegroundColor Yellow

    $controller = $found["app\Http\Controllers\AccountController.php"]

    $result = & php -l $controller 2>&1

    if ($LASTEXITCODE -ne 0) {
        throw "AccountController syntax error.`n$result"
    }

    Write-Host "AccountController syntax OK" -ForegroundColor Green

    Write-Host ""
    Write-Host "[5] INSTALL BUYER PANEL" -ForegroundColor Yellow

    foreach ($file in $targets) {

        $source = $found[$file]
        $destination = Join-Path $root $file

        New-Item `
            -ItemType Directory `
            -Path (Split-Path $destination) `
            -Force | Out-Null

        Copy-Item `
            -LiteralPath $source `
            -Destination $destination `
            -Force

        Write-Host "INSTALLED: $file" -ForegroundColor Green
    }

    Write-Host ""
    Write-Host "[6] LARAVEL CACHE" -ForegroundColor Yellow

    & php artisan optimize:clear

    if ($LASTEXITCODE -ne 0) {
        throw "php artisan optimize:clear failed."
    }

    Write-Host ""
    Write-Host "[7] ROUTE CHECK" -ForegroundColor Yellow

    & php artisan route:list --no-ansi

    if ($LASTEXITCODE -ne 0) {
        throw "php artisan route:list failed."
    }

    Write-Host ""
    Write-Host "[8] FINAL PHP CHECK" -ForegroundColor Yellow

    $installed = Join-Path `
        $root `
        "app\Http\Controllers\AccountController.php"

    $result = & php -l $installed 2>&1

    if ($LASTEXITCODE -ne 0) {
        throw "Installed AccountController syntax error.`n$result"
    }

    Write-Host "FINAL PHP CHECK OK" -ForegroundColor Green

    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host " INSTALLATION SUCCESSFUL" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Backup:" -ForegroundColor Cyan
    Write-Host $backup -ForegroundColor Cyan
    Write-Host ""
    Write-Host "IMPORTANT:" -ForegroundColor Yellow
    Write-Host "web.php was NOT modified."
    Write-Host "AuthController was NOT modified."
    Write-Host "CheckoutController was NOT modified."
    Write-Host "AdminUserController was NOT modified."
    Write-Host "User.php was NOT modified."
    Write-Host ""

}
catch {

    Write-Host ""
    Write-Host "========================================" -ForegroundColor Red
    Write-Host " INSTALL FAILED" -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Red
    Write-Host ""
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    Write-Host "NO AUTOMATIC ROUTE/AUTH CHANGES WERE MADE." -ForegroundColor Yellow
    Write-Host "Backup:" -ForegroundColor Cyan
    Write-Host $backup -ForegroundColor Cyan
    Write-Host ""

}
finally {

    if (Test-Path $tmp) {
        Remove-Item `
            -LiteralPath $tmp `
            -Recurse `
            -Force `
            -ErrorAction SilentlyContinue
    }
}