<?php
/**
 * Modèle Don
 * Gère toutes les opérations sur les dons (PDO obligatoire)
 */

require_once __DIR__ . '/../config/database.php';

class Don {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPDO();
    }

    /**
     * Crée un nouveau don en base
     */
    public function creer(array $data): int {
        $sql = "INSERT INTO dons (type, nom, prenom, pays, ville, telephone, email, montant,
                    methode_paiement, operateur, code_pays_tel, reference_transaction, statut)
                VALUES (:type, :nom, :prenom, :pays, :ville, :telephone, :email, :montant,
                    :methode_paiement, :operateur, :code_pays_tel, :reference_transaction, :statut)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':type'                  => $data['type'] ?? 'anonyme',
            ':nom'                   => $data['nom'] ?? null,
            ':prenom'                => $data['prenom'] ?? null,
            ':pays'                  => $data['pays'] ?? null,
            ':ville'                 => $data['ville'] ?? null,
            ':telephone'             => $data['telephone'] ?? null,
            ':email'                 => $data['email'] ?? null,
            ':montant'               => (float)($data['montant'] ?? 0),
            ':methode_paiement'      => $data['methode_paiement'] ?? 'api_mobile_money',
            ':operateur'             => $data['operateur'] ?? null,
            ':code_pays_tel'         => $data['code_pays_tel'] ?? null,
            ':reference_transaction' => $data['reference_transaction'] ?? null,
            ':statut'                => $data['statut'] ?? 'en_attente',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Met à jour le statut et la référence d'un don
     */
    public function mettreAJourStatut(int $id, string $statut, string $reference = null): void {
        $sql = "UPDATE dons SET statut = :statut, reference_transaction = :ref WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':statut' => $statut, ':ref' => $reference, ':id' => $id]);
    }

    /**
     * Retourne un don par son ID
     */
    public function trouverParId(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM dons WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Montant total collecté (dons réussis uniquement)
     */
    public function montantTotal(): float {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(montant), 0) FROM dons WHERE statut = 'succes'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Nombre total de contributeurs
     */
    public function nombreContributeurs(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM dons WHERE statut = 'succes'");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Nombre de dons anonymes
     */
    public function nombreAnonymes(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM dons WHERE type = 'anonyme' AND statut = 'succes'");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Don moyen
     */
    public function donMoyen(): float {
        $stmt = $this->pdo->query("SELECT COALESCE(AVG(montant), 0) FROM dons WHERE statut = 'succes'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Plus gros don
     */
    public function plusGrosDon(): float {
        $stmt = $this->pdo->query("SELECT COALESCE(MAX(montant), 0) FROM dons WHERE statut = 'succes'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Montant collecté aujourd'hui
     */
    public function montantAujourdhui(): float {
        $sql = "SELECT COALESCE(SUM(montant), 0) FROM dons WHERE statut = 'succes' AND DATE(created_at) = DATE('now')";
        return (float)$this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Montant collecté cette semaine
     */
    public function montantSemaine(): float {
        $sql = "SELECT COALESCE(SUM(montant), 0) FROM dons WHERE statut = 'succes'
                AND created_at >= datetime('now', '-7 days')";
        return (float)$this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Montant collecté ce mois
     */
    public function montantMois(): float {
        $sql = "SELECT COALESCE(SUM(montant), 0) FROM dons WHERE statut = 'succes'
                AND strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')";
        return (float)$this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Tous les dons pour l'admin (avec recherche)
     */
    public function tousAvecRecherche(string $recherche = '', string $date = '', string $montant_min = ''): array {
        $conditions = ["1=1"];
        $params = [];

        if ($recherche !== '') {
            $conditions[] = "(nom LIKE :recherche OR prenom LIKE :recherche OR email LIKE :recherche)";
            $params[':recherche'] = '%' . $recherche . '%';
        }
        if ($date !== '') {
            $conditions[] = "DATE(created_at) = :date";
            $params[':date'] = $date;
        }
        if ($montant_min !== '' && is_numeric($montant_min)) {
            $conditions[] = "montant >= :montant_min";
            $params[':montant_min'] = (float)$montant_min;
        }

        $sql = "SELECT * FROM dons WHERE " . implode(' AND ', $conditions) . " ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Évolution des dons par jour (7 derniers jours) pour graphique
     */
    public function evolutionParJour(): array {
        $sql = "SELECT DATE(created_at) as jour, SUM(montant) as total
                FROM dons WHERE statut = 'succes' AND created_at >= datetime('now', '-30 days')
                GROUP BY DATE(created_at) ORDER BY jour ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Évolution des dons par semaine
     */
    public function evolutionParSemaine(): array {
        $sql = "SELECT strftime('%Y-W%W', created_at) as semaine, SUM(montant) as total
                FROM dons WHERE statut = 'succes'
                GROUP BY semaine ORDER BY semaine ASC LIMIT 12";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Export CSV de tous les dons
     */
    public function exporterCSV(): void {
        $dons = $this->tousAvecRecherche();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="dons_sourires_paques.csv"');
        $f = fopen('php://output', 'w');
        fputs($f, "\xEF\xBB\xBF"); // BOM UTF-8
        fputcsv($f, ['ID', 'Date', 'Type', 'Nom', 'Prénom', 'Pays', 'Ville', 'Téléphone', 'Email', 'Montant', 'Méthode', 'Référence', 'Statut'], ';');
        foreach ($dons as $d) {
            fputcsv($f, [
                $d['id'], $d['created_at'], $d['type'], $d['nom'], $d['prenom'],
                $d['pays'], $d['ville'], $d['telephone'], $d['email'], $d['montant'],
                $d['methode_paiement'], $d['reference_transaction'], $d['statut']
            ], ';');
        }
        fclose($f);
    }
}
