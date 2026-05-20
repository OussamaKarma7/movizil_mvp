# ==============================================================================
# SAGE 100 AUTOMATOR - UNIVERSAL INVEST STRATEGY
# ==============================================================================
# Ce script surveille le dossier d'importation et injecte automatiquement
# les données dans votre fichier Sage UIS2026.mae.
# ==============================================================================

# --- CONFIGURATION ---
$SagePath = "C:\Program Files (x86)\Sage\iComptabilité\Maestria.exe"
$MaeFile = "C:\Users\pc\Desktop\UIS2026.mae"
$ImportFolder = "C:\Sage_Import"
$FormatFile = "C:\Sage_Import\FORMAT_UIS.ema" # Le modèle d'importation Sage

# --- SURVEILLANCE ---
Write-Host "--- Sage Automator Actif ---" -ForegroundColor Cyan
Write-Host "Surveillance de : $ImportFolder"
Write-Host "Cible Sage : $MaeFile"

if (!(Test-Path $ImportFolder)) { New-Item -ItemType Directory -Path $ImportFolder }

$Watcher = New-Object System.IO.FileSystemWatcher
$Watcher.Path = $ImportFolder
$Watcher.Filter = "*.txt"
$Watcher.EnableRaisingEvents = $true

$Action = {
    $Path = $Event.SourceEventArgs.FullPath
    $Name = $Event.SourceEventArgs.Name
    Write-Host "[$(Get-Date)] Nouveau fichier détecté : $Name" -ForegroundColor Yellow
    
    # Commande d'importation silencieuse Sage
    # Note : Nécessite que le format .ema soit déjà configuré dans Sage
    $Args = "`"$MaeFile`" -I `"$FormatFile`" `"$Path`""
    
    try {
        Start-Process -FilePath $SagePath -ArgumentList $Args -Wait
        Write-Host "[$(Get-Date)] Importation réussie dans Sage !" -ForegroundColor Green
        # Optionnel : Déplacer le fichier traité vers un dossier archive
    } catch {
        Write-Host "[$(Get-Date)] Erreur lors de l'importation : $($_.Exception.Message)" -ForegroundColor Red
    }
}

Register-ObjectEvent $Watcher "Created" -Action $Action

# Boucle infinie pour garder le script actif
while ($true) { Start-Sleep -Seconds 5 }
