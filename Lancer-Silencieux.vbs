Set FSO = CreateObject("Scripting.FileSystemObject")
Set WshShell = CreateObject("WScript.Shell")

' Récupère automatiquement le dossier actuel du script VBS
scriptDir = FSO.GetParentFolderName(WScript.ScriptFullName)

' Lance le fichier .bat en mode masque (0)
WshShell.Run "cmd /c """ & scriptDir & "\Lancer-Movizil.bat""", 0, False