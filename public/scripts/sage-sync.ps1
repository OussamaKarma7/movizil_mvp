# Script de Synchronisation Automatique Sage 100
# --------------------------------------------------
# Ce script télécharge les écritures comptables depuis le SaaS 
# et les dépose dans le dossier d'importation de Sage.

# --- CONFIGURATION ---
$SaaSUrl = "http://127.0.0.1:8000/export/api/sage-sync"
$Token = "sage_sync_protected_token_2026"
$ImportFolder = "C:\Sage_Import"
$FileName = "IMPORT_SAGE_$(Get-Date -Format 'yyyyMMdd_HHmm').txt"
$LogFile = "$ImportFolder\sync_log.txt"
# ---------------------

# Créer le dossier s'il n'existe pas
if (!(Test-Path -Path $ImportFolder)) {
    New-Item -ItemType Directory -Path $ImportFolder
}

Write-Host "Démarrage de la synchronisation Sage..." -ForegroundColor Cyan

try {
    $FullUrl = "$SaaSUrl?token=$Token"
    $DestPath = Join-Path $ImportFolder $FileName
    
    # Téléchargement
    Invoke-WebRequest -Uri $FullUrl -OutFile $DestPath -ErrorAction Stop
    
    $LogMsg = "$(Get-Date -Format 'dd/MM/yyyy HH:mm:ss') : Succès - Fichier $FileName généré."
    Write-Host $LogMsg -ForegroundColor Green
    Add-Content -Path $LogFile -Value $LogMsg
}
catch {
    $LogMsg = "$(Get-Date -Format 'dd/MM/yyyy HH:mm:ss') : ERREUR - $($_.Exception.Message)"
    Write-Host $LogMsg -ForegroundColor Red
    Add-Content -Path $LogFile -Value $LogMsg
}

# Note : Pour automatiser ce script, utilisez le Planificateur de Tâches Windows
# pour l'exécuter toutes les heures.
