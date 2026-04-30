@echo off
SETLOCAL EnableDelayedExpansion

echo ======================================================
echo   PROJET SAAS ACCOUNTING - CONFIGURATION AUTOMATIQUE
echo ======================================================

:: 1. Vérifier si Docker est installé et démarré
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] Docker n'est pas installe. Veuillez installer Docker Desktop avant de continuer.
    pause
    exit /b
)

docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] Docker n'est pas demarre. Veuillez lancer Docker Desktop.
    pause
    exit /b
)

:: 2. Configuration de l'environnement
if not exist .env (
    echo [+] Creation du fichier .env...
    copy .env.example .env
)

:: Variables par defaut pour Windows (evite les erreurs de Sail)
set WWWGROUP=1000
set WWWUSER=1000

:: 3. Lancement des conteneurs (via Docker Compose natif pour eviter les erreurs WSL)
echo [+] Lancement des serveurs (Docker)...
echo     (Note : Le premier lancement telecharge environ 500Mo, merci de patienter...)
echo.
docker compose up -d

if %errorlevel% neq 0 (
    echo.
    echo [ERREUR] Impossible de lancer les conteneurs.
    echo Verifiez que l'icône de Docker est VERTE en bas a droite de votre ecran.
    pause
    exit /b
)

:: 4. Initialisation de la base de donnees et dependances
echo [+] Initialisation de la base de donnees...
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate --seed

echo [+] Installation des dependances Javascript...
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build

echo ======================================================
echo   CONFIGURATION TERMINEE !
echo ======================================================
echo   Le projet est accessible sur : http://localhost
echo   Utilisez la commande 'docker compose down' pour arreter.
echo ======================================================
pause
