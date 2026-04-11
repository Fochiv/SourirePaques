<?php
$adminBase = $adminBase ?? 'adminpanel.php';
$current   = $_GET['page'] ?? 'dashboard';
$adminUser = htmlspecialchars($_SESSION['admin_username'] ?? 'Admin');
$initiale  = strtoupper(substr($adminUser, 0, 1));
$lastLogin = $_SESSION['admin_last_login'] ?? date('d/m/Y H:i');
?>
<!-- ===== SIDEBAR ADMIN ===== -->
<aside class="admin-sidebar d-flex flex-column">

    <!-- Logo / Brand -->
    <div class="sidebar-brand px-4 py-3 d-flex align-items-center gap-3">
        <div class="sidebar-brand-icon">
            <span class="brand-cross-admin">✝</span>
        </div>
        <div>
            <div class="fw-bold text-white" style="font-size:0.95rem; line-height:1.2;">Sourires de Pâques</div>
            <div class="sidebar-role-badge">Administration</div>
        </div>
    </div>

    <!-- Info Admin -->
    <div class="sidebar-user-box mx-3 my-3 p-3 rounded-3">
        <div class="fw-bold text-white small mb-1">Bonjour, <?= $adminUser ?></div>
        <div class="text-muted" style="font-size:0.72rem;">Dernière connexion: <?= $lastLogin ?></div>
    </div>

    <!-- Menu principal -->
    <div class="px-3 mb-2">
        <div class="sidebar-section-label">MENU PRINCIPAL</div>
    </div>

    <nav class="flex-grow-1 px-2">
        <?php
        $menu = [
            ['page' => 'dashboard',  'icon' => 'speedometer2',  'label' => 'Tableau de bord'],
            ['page' => 'dons',       'icon' => 'list-ul',        'label' => 'Voir les dons'],
            ['page' => 'versets',    'icon' => 'book',           'label' => 'Ajouter un verset'],
            ['page' => 'parametres', 'icon' => 'gear',           'label' => 'Modifier les paramètres'],
        ];
        foreach ($menu as $item):
            $active = $current === $item['page'] ? 'active' : '';
        ?>
        <a href="<?= $adminBase ?>?page=<?= $item['page'] ?>"
           class="sidebar-nav-link <?= $active ?> d-flex align-items-center gap-3 px-3 py-2 rounded-2 mb-1">
            <i class="bi bi-<?= $item['icon'] ?>"></i>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>

        <div class="sidebar-divider my-3"></div>

        <a href="<?= $adminBase ?>?page=logout"
           class="sidebar-nav-link logout d-flex align-items-center gap-3 px-3 py-2 rounded-2 mb-1">
            <i class="bi bi-box-arrow-left"></i>
            <span>Se déconnecter</span>
        </a>
    </nav>

    <!-- Besoin d'aide -->
    <div class="sidebar-help-box mx-3 mb-3 p-3 rounded-3">
        <div class="fw-semibold text-white small mb-1">
            <i class="bi bi-question-circle me-1 text-warning"></i>Besoin d'aide ?
        </div>
        <div class="text-muted" style="font-size:0.72rem;">
            Telegram : <a href="https://t.me/donorphelinat" target="_blank" class="text-warning text-decoration-none">@donorphelinat</a>
        </div>
    </div>

    <!-- Bouton thème sombre/clair -->
    <div class="px-3 pb-3 text-center">
        <button class="btn btn-admin-theme w-100" onclick="toggleTheme()" id="btnThemeAdmin">
            <i class="bi bi-sun" id="themeIconAdmin"></i>
            <span id="themeTextAdmin">Mode clair</span>
        </button>
    </div>
</aside>
