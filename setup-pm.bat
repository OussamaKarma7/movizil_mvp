@echo off
SETLOCAL EnableDelayedExpansion

echo ======================================================
echo   PROJET SAAS ACCOUNTING - CONFIGURATION LOCALE
echo ======================================================

:: 1. Vérifier si PHP est installé
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] PHP n'est pas installe ou n'est pas dans le PATH.
    pause
    exit /b
)

:: 2. Vérifier si Composer est installé
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERREUR] Composer n'est pas installe.
    pause
    exit /b
)

:: 3. Configuration de l'environnement
if not exist .env (
    echo [+] Creation du fichier .env...
    copy .env.example .env
)

:: 4. Installation des dépendances PHP
echo [+] Installation des dependances PHP...
call composer install

:: 5. Initialisation de l'application
echo [+] Generation de la cle d'application...
php artisan key:generate

echo [+] Migration de la base de donnees...
php artisan migrate --seed

:: 6. Installation des dépendances Javascript (si npm est présent)
npm -v >nul 2>&1
if %errorlevel% equ 0 (
    echo [+] Installation des dependances Javascript...
    call npm install
    echo [+] Compilation des fichiers...
    call npm run build
) else (
    echo [NOTE] npm n'est pas installe, skipping dependencies JS...
)

echo ======================================================
echo   CONFIGURATION TERMINEE !
echo ======================================================
echo   Lancez 'php artisan serve' pour demarrer le projet.
echo   Le projet sera accessible sur : http://localhost:8000
echo ======================================================
pause
