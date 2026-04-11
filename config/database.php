<?php
/**
 * Configuration de la base de données
 * Sourires de Pâques
 *
 * Pour hébergement en ligne (AeronFree / MySQL) : DB_TYPE = 'mysql'
 * Pour Replit (développement)                   : DB_TYPE = 'sqlite'
 */

// ============================================================
// CHOISIR LE MODE : 'mysql' en ligne | 'sqlite' sur Replit
// ============================================================
// ⚠️  AVANT D'UPLOADER : changer 'sqlite' en 'mysql' ci-dessous
define('DB_TYPE', 'sql');

// ============================================================
// CONFIGURATION MYSQL — AeronFree / iceiy.com
// ============================================================
define('DB_HOST', 'sql306.iceiy.com');
define('DB_NAME', 'icei_41478337_sourires_de_paques');
define('DB_USER', 'icei_41478337');
define('DB_PASS', '1214161820Ben');

// ============================================================
// CONFIGURATION SQLITE — développement Replit uniquement
// ============================================================
define('DB_SQLITE_PATH', __DIR__ . '/../database.sqlite');

// ============================================================
// OBJECTIF FINANCIER PAR DÉFAUT
// ============================================================
define('OBJECTIF_DEFAULT', 500000);

// ============================================================
// CLASSE DATABASE (Singleton PDO)
// ============================================================
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            if (DB_TYPE === 'mysql') {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]);
            } else {
                $this->pdo = new PDO('sqlite:' . DB_SQLITE_PATH, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                $this->pdo->exec("PRAGMA foreign_keys = ON");
                $this->pdo->exec("PRAGMA journal_mode = WAL");
            }
        } catch (PDOException $e) {
            // En production, ne pas afficher les détails de l'erreur
            die('<h3 style="font-family:sans-serif;color:#c00;padding:2rem;">
                Erreur de connexion à la base de données.<br>
                <small>Vérifiez les paramètres dans config/database.php</small>
            </h3>');
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }
}
