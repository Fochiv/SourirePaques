<?php
/**
 * Modèle Admin
 * Authentification et gestion administrateur
 */

require_once __DIR__ . '/../config/database.php';

class Admin {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * Vérifie les identifiants admin
     * Retourne le tableau admin ou false
     */
    public function authentifier(string $username, string $password): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    /**
     * Changer le mot de passe
     */
    public function changerMotDePasse(int $id, string $nouveauMdp): void {
        $hash = password_hash($nouveauMdp, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE admins SET password = :password WHERE id = :id");
        $stmt->execute([':password' => $hash, ':id' => $id]);
    }
}
