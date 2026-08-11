<?php
/**
 * Script utilitaire pour s'assurer que la base de données existe
 * avant de lancer les migrations Laravel.
 */

$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';
$database = 'saas_accounting';

// Essayer de lire la configuration depuis le fichier .env s'il existe
if (file_exists(__DIR__ . '/../.env')) {
    $env = file_get_contents(__DIR__ . '/../.env');
    if (preg_match('/DB_HOST=(.*)/', $env, $matches)) $host = trim($matches[1]);
    if (preg_match('/DB_PORT=(.*)/', $env, $matches)) $port = trim($matches[1]);
    if (preg_match('/DB_USERNAME=(.*)/', $env, $matches)) $username = trim($matches[1]);
    if (preg_match('/DB_PASSWORD=(.*)/', $env, $matches)) {
        // Enlever les guillemets si présents
        $password = trim(str_replace(['"', "'"], '', $matches[1]));
    }
    if (preg_match('/DB_DATABASE=(.*)/', $env, $matches)) $database = trim($matches[1]);
}

try {
    // Connexion au serveur MySQL (sans spécifier de base de données)
    $dsn = "mysql:host={$host};port={$port}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    
    // Créer la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Base de donnees '{$database}' prete ou creee avec succes.\n";
} catch (Exception $e) {
    echo "Erreur lors de la creation de la base de donnees : " . $e->getMessage() . "\n";
    exit(1);
}
