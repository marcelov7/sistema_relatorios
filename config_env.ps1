# Script para configurar .env para MySQL
$envContent = Get-Content .env

# Configurar para MySQL
$envContent = $envContent -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql'
$envContent = $envContent -replace '# DB_HOST=127.0.0.1', 'DB_HOST=127.0.0.1'
$envContent = $envContent -replace '# DB_PORT=3306', 'DB_PORT=3306'
$envContent = $envContent -replace '# DB_DATABASE=laravel', 'DB_DATABASE=sistema_relatorios'
$envContent = $envContent -replace '# DB_USERNAME=root', 'DB_USERNAME=root'
$envContent = $envContent -replace '# DB_PASSWORD=', 'DB_PASSWORD='

# Salvar o arquivo
$envContent | Set-Content .env

Write-Host "Configuracao do MySQL aplicada com sucesso!"
Write-Host "DB_CONNECTION=mysql"
Write-Host "DB_HOST=127.0.0.1"
Write-Host "DB_PORT=3306"
Write-Host "DB_DATABASE=sistema_relatorios"
Write-Host "DB_USERNAME=root"
Write-Host "DB_PASSWORD=(sem senha)" 