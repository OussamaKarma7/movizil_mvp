@echo off
cd /d "%~dp0"
title Lancement de SaaS Accounting
color 0A

echo =======================================================
echo     DEMARRAGE DE LA PLATEFORME UNIVERSAL INVEST STRATEGY
echo =======================================================
echo.
echo Ce script va lancer le serveur web local de l'application.
echo L'application sera accessible depuis votre navigateur web.
echo.
echo Veuillez patienter...
echo.

:: Vérifier si PHP est disponible
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERREUR] PHP n'est pas installe ou n'est pas dans le PATH.
    echo Veuillez installer PHP via XAMPP ou Laragon avant de lancer l'application.
    pause
    exit /b
)

:: --- INSTALLATION AUTOMATIQUE DES CONFIGURATIONS ET DEPENDANCES ---

:: 1. Vérifier si le fichier .env existe, sinon le créer et générer la clé
if not exist .env (
    echo [+] Le fichier de configuration '.env' est introuvable.
    echo [+] Creation du fichier .env a partir de .env.example...
    copy .env.example .env >nul
    
    echo [+] Generation de la cle de securite Laravel...
    php artisan key:generate
)

:: 2. Vérifier si le dossier vendor existe, sinon installer les dépendances avec Composer
if not exist vendor (
    echo.
    echo =======================================================
    echo   INSTALLATION DES DEPENDANCES PHP - COMPOSER...
    echo   Cette etape est effectuee une seule fois au premier
    echo   lancement. Veuillez patienter 1 a 2 minutes...
    echo =======================================================
    echo.
    
    where composer >nul 2>nul
    if %errorlevel% neq 0 (
        echo [ERREUR] Composer n'est pas installe ou n'est pas dans le PATH.
        echo Veuillez installer Composer via https://getcomposer.org/ ou activer Laragon.
        pause
        exit /b
    )
    
    echo [+] Telechargement et installation des dependances en cours...
    call composer install --no-interaction --prefer-dist
    
    if not exist vendor (
        echo [ERREUR] L'installation des dependances via Composer a echoue.
        pause
        exit /b
    )
    
    echo.
    echo [+] Initialisation et migration de la base de donnees...
    echo     Note : Assurez-vous que Laragon/XAMPP is demarre avec MySQL actif !
    php artisan migrate --seed --force
    echo.
)

:: 3. Verifier et configurer la base de donnees
echo [INFO] Verification de la base de donnees...
php database/create_db.php >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo [ERREUR] Impossible de se connecter a la base de donnees MySQL.
    echo Veuillez demarrer Laragon ou XAMPP - avec MySQL actif - puis relancer.
    echo.
    pause
    exit /b
)
php artisan migrate --seed --force >nul 2>&1

:: 1. Vérification des dossiers pour Sage
if not exist "C:\Sage_Import" (
    echo [INFO] Creation du dossier C:\Sage_Import...
    mkdir "C:\Sage_Import"
)

:: 2. Lancement de l'automate Python Sage (Synchronisation)
echo [INFO] Lancement de l'automate Sage (Synchronisation)...
start "SAGE AUTOMATE" python sage_sync_agent.py

:: 3. Liberer le port 8000 s'il est deja occupe
netstat -ano | findstr :8000 | findstr LISTENING >nul
if %errorlevel% equ 0 (
    echo [INFO] Le port 8000 est occupe. Liberation du port en cours...
    for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8000 ^| findstr LISTENING') do (
        taskkill /F /PID %%a >nul 2>&1
    )
    timeout /t 1 /nobreak >nul
)

:: 4. Lancer le serveur Laravel de façon asynchrone (en arrière-plan)
start /b php artisan serve --host=127.0.0.1 --port=8000 > php_server.log 2>&1

:: Attendre 3 secondes le temps que le serveur soit prêt
timeout /t 3 /nobreak >nul

:: Vérifier si le serveur écoute bien sur le port 8000
netstat -ano | findstr :8000 | findstr LISTENING >nul
if %errorlevel% neq 0 (
    echo [ERREUR] Le serveur Laravel n'a pas pu demarrer sur le port 8000.
    echo Les causes possibles :
    echo   - Le port 8000 est deja utilise par une autre application.
    echo   - Une erreur PHP s'est produite - ex: extension manquante ou syntaxe.
    echo.
    echo Detail de l'erreur dans 'php_server.log' :
    echo ----------------------------------------------------------------------
    if exist php_server.log type php_server.log
    echo ----------------------------------------------------------------------
    echo.
    pause
    exit /b
)

echo [SUCCES] Le serveur local est en cours d'execution !
echo.
echo L'application va s'ouvrir automatiquement...
echo.

:: Ouvrir l'URL dans le navigateur par défaut
start http://127.0.0.1:8000

:: Lancer Sage 100 si le fichier existe
set SAGE_EXE="C:\Program Files (x86)\Sage\iComptabilite\Maestria.exe"
if exist %SAGE_EXE% (
    echo.
    echo [INFO] Ouverture de Sage 100 en cours...
    start "" %SAGE_EXE% "C:\Users\pc\Desktop\UIS2026.mae"
) else (
    echo.
    echo [ATTENTION] Logiciel Sage 100 introuvable au chemin par defaut.
)

echo.
echo =======================================================
echo   ATTENTION: NE FERMEZ PAS CETTE FENETRE NOIRE !
echo   (Si vous la fermez, l'application s'arretera)
echo =======================================================
echo.
echo Appuyez sur n'importe quelle touche pour arreter le serveur.
pause >nul

:: Tuer le processus du serveur et de l'automate si on quitte
echo Arret du serveur et de l'automate en cours...
taskkill /F /IM php.exe >nul 2>nul
taskkill /F /FI "WINDOWTITLE eq SAGE AUTOMATE*" >nul 2>nul
echo Serveur et automate arretes avec succes. A bientot !
timeout /t 2 >nul