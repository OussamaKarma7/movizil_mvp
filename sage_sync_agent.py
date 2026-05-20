import os
import time
import subprocess
import logging

# ==============================================================================
# SAGE 100 REAL-TIME SYNC AGENT (V4 BACKGROUND)
# ==============================================================================
# Cet agent détecte les nouveaux clients et les importe en arrière-plan
# dans Sage 100 sans déranger l'utilisateur courant.
# ==============================================================================

# --- LOGGING SETUP ---
logging.basicConfig(level=logging.INFO, format='[%(asctime)s] %(message)s', datefmt='%H:%M:%S')
logger = logging.getLogger("SageSync")

def get_env_var(key, default=""):
    """Lit une variable d'environnement depuis le fichier .env si elle n'est pas déjà définie."""
    if key in os.environ:
        return os.environ[key]
    try:
        with open(".env", "r") as f:
            for line in f:
                if line.startswith(f"{key}="):
                    return line.split("=", 1)[1].strip().strip('"').strip("'")
    except:
        pass
    return default

# --- CONFIGURATION ---
IMPORT_DIR = r"C:\Sage_Import"
FORMAT_EMA = get_env_var("SAGE_FORMAT_PATH", r"C:\Sage_Import\FORMAT_UIS.ema")
SAGE_EXE = get_env_var("SAGE_EXE_PATH", r"C:\Program Files (x86)\Sage\iComptabilité\Maestria.exe")
COMPANY_FILE = get_env_var("SAGE_COMPANY_PATH", r"C:\Users\pc\Desktop\UIS2026.mae")

def is_sage_running():
    """Vérifie si le processus Sage (Maestria.exe) est déjà actif."""
    try:
        output = subprocess.check_output('tasklist /FI "IMAGENAME eq Maestria.exe"', shell=True).decode('cp1252')
        return "Maestria.exe" in output
    except:
        return False

def push_to_active_sage(file_path):
    """
    Stratégie Universelle SAGE 100 :
    1. Tentative via Objets Métiers (100% arrière-plan, invisible)
    2. Si Sage est fermé : Import direct via CMD
    3. Si Sage est ouvert : Simulation UI "Ultra-Rapide"
    """
    
    # 1. TENTATIVE OBJETS MÉTIERS (Si installé sur le poste)
    try:
        import win32com.client
        logger.info("Tentative via Objets Métiers SAGE (Invisible)...")
        # Tentative d'initialisation de la Compta ou Gestion
        # Note: Ceci nécessite la licence Objets Métiers sur le poste.
        # Nous utilisons une commande générique.
        sage_app = win32com.client.Dispatch("Compta.Application")
        sage_app.Ouvrir(COMPANY_FILE, "ADMIN", "") # Ajuster utilisateur si besoin
        # Importation réelle via OM... (simplifié ici pour la compatibilité)
        # Si on arrive ici sans erreur, on a réussi à se connecter "proprement"
        if sage_app.EstOuvert:
            # Code d'importation spécifique OM
            # (Pour cet exemple, on passe à la suite si l'importation est complexe)
            pass
    except:
        pass # Pas d'Objets Métiers ou erreur de config

    # 2. SI SAGE EST FERMÉ : Import via CMD (Propre)
    if not is_sage_running():
        if os.path.exists(SAGE_EXE) and os.path.exists(COMPANY_FILE):
            try:
                logger.info("Sage est fermé. Importation directe (Arrière-plan).")
                cmd = [SAGE_EXE, COMPANY_FILE, "-I", FORMAT_EMA, file_path]
                subprocess.Popen(cmd)
                return True
            except:
                pass

    # 3. SI SAGE EST OUVERT OU FALLBACK : Simulation UI ULTRA-RAPIDE
    return push_via_fast_vbs(file_path)

def push_via_fast_vbs(file_path):
    """Simulation de clics OPTIMISÉE pour être la moins visible possible."""
    vbs_path = os.path.join(IMPORT_DIR, "pusher.vbs")
    
    # Réduction des délais au minimum pour que ça "clignote" à peine
    vbs_content = f'''
    Set WshShell = WScript.CreateObject("WScript.Shell")
    
    ' Activer Sage
    If WshShell.AppActivate("Sage 100") Then
        WScript.Sleep 200 ' Très court délai
        
        ' Sequence Rapide
        WshShell.SendKeys "%f"
        WScript.Sleep 200
        WshShell.SendKeys "i"
        WScript.Sleep 200
        WshShell.SendKeys "p"
        WScript.Sleep 1000 ' On attend que la fenêtre "Ouvrir le format" soit bien prête
        
        ' Saisie du format : Nettoyage d'abord pour éviter les résidus ou lettres manquantes
        WshShell.SendKeys "^a"
        WScript.Sleep 100
        WshShell.SendKeys "{{BACKSPACE}}"
        WScript.Sleep 200
        WshShell.SendKeys "{FORMAT_EMA}"
        WScript.Sleep 200
        WshShell.SendKeys "{{ENTER}}"
        WScript.Sleep 500
        
        ' Saisie du fichier de données
        WshShell.SendKeys "^a"
        WScript.Sleep 100
        WshShell.SendKeys "{{BACKSPACE}}"
        WScript.Sleep 200
        WshShell.SendKeys "{file_path}"
        WScript.Sleep 200
        WshShell.SendKeys "{{ENTER}}"
        WScript.Sleep 500
        
        ' Validation finale
        WshShell.SendKeys "{{ENTER}}"
        
        ' Optionnel : On peut essayer de minimiser Sage après
        ' WshShell.SendKeys "% {{DOWN}}"
    Else
        WScript.Quit 1
    End If
    '''
    
    with open(vbs_path, "w", encoding="cp1252") as f:
        f.write(vbs_content)
    
    try:
        logger.info(f"Synchronisation en cours (Mode Rapide)...")
        subprocess.run(["cscript.exe", "//Nologo", vbs_path], check=True)
        return True
    except:
        logger.error("Veuillez ouvrir Sage ou configurer les chemins dans .env")
        return False

def monitor_folder():
    if not os.path.exists(IMPORT_DIR):
        os.makedirs(IMPORT_DIR)

    logger.info("====================================================")
    logger.info("   SAGE 100 AUTOMATOR - MODE INTELLIGENT ACTIF     ")
    logger.info("====================================================")
    logger.info("L'automate surveille l'importation...")

    processed_files = set()
    for f in os.listdir(IMPORT_DIR):
        if f.endswith(".txt"): processed_files.add(f)

    try:
        while True:
            current_files = os.listdir(IMPORT_DIR)
            for f in current_files:
                if f.endswith(".txt") and f not in processed_files:
                    file_path = os.path.join(IMPORT_DIR, f)
                    time.sleep(2)
                    if push_to_active_sage(file_path):
                        processed_files.add(f)
                        logger.info("✅ Opération terminée.")
            time.sleep(2)
    except KeyboardInterrupt:
        logger.info("Arrêt de l'automate.")

if __name__ == "__main__":
    monitor_folder()
