<?php
$titre = 'Paramètres — Sourires de Pâques';
$adminBase = $adminBase ?? 'adminpanel.php';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-layout d-flex min-vh-100">
    <?php require __DIR__ . '/sidebar.php'; ?>
    <div class="d-flex flex-column flex-grow-1">
        <header class="admin-topbar d-flex align-items-center justify-content-end px-4">
            <div class="d-flex align-items-center gap-2">
                <div class="admin-avatar-sm"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
                <span class="text-white small fw-semibold"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Administrateur') ?></span>
            </div>
        </header>
        <main class="admin-main flex-grow-1 p-4">
            <div class="mb-4">
                <h2 class="fw-bold mb-1">Paramètres du Site</h2>
                <p class="text-muted small mb-0">Configurez les informations du site et de l'orphelinat</p>
            </div>

            <?php if ($succes): ?>
            <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($succes) ?></div>
            <?php endif; ?>
            <?php if ($erreur): ?>
            <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Paramètres site -->
                <div class="col-lg-8">
                    <div class="admin-chart-card p-4 rounded-3 mb-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-sliders me-2 text-warning"></i>Paramètres Généraux
                        </h5>
                        <form action="<?= $adminBase ?>?page=parametres" method="POST">
                            <input type="hidden" name="action" value="parametres_site">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom de l'orphelinat</label>
                                    <input type="text" name="nom_orphelinat" class="form-control"
                                           value="<?= htmlspecialchars($params['nom_orphelinat'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Objectif financier</label>
                                    <input type="number" name="objectif_financier" class="form-control"
                                           value="<?= htmlspecialchars($params['objectif_financier'] ?? '500000') ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Devise</label>
                                    <select name="devise" class="form-select">
                                        <?php foreach (['XAF', 'XOF', 'GNF', 'CDF', 'EUR', 'USD'] as $d): ?>
                                            <option value="<?= $d ?>" <?= ($params['devise'] ?? '') === $d ? 'selected' : '' ?>><?= $d ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message d'accueil (Français)</label>
                                    <textarea name="message_accueil_fr" class="form-control" rows="3"><?= htmlspecialchars($params['message_accueil_fr'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message d'accueil (English)</label>
                                    <textarea name="message_accueil_en" class="form-control" rows="3"><?= htmlspecialchars($params['message_accueil_en'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Présentation de l'orphelinat (Français)</label>
                                    <textarea name="presentation_orphelinat_fr" class="form-control" rows="4"><?= htmlspecialchars($params['presentation_orphelinat_fr'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Présentation de l'orphelinat (English)</label>
                                    <textarea name="presentation_orphelinat_en" class="form-control" rows="4"><?= htmlspecialchars($params['presentation_orphelinat_en'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-donate px-4">
                                    <i class="bi bi-save me-1"></i>Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Changer mot de passe -->
                <div class="col-lg-4">
                    <div class="admin-chart-card p-4 rounded-3">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-shield-lock me-2 text-danger"></i>Sécurité
                        </h5>
                        <form action="<?= $adminBase ?>?page=parametres" method="POST">
                            <input type="hidden" name="action" value="changer_mdp">
                            <div class="mb-3">
                                <label class="form-label">Ancien mot de passe</label>
                                <input type="password" name="ancien_mdp" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="nouveau_mdp" class="form-control" minlength="8" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="confirmer_mdp" class="form-control" minlength="8" required>
                            </div>
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-key me-1"></i>Changer le mot de passe
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleTheme() {
    const html = document.documentElement;
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    const icon = document.getElementById('themeIconAdmin');
    const text = document.getElementById('themeTextAdmin');
    if (icon) icon.className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    if (text) text.textContent = next === 'dark' ? 'Mode clair' : 'Mode sombre';
}
(function(){
    const saved = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    const icon = document.getElementById('themeIconAdmin');
    const text = document.getElementById('themeTextAdmin');
    if (icon) icon.className = saved === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    if (text) text.textContent = saved === 'dark' ? 'Mode clair' : 'Mode sombre';
})();
</script>
</body>
</html>
