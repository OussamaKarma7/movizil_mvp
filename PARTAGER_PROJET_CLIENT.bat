@echo off
cd /d "%~dp0"
TITLE UNIVERSAL INVEST STRATEGY - PARTAGE CLIENT
COLOR 0B

echo ==============================================================================
echo    DEMARRAGE DU PROJET ET GENERATION DU LIEN PUBLIC
echo ==============================================================================
echo.
echo    IMPORTANT : L'automatisation Sage continue de fonctionner en 
echo                arriere-plan sans aucune modification.
echo ==============================================================================
echo.

:: 1. Lancement du serveur Web et Sage 
echo [1/2] Lancement du serveur local et de l'automate Sage...
start cmd /c "LANCER_PROJET_ET_SAGE.bat"

echo.
echo [2/2] Generation du lien public securise pour le client...
echo Patientez quelques secondes pour voir apparaitre votre lien en ".lhr.life"
echo.

:: 2. Création du tunnel sécurisé avec localhost.run
ssh -o StrictHostKeyChecking=no -R 80:localhost:8000 nokey@localhost.run -T -n

pause