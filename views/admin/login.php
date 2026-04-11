<?php
$titre = 'Connexion Admin — Sourires de Pâques';
$adminBase = $adminBase ?? 'adminpanel.php';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-login-body d-flex align-items-center justify-content-center min-vh-100">

<div class="col-sm-10 col-md-6 col-lg-4">
    <div class="admin-login-card p-5 rounded-4 text-center">

        <!-- Logo -->
        <div class="mb-4">
            <div class="admin-login-avatar mx-auto mb-3">
                <span>✝</span>
            </div>
            <h3 class="fw-bold text-white mb-1">Sourires <span style="color:#6c5ce7;">de Pâques</span></h3>
            <p class="text-muted small">Espace Administrateur — Accès restreint</p>
        </div>

        <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger text-start mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($erreur) ?>
        </div>
        <?php endif; ?>

        <form action="<?= $adminBase ?>?page=login_traiter" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small fw-semibold">Identifiant</label>
                <div class="input-group">
                    <span class="input-group-text admin-input-icon"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control admin-input"
                           placeholder="Votre identifiant" required autofocus>
                </div>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label small fw-semibold">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text admin-input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="pwd" class="form-control admin-input"
                           placeholder="••••••••" required>
                    <button type="button" class="btn admin-input-icon" onclick="togglePwd()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-admin-primary w-100 py-3 mb-3 fw-bold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </button>
        </form>

        <a href="../index.php" class="d-block mt-3 text-muted small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Retour au site
        </a>

        <!-- Theme toggle -->
        <div class="mt-4 pt-3 border-top border-secondary">
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleTheme()">
                <i class="bi bi-sun" id="themeIconLogin"></i>
                <span id="themeTextLogin">Mode clair</span>
            </button>
        </div>
    </div>
</div>

<script>
function togglePwd() {
    const pwd = document.getElementById('pwd');
    const icon = document.getElementById('eyeIcon');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
    icon.className = pwd.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
function toggleTheme() {
    const html = document.documentElement;
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    const icon = document.getElementById('themeIconLogin');
    const text = document.getElementById('themeTextLogin');
    if (icon) icon.className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    if (text) text.textContent = next === 'dark' ? 'Mode clair' : 'Mode sombre';
}
(function(){
    const saved = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    const icon = document.getElementById('themeIconLogin');
    const text = document.getElementById('themeTextLogin');
    if (icon) icon.className = saved === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    if (text) text.textContent = saved === 'dark' ? 'Mode clair' : 'Mode sombre';
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
