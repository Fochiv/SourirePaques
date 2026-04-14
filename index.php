<?php
/**
 * Point d'entrée principal - Routeur
 * Sourires de Pâques
 */

// Initialisation de la session sécurisée
session_set_cookie_params([
    'lifetime' => 7200,
    'path'     => '/',
    'secure'   => false, // true en production HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// Initialisation de la base de données SQLite si nécessaire
$dbFile = __DIR__ . '/database.sqlite';
if (!file_exists($dbFile)) {
    require_once __DIR__ . '/config/init_sqlite.php';
}

// Routage
$page = $_GET['page'] ?? 'accueil';

// Sécurité : valider que la page est une chaîne alphanumérique+underscore
if (!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
    $page = 'accueil';
}

switch ($page) {
    case 'accueil':
    case 'home':
        require_once __DIR__ . '/controllers/HomeController.php';
        (new HomeController())->index();
        break;

    case 'don':
        require_once __DIR__ . '/controllers/DonController.php';
        (new DonController())->formulaire();
        break;

    case 'traiter_don':
        require_once __DIR__ . '/controllers/DonController.php';
        (new DonController())->traiter();
        break;

    case 'merci':
        require_once __DIR__ . '/controllers/DonController.php';
        (new DonController())->merci();
        break;

    case 'wave':
        require_once __DIR__ . '/controllers/DonController.php';
        (new DonController())->wave();
        break;

    case 'webhook':
        require_once __DIR__ . '/controllers/DonController.php';
        (new DonController())->webhook();
        break;

    case 'check_statut':
        require_once __DIR__ . '/controllers/DonController.php';
        (new DonController())->checkStatut();
        break;

    case 'admin_login':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->login();
        break;

    case 'admin_login_traiter':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->traiterLogin();
        break;

    case 'admin_logout':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->logout();
        break;

    case 'admin':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->dashboard();
        break;

    case 'admin_dons':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->dons();
        break;

    case 'admin_versets':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->versets();
        break;

    case 'admin_parametres':
        require_once __DIR__ . '/controllers/AdminController.php';
        (new AdminController())->parametres();
        break;

    default:
        http_response_code(404);
        echo '<h1>Page introuvable</h1><a href="index.php">Retour à l\'accueil</a>';
}
