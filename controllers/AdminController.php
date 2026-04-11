<?php
/**
 * Contrôleur Admin
 * Dashboard, gestion des dons, versets, paramètres
 */

require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Verset.php';
require_once __DIR__ . '/../models/Parametre.php';

class AdminController {

    private Don $donModel;
    private Admin $adminModel;
    private Verset $versetModel;
    private Parametre $parametreModel;

    private string $loginUrl    = 'adminpanel.php?page=login';
    private string $dashboardUrl = 'adminpanel.php?page=dashboard';

    public function __construct() {
        $this->donModel       = new Don();
        $this->adminModel     = new Admin();
        $this->versetModel    = new Verset();
        $this->parametreModel = new Parametre();
    }

    private function verifierAuth(): void {
        if (empty($_SESSION['admin_id'])) {
            header('Location: ' . $this->loginUrl);
            exit;
        }
    }

    public function login(): void {
        if (!empty($_SESSION['admin_id'])) {
            header('Location: ' . $this->dashboardUrl);
            exit;
        }
        $erreur = $_SESSION['erreur_login'] ?? null;
        unset($_SESSION['erreur_login']);
        $adminBase = $adminBase ?? (defined('ADMIN_BASE_URL') ? ADMIN_BASE_URL : 'adminpanel.php');
        require __DIR__ . '/../views/admin/login.php';
    }

    public function traiterLogin(): void {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $admin = $this->adminModel->authentifier($username, $password);
        if ($admin) {
            session_regenerate_id(true);
            $_SESSION['admin_id']         = $admin['id'];
            $_SESSION['admin_username']   = $admin['username'];
            $_SESSION['admin_last_login'] = date('d/m/Y H:i');
            header('Location: ' . $this->dashboardUrl);
        } else {
            $_SESSION['erreur_login'] = 'Identifiants incorrects.';
            header('Location: ' . $this->loginUrl);
        }
        exit;
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . $this->loginUrl);
        exit;
    }

    public function dashboard(): void {
        $this->verifierAuth();

        $data = [
            'montant_total'        => $this->donModel->montantTotal(),
            'montant_aujourdhui'   => $this->donModel->montantAujourdhui(),
            'montant_semaine'      => $this->donModel->montantSemaine(),
            'montant_mois'         => $this->donModel->montantMois(),
            'nombre_contributeurs' => $this->donModel->nombreContributeurs(),
            'nombre_anonymes'      => $this->donModel->nombreAnonymes(),
            'don_moyen'            => $this->donModel->donMoyen(),
            'plus_gros_don'        => $this->donModel->plusGrosDon(),
            'evolution_jour'       => $this->donModel->evolutionParJour(),
            'evolution_semaine'    => $this->donModel->evolutionParSemaine(),
            'params'               => $this->parametreModel->tous(),
        ];

        $adminBase = defined('ADMIN_BASE_URL') ? ADMIN_BASE_URL : 'adminpanel.php';
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function dons(): void {
        $this->verifierAuth();

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->donModel->exporterCSV();
            exit;
        }

        $recherche   = trim($_GET['recherche'] ?? '');
        $date        = trim($_GET['date'] ?? '');
        $montant_min = trim($_GET['montant_min'] ?? '');

        $dons   = $this->donModel->tousAvecRecherche($recherche, $date, $montant_min);
        $params = $this->parametreModel->tous();
        $adminBase = defined('ADMIN_BASE_URL') ? ADMIN_BASE_URL : 'adminpanel.php';

        require __DIR__ . '/../views/admin/dons.php';
    }

    public function versets(): void {
        $this->verifierAuth();

        $adminBase = defined('ADMIN_BASE_URL') ? ADMIN_BASE_URL : 'adminpanel.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'creer') {
            $this->versetModel->creer($_POST);
            header('Location: ' . $adminBase . '?page=versets&succes=1');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'modifier') {
            $this->versetModel->modifier((int)$_POST['id'], $_POST);
            header('Location: ' . $adminBase . '?page=versets&succes=modifie');
            exit;
        }
        if (isset($_GET['supprimer'])) {
            $this->versetModel->supprimer((int)$_GET['supprimer']);
            header('Location: ' . $adminBase . '?page=versets');
            exit;
        }
        if (isset($_GET['toggle'])) {
            $this->versetModel->basculerActif((int)$_GET['toggle']);
            header('Location: ' . $adminBase . '?page=versets');
            exit;
        }

        $versets = $this->versetModel->tous();
        $edition = isset($_GET['edit']) ? $this->versetModel->trouverParId((int)$_GET['edit']) : null;
        require __DIR__ . '/../views/admin/versets.php';
    }

    public function parametres(): void {
        $this->verifierAuth();

        $adminBase = defined('ADMIN_BASE_URL') ? ADMIN_BASE_URL : 'adminpanel.php';
        $succes = null;
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'parametres_site') {
                $this->parametreModel->mettreAJourPlusieurs([
                    'objectif_financier'           => (float)($_POST['objectif_financier'] ?? 500000),
                    'message_accueil_fr'           => trim($_POST['message_accueil_fr'] ?? ''),
                    'message_accueil_en'           => trim($_POST['message_accueil_en'] ?? ''),
                    'presentation_orphelinat_fr'   => trim($_POST['presentation_orphelinat_fr'] ?? ''),
                    'presentation_orphelinat_en'   => trim($_POST['presentation_orphelinat_en'] ?? ''),
                    'nom_orphelinat'               => trim($_POST['nom_orphelinat'] ?? ''),
                    'devise'                       => trim($_POST['devise'] ?? 'XAF'),
                ]);
                $succes = 'Paramètres mis à jour avec succès.';

            } elseif ($action === 'changer_mdp') {
                $ancien  = $_POST['ancien_mdp'] ?? '';
                $nouveau = $_POST['nouveau_mdp'] ?? '';
                $confirm = $_POST['confirmer_mdp'] ?? '';

                $admin = $this->adminModel->authentifier($_SESSION['admin_username'], $ancien);
                if (!$admin) {
                    $erreur = 'Ancien mot de passe incorrect.';
                } elseif ($nouveau !== $confirm) {
                    $erreur = 'Les nouveaux mots de passe ne correspondent pas.';
                } elseif (strlen($nouveau) < 8) {
                    $erreur = 'Le mot de passe doit comporter au moins 8 caractères.';
                } else {
                    $this->adminModel->changerMotDePasse($_SESSION['admin_id'], $nouveau);
                    $succes = 'Mot de passe modifié avec succès.';
                }
            }
        }

        $params = $this->parametreModel->tous();
        require __DIR__ . '/../views/admin/parametres.php';
    }
}
