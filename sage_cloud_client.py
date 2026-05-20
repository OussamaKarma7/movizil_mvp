import os
import time
import subprocess
import urllib.request
import urllib.error
import urllib.parse
import tkinter as tk
from tkinter import messagebox
from tkinter import ttk
from datetime import datetime

# ==============================================================================
# SAGE 100 CLOUD SYNC CLIENT
# ==============================================================================

# --- CONFIGURATION ---
IMPORT_DIR = r"C:\Sage_Import"
FORMAT_EMA = r"C:\Sage_Import\FORMAT_UIS.ema"
COMPANY_FILE = r"C:\Users\pc\Desktop\UIS2026.mae"
API_TOKEN = "sage_sync_protected_token_2026"

def is_sage_running():
    try:
        output = subprocess.check_output('tasklist /FI "IMAGENAME eq Maestria.exe"', shell=True).decode('cp1252', errors='ignore')
        return "Maestria.exe" in output
    except:
        return False

def push_via_fast_vbs(file_path):
    vbs_path = os.path.join(IMPORT_DIR, "pusher.vbs")
    
    vbs_content = f'''
    Set WshShell = WScript.CreateObject("WScript.Shell")
    If WshShell.AppActivate("Sage 100") Then
        WScript.Sleep 200
        WshShell.SendKeys "%f"
        WScript.Sleep 200
        WshShell.SendKeys "i"
        WScript.Sleep 200
        WshShell.SendKeys "p"
        WScript.Sleep 1000
        WshShell.SendKeys "^a"
        WScript.Sleep 100
        WshShell.SendKeys "{{BACKSPACE}}"
        WScript.Sleep 200
        WshShell.SendKeys "{FORMAT_EMA}"
        WScript.Sleep 200
        WshShell.SendKeys "{{ENTER}}"
        WScript.Sleep 500
        WshShell.SendKeys "^a"
        WScript.Sleep 100
        WshShell.SendKeys "{{BACKSPACE}}"
        WScript.Sleep 200
        WshShell.SendKeys "{file_path}"
        WScript.Sleep 200
        WshShell.SendKeys "{{ENTER}}"
        WScript.Sleep 500
        WshShell.SendKeys "{{ENTER}}"
    Else
        WScript.Quit 1
    End If
    '''
    
    with open(vbs_path, "w", encoding="cp1252") as f:
        f.write(vbs_content)
    
    try:
        subprocess.run(["cscript.exe", "//Nologo", vbs_path], check=True, creationflags=subprocess.CREATE_NO_WINDOW)
        return True
    except subprocess.CalledProcessError:
        return False

def push_to_active_sage(file_path):
    # Tentative via OM
    try:
        import win32com.client
        sage_app = win32com.client.Dispatch("Compta.Application")
        sage_app.Ouvrir(COMPANY_FILE, "ADMIN", "")
        if sage_app.EstOuvert:
            pass
    except:
        pass
    
    # Simulation UI (Fallback principal)
    return push_via_fast_vbs(file_path)

def sync_data(url):
    if not os.path.exists(IMPORT_DIR):
        os.makedirs(IMPORT_DIR)
        
    url = url.rstrip('/')
    api_url = f"{url}/api/sage-sync?token={API_TOKEN}"
    
    try:
        req = urllib.request.Request(api_url)
        with urllib.request.urlopen(req) as response:
            if response.status == 200:
                data = response.read()
                if not data or len(data.strip()) == 0:
                    return "Aucune nouvelle donnée à synchroniser."
                    
                timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
                file_path = os.path.join(IMPORT_DIR, f"CLOUD_SYNC_{timestamp}.txt")
                
                with open(file_path, "wb") as f:
                    f.write(data)
                
                if push_to_active_sage(file_path):
                    return f"Synchronisation réussie ! ({len(data)} octets importés dans Sage)"
                else:
                    return "Fichier téléchargé, mais Sage n'est pas ouvert ou est introuvable."
            else:
                return f"Erreur API: Code {response.status}"
    except urllib.error.URLError as e:
        return f"Impossible de se connecter au site: {e.reason}"
    except Exception as e:
        return f"Erreur inattendue: {str(e)}"

class SageSyncApp:
    def __init__(self, root):
        self.root = root
        self.root.title("SaaS Accounting - Sage Cloud Sync")
        self.root.geometry("450x250")
        self.root.resizable(False, False)
        
        # Style
        style = ttk.Style()
        style.theme_use('clam')
        
        # UI
        ttk.Label(root, text="Synchronisation SaaS -> Sage 100", font=("Arial", 14, "bold")).pack(pady=15)
        
        ttk.Label(root, text="URL de votre plateforme (ex: https://monsaas.com) :").pack(pady=5)
        self.url_var = tk.StringVar(value="http://127.0.0.1:8000")
        self.url_entry = ttk.Entry(root, textvariable=self.url_var, width=50)
        self.url_entry.pack(pady=5)
        
        self.sync_btn = ttk.Button(root, text="Synchroniser Maintenant", command=self.do_sync)
        self.sync_btn.pack(pady=20)
        
        self.status_var = tk.StringVar(value="Prêt.")
        self.status_label = ttk.Label(root, textvariable=self.status_var, foreground="gray")
        self.status_label.pack(pady=5)

    def do_sync(self):
        url = self.url_var.get().strip()
        if not url:
            messagebox.showwarning("Attention", "Veuillez entrer l'URL du site.")
            return
            
        self.status_var.set("Connexion en cours...")
        self.sync_btn.config(state="disabled")
        self.root.update()
        
        # Artificial delay for UX
        time.sleep(0.5)
        
        result = sync_data(url)
        
        if "réussie" in result:
            self.status_label.config(foreground="green")
            messagebox.showinfo("Succès", result)
        else:
            self.status_label.config(foreground="red")
            messagebox.showerror("Erreur ou Info", result)
            
        self.status_var.set(result)
        self.sync_btn.config(state="normal")

if __name__ == "__main__":
    root = tk.Tk()
    app = SageSyncApp(root)
    root.mainloop()
