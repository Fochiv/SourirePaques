<?php
/**
 * Modèle Verset
 * Gestion des versets bibliques
 */

require_once __DIR__ . '/../config/database.php';

class Verset {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * Retourne un verset aléatoire actif
     */
    public function aleatoire(): array|false {
        $stmt = $this->pdo->query("SELECT * FROM versets WHERE actif = 1 ORDER BY RANDOM() LIMIT 1");
        return $stmt->fetch();
    }

    /**
     * Retourne tous les versets
     */
    public function tous(): array {
        return $this->pdo->query("SELECT * FROM versets ORDER BY id DESC")->fetchAll();
    }

    /**
     * Trouver par ID
     */
    public function trouverParId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM versets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Créer un verset
     */
    public function creer(array $data): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO versets (reference_fr, texte_fr, reference_en, texte_en, actif)
             VALUES (:ref_fr, :texte_fr, :ref_en, :texte_en, :actif)"
        );
        $stmt->execute([
            ':ref_fr'   => $data['reference_fr'],
            ':texte_fr' => $data['texte_fr'],
            ':ref_en'   => $data['reference_en'],
            ':texte_en' => $data['texte_en'],
            ':actif'    => isset($data['actif']) ? 1 : 0,
        ]);
    }

    /**
     * Modifier un verset
     */
    public function modifier(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            "UPDATE versets SET reference_fr=:ref_fr, texte_fr=:texte_fr,
             reference_en=:ref_en, texte_en=:texte_en, actif=:actif WHERE id=:id"
        );
        $stmt->execute([
            ':ref_fr'   => $data['reference_fr'],
            ':texte_fr' => $data['texte_fr'],
            ':ref_en'   => $data['reference_en'],
            ':texte_en' => $data['texte_en'],
            ':actif'    => isset($data['actif']) ? 1 : 0,
            ':id'       => $id,
        ]);
    }

    /**
     * Supprimer un verset
     */
    public function supprimer(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM versets WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Basculer actif/inactif
     */
    public function basculerActif(int $id): void {
        $stmt = $this->pdo->prepare("UPDATE versets SET actif = CASE WHEN actif=1 THEN 0 ELSE 1 END WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }
}
