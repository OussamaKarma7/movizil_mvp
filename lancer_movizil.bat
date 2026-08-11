@echo off
cd /d "%~dp0"
TITLE UNIVERSAL INVEST STRATEGY - DEMARRAGE AUTOMATIQUE
COLOR 0A

echo ==============================================================================
echo    DEMARRAGE DE LA PLATEFORME ET DE L'AUTOMATE SAGE 100
echo ==============================================================================
echo.

:: 1. Verification/Creation du dossier Sage Import
if not exist "C:\Sage_Import" (
    echo [INFO] Creation du dossier C:\Sage_Import...
    mkdir "C:\Sage_Import"
)

:: 2. Lancement du serveur Web avec le PHP PORTABLE (chemin corrige : %~dp0public)
echo [1/3] Lancement du serveur Web PHP...
start /b "" "%~dp0php\php.exe" -S 127.0.0.1:8000 -t "%~dp0public"

:: 3. Lancement de l'automate Python Sage
echo [2/3] Lancement de l'automate Sage...
if exist "%~dp0sage_sync_agent.py" (
    start "SAGE AUTOMATE" python "%~dp0sage_sync_agent.py"
) else (
    echo [INFO] Fichier sage_sync_agent.py non trouve, passage a la suite...
)

:: 4. Ouverture de l'interface en mode FENETRE
echo [3/3] Ouverture de l'application...
timeout /t 3 /nobreak > nul

where msedge >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    start msedge --app=http://127.0.0.1:8000
) else (
    start http://127.0.0.1:8000
)

echo.
echo ==============================================================================
echo    TOUT EST PRET ! 
echo    - Ne fermez pas cette fenetre pendant l'utilisation.
echo ==============================================================================
echo.
pause