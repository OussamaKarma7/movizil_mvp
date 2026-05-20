@echo off
TITLE UNIVERSAL INVEST STRATEGY - DEMARRAGE AUTOMATIQUE
COLOR 0A

echo ==============================================================================
echo    DEMARRAGE DE LA PLATEFORME ET DE L'AUTOMATE SAGE 100
echo ==============================================================================
echo.

:: 1. Vérification des dossiers
if not exist "C:\Sage_Import" (
    echo [INFO] Creation du dossier C:\Sage_Import...
    mkdir "C:\Sage_Import"
)

:: 2. Lancement du serveur Web (Méthode directe pour éviter les erreurs de permission)
echo [1/3] Lancement du serveur Web sur http://127.0.0.1:8000...
start /b php -S 127.0.0.1:8000 -t public > nul 2>&1

:: 3. Lancement de l'automate Python
echo [2/3] Lancement de l'automate Sage (Synchronisation)...
start "SAGE AUTOMATE" python sage_sync_agent.py

:: 4. Ouverture du tableau de bord
echo [3/3] Ouverture de l'interface...
timeout /t 3 /nobreak > nul
start http://127.0.0.1:8000

echo.
echo ==============================================================================
echo    TOUT EST PRET ! 
echo    - Gardez cette fenetre ouverte pour le serveur web.
echo    - Les donnees iront automatiquement dans Sage UIS2026.mae.
echo ==============================================================================
echo.
pause
