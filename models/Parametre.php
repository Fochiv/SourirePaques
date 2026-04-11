<?php
/**
 * Modèle Parametre
 * Gestion des paramètres du site
 */

require_once __DIR__ . '/../config/database.php';

class Parametre {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * Récupérer une valeur par clé
     */
    public function get(string $cle, string $defaut = ''): string {
        $stmt = $this->pdo->prepare("SELECT valeur FROM parametres_site WHERE cle = :cle");
        $stmt->execute([':cle' => $cle]);
        $row = $stmt->fetch();
        return $row ? $row['valeur'] : $defaut;
    }

    /**
     * Mettre à jour une valeur
     */
    public function set(string $cle, string $valeur): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO parametres_site (cle, valeur) VALUES (:cle, :valeur)
             ON CONFLICT(cle) DO UPDATE SET valeur=excluded.valeur, updated_at=datetime('now')"
        );
        $stmt->execute([':cle' => $cle, ':valeur' => $valeur]);
    }

    /**
     * Récupérer tous les paramètres sous forme de tableau associatif
     */
    public function tous(): array {
        $rows = $this->pdo->query("SELECT cle, valeur FROM parametres_site")->fetchAll();
        $result = [];
        foreach ($rows as $r) {
            $result[$r['cle']] = $r['valeur'];
        }
        return $result;
    }

    /**
     * Mettre à jour plusieurs paramètres à la fois
     */
    public function mettreAJourPlusieurs(array $data): void {
        foreach ($data as $cle => $valeur) {
            $this->set($cle, $valeur);
        }
    }
}
