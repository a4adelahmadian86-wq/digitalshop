$backup = Join-Path $root ('SAFE_BACKUP_' + $stamp
$tmp = Join-Path $env:TEMP ('BUYER_PANEL_' + $stamp)
$targets = @('app\Http\Controllers\AccountController.php','resources\views\account\layout.blade.php','resources\views\account\dashboard.blade.php','resources\views\account\orders.blade.php','resources\views\account\order-show.blade.php','resources\views\account\files.blade.php','resources\views\account\wallet.blade.php','resources\views\account\notifications.blade.php','resources\views\account\profile.blade.php','resources\views\account\security.blade.php','public\css\account.css')
$protected = @('routes\web.php','app\Http\Controllers\AuthController.php','app\Http\Controllers\CheckoutController.php','app\Http\Controllers\AdminUserController.php','app\Models\User.php')
if (!(Test-Path -LiteralPath $zip)) { throw 'buyer-panel-v2.zip پیدا نشد' }
New-Item -ItemType Directory -Path $backup,$tmp -Force | Out-Null
Write-Host 'BACKUP...' -ForegroundColor Cyan
foreach ($file in ($targets + $protected)) { $src=Join-Path $root $file; if (Test-Path -LiteralPath $src) { $dst=Join-Path $backup $file; New-Item -ItemType Directory -Path (Split-Path $dst) -Force | Out-Null; Copy-Item -LiteralPath $src -Destination $dst -Force } }
Expand-Archive -LiteralPath $zip -DestinationPath $tmp -Force
$zipFiles=Get-ChildItem -LiteralPath $tmp -Recurse -File
foreach ($file in $targets) { $leaf=Split-Path $file -Leaf; $match=$zipFiles | Where-Object { $_.Name -eq $leaf } | Select-Object -First 1; if (-not $match) { throw ('فایل داخل ZIP پیدا نشد: ' + $file) }; Write-Host ('FOUND: ' + $file) -ForegroundColor Green }
$account=($zipFiles | Where-Object { $_.Name -eq 'AccountController.php' } | Select-Object -First 1).FullName
Could not open input file: $account
if ($LASTEXITCODE -ne 0) { throw ('AccountController syntax error: ' + ($r -join ' ')) }
foreach ($file in $targets) { $leaf=Split-Path $file -Leaf; $src=($zipFiles | Where-Object { $_.Name -eq $leaf } | Select-Object -First 1).FullName; $dst=Join-Path $root $file; New-Item -ItemType Directory -Path (Split-Path $dst) -Force | Out-Null; Copy-Item -LiteralPath $src -Destination $dst -Force; Write-Host ('INSTALLED: ' + $file) -ForegroundColor Green }
Write-Host 'CLEARING LARAVEL CACHE...' -ForegroundColor Cyan
& php artisan optimize:clear
if ($LASTEXITCODE -ne 0) { throw 'optimize:clear failed' }
Write-Host 'TESTING ROUTES...' -ForegroundColor Cyan
& php artisan route:list --no-ansi
if ($LASTEXITCODE -ne 0) { throw 'route:list failed' }
$r=& php -l (Join-Path $root 'app\Http\Controllers\AccountController.php') 2>&
if ($LASTEXITCODE -ne 0) { throw ('Installed Controller syntax error: ' + ($r -join ' ')) }
Write-Host '================================' -ForegroundColor Green
Write-Host 'INSTALLATION SUCCESSFUL' -ForegroundColor Green
Write-Host ('BACKUP: ' + $backup) -ForegroundColor Cyan
Write-Host 'web.php was NOT changed.' -ForegroundColor Yellow
Write-Host 'Auth/Checkout/Admin/User were NOT changed.' -ForegroundColor Yellow
Write-Host '================================' -ForegroundColor Green
