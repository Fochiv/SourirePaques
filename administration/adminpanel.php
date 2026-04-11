<?php
/**
 * Point d'entrée sécurisé de l'administration
 * Sourires de Pâques — Accès restreint
 */

session_set_cookie_params([
    'lifetime' => 7200,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

define('ROOT_PATH', __DIR__ . '/..');
define('ADMIN_BASE_URL', 'adminpanel.php');

$dbFile = ROOT_PATH . '/database.sqlite';
if (!file_exists($dbFile)) {
    require_once ROOT_PATH . '/config/init_sqlite.php';
}

$adminBase = 'adminpanel.php';

$page = $_GET['page'] ?? 'login';
if (!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
    $page = 'login';
}

require_once ROOT_PATH . '/controllers/AdminController.php';
$adminCtrl = new AdminController();

switch ($page) {
    case 'login':
        $adminCtrl->login();
        break;
    case 'login_traiter':
        $adminCtrl->traiterLogin();
        break;
    case 'logout':
        $adminCtrl->logout();
        break;
    case 'dashboard':
        $adminCtrl->dashboard();
        break;
    case 'dons':
        $adminCtrl->dons();
        break;
    case 'versets':
        $adminCtrl->versets();
        break;
    case 'parametres':
        $adminCtrl->parametres();
        break;
    default:
        header('Location: adminpanel.php?page=login');
        exit;
}
