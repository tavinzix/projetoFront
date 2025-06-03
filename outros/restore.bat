@echo off
:: Defina os parâmetros do seu banco de dados
set PGBIN=D:\Program Files\pgadmin4\v7\runtime
set PGUSER=postgres
set PGPASSWORD=123456
set PGDATABASE=projeto_if_teste
set BACKUP_FILE=C:\wamp64\www\projetoFront\outros\projeto_if_backup_02-06-2025.backup

:: Ir para o diretório onde está o pg_restore
cd /d "%PGBIN%"

:: Restaurar o backup
pg_restore -U %PGUSER% -h localhost -d %PGDATABASE% -v "%BACKUP_FILE%"

:: Mensagem de conclusão
echo Restauração concluída com sucesso do arquivo %BACKUP_FILE%
pause
