param(
    [string]$Source = "C:\xampp\htdocs\digitalshop",
    [string]$Destination = "D:\DigitalShop-Backups",
    [int]$EveryDays = 90
)

$ErrorActionPreference = 'Stop'
$state = Join-Path $Destination '.digitalshop-backup-state.json'
$logDir = Join-Path $Destination 'logs'
New-Item -ItemType Directory -Force -Path $Destination,$logDir | Out-Null

$last = $null
if (Test-Path $state) {
    try { $last = (Get-Content $state -Raw | ConvertFrom-Json).last_run } catch { $last = $null }
}
if ($last) {
    $lastDate = [datetime]$last
    if (((Get-Date) - $lastDate).TotalDays -lt $EveryDays) {
        Write-Host "Backup is not due yet. Last run: $lastDate"
        exit 0
    }
}

if (-not (Test-Path $Source)) { throw "Source project was not found: $Source" }

$stamp = Get-Date -Format 'yyyy-MM-dd_HH-mm-ss'
$log = Join-Path $logDir "backup_$stamp.log"

# Incremental copy: Robocopy transfers only new/changed files; it does not redownload unchanged files.
# No /MIR is used, so a source deletion never silently destroys an older local backup copy.
$excluded = @(
    'vendor', 'node_modules', '.git', 'storage\framework\cache',
    'storage\framework\sessions', 'storage\framework\views', 'storage\logs'
)
$xd = $excluded | ForEach-Object { '/XD'; Join-Path $Source $_ }

$args = @($Source,$Destination,'/E','/Z','/FFT','/COPY:DAT','/DCOPY:T','/R:2','/W:2','/XJ','/NP','/TEE',('/LOG+:'+$log)) + $xd
& robocopy @args
$code = $LASTEXITCODE

if ($code -ge 8) {
    throw "Robocopy reported an error. Exit code: $code. Review $log before considering the backup successful."
}

@{ last_run = (Get-Date).ToString('o'); robocopy_exit = $code } | ConvertTo-Json | Set-Content -Encoding UTF8 $state
Write-Host "Incremental local backup completed. Exit code: $code"
Write-Host "Log: $log"
