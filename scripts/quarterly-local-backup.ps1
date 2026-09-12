param(
  [string]$Source = "$env:USERPROFILE\Desktop\digitalshop-ai-dev",
  [string]$Destination = "$env:USERPROFILE\Desktop\digitalshop-backups\quarterly",
  [int]$MinDays = 90
)

$ErrorActionPreference = 'Stop'
$stamp = Get-Date -Format 'yyyyMMdd_HHmmss'
$logDir = Join-Path $Destination 'logs'
New-Item -ItemType Directory -Force -Path $Destination | Out-Null
New-Item -ItemType Directory -Force -Path $logDir | Out-Null
$log = Join-Path $logDir "backup_$stamp.log"

if (-not (Test-Path -LiteralPath $Source)) {
  throw "Source not found: $Source"
}

# Copy only new/changed files; do not mirror-delete destination
$args = @($Source, $Destination, '/E', '/XO', '/R:2', '/W:2', '/NFL', '/NDL', '/NP', '/XD', 'vendor', 'node_modules', '.git', 'storage\logs')
$proc = Start-Process -FilePath robocopy.exe -ArgumentList $args -Wait -PassThru -NoNewWindow
$code = $proc.ExitCode
"[$stamp] robocopy exit=$code source=$Source dest=$Destination" | Out-File -FilePath $log -Append -Encoding utf8

if ($code -ge 8) {
  throw "robocopy failed with exit code $code. See $log"
}

Write-Host "Backup completed. Log: $log"
