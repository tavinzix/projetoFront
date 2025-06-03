@echo off
:: Defina os parâmetros do seu banco de dados
set PGBIN=D:\Program Files\pgadmin4\v7\runtime
set PGUSER=postgres
set PGPASSWORD=123456
set PGDATABASE=projeto_if
set BACKUP_DIR=C:\wamp64\www\projetoFront\outros

:: Substituir / por - na data
set TODAY=%DATE:/=-%
set BACKUP_NAME=%PGDATABASE%_backup_%TODAY%.backup

:: Definir o diretório de backups
if not exist %BACKUP_DIR% (
    mkdir %BACKUP_DIR%
)

:: Realizar o backup com pg_dump
cd /d %PGBIN%

:: Comando para gerar o backup
pg_dump -U %PGUSER% -h localhost -F c -b -v -f "%BACKUP_DIR%\%BACKUP_NAME%" %PGDATABASE%

:: Mensagem de conclusão
echo Backup concluído com sucesso em %BACKUP_DIR%\%BACKUP_NAME%
pause
