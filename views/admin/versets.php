<?php
$titre = 'Gestion des Versets — Sourires de Pâques';
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
                <h2 class="fw-bold mb-1">Gestion des Versets Bibliques</h2>
                <p class="text-muted small mb-0">Ajoutez ou modifiez les versets affichés sur le site</p>
            </div>

            <?php if (isset($_GET['succes'])): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>
                <?= $_GET['succes'] === 'modifie' ? 'Verset modifié avec succès.' : 'Verset ajouté avec succès.' ?>
            </div>
            <?php endif; ?>

            <!-- Formulaire ajout / modification -->
            <div class="admin-chart-card p-4 rounded-3 mb-4">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-<?= $edition ? 'pencil' : 'plus-circle' ?> me-2 text-warning"></i>
                    <?= $edition ? 'Modifier le verset' : 'Ajouter un verset' ?>
                </h5>
                <form action="<?= $adminBase ?>?page=versets" method="POST">
                    <input type="hidden" name="action" value="<?= $edition ? 'modifier' : 'creer' ?>">
                    <?php if ($edition): ?>
                        <input type="hidden" name="id" value="<?= $edition['id'] ?>">
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Référence (FR)</label>
                            <input type="text" name="reference_fr" class="form-control"
                                   placeholder="Ex: Jean 3:16"
                                   value="<?= htmlspecialchars($edition['reference_fr'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Texte (Français)</label>
                            <textarea name="texte_fr" class="form-control" rows="2" required><?= htmlspecialchars($edition['texte_fr'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Référence (EN)</label>
                            <input type="text" name="reference_en" class="form-control"
                                   placeholder="Ex: John 3:16"
                                   value="<?= htmlspecialchars($edition['reference_en'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Texte (English)</label>
                            <textarea name="texte_en" class="form-control" rows="2" required><?= htmlspecialchars($edition['texte_en'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actif" id="actif"
                                       <?= (!$edition || $edition['actif']) ? 'checked' : '' ?>>
                                <label class="form-check-label text-muted" for="actif">Actif</label>
                            </div>
                        </div>
                        <div class="col-md-9 d-flex gap-2 align-items-center">
                            <button type="submit" class="btn btn-donate px-4">
                                <i class="bi bi-save me-1"></i>
                                <?= $edition ? 'Enregistrer' : 'Ajouter' ?>
                            </button>
                            <?php if ($edition): ?>
                                <a href="<?= $adminBase ?>?page=versets" class="btn btn-outline-secondary">Annuler</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Liste des versets -->
            <div class="admin-chart-card rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Référence FR</th>
                                <th>Texte FR</th>
                                <th>Référence EN</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($versets as $v): ?>
                        <tr class="<?= !$v['actif'] ? 'opacity-50' : '' ?>">
                            <td class="fw-semibold text-warning"><?= htmlspecialchars($v['reference_fr']) ?></td>
                            <td><small><?= htmlspecialchars(mb_substr($v['texte_fr'], 0, 80)) ?>...</small></td>
                            <td><small class="text-muted"><?= htmlspecialchars($v['reference_en']) ?></small></td>
                            <td>
                                <a href="<?= $adminBase ?>?page=versets&toggle=<?= $v['id'] ?>" class="badge text-decoration-none bg-<?= $v['actif'] ? 'success' : 'secondary' ?>">
                                    <?= $v['actif'] ? 'Actif' : 'Inactif' ?>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= $adminBase ?>?page=versets&edit=<?= $v['id'] ?>" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= $adminBase ?>?page=versets&supprimer=<?= $v['id'] ?>"
                                       class="btn btn-outline-danger"
                                       onclick="return confirm('Supprimer ce verset ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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
