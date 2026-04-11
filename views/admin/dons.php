<?php
$titre = 'Gestion des Dons — Sourires de Pâques';
$devise = $params['devise'] ?? 'XAF';
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

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="fw-bold mb-1">Gestion des Dons</h2>
                    <p class="text-muted small mb-0">Liste de toutes les contributions reçues</p>
                </div>
                <a href="<?= $adminBase ?>?page=dons&export=csv" class="btn btn-admin-refresh">
                    <i class="bi bi-download me-1"></i>Exporter CSV
                </a>
            </div>

            <!-- Formulaire de recherche -->
            <div class="admin-chart-card p-3 rounded-3 mb-4">
                <form method="GET" action="<?= $adminBase ?>">
                    <input type="hidden" name="page" value="dons">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="recherche" class="form-control"
                                   placeholder="Nom, prénom, email..." value="<?= htmlspecialchars($recherche) ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="montant_min" class="form-control"
                                   placeholder="Montant min" value="<?= htmlspecialchars($montant_min) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-donate w-100">
                                <i class="bi bi-search me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tableau des dons -->
            <div class="admin-chart-card rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle admin-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Donateur</th>
                                <th>Pays / Ville</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Référence</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($dons)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-5">Aucun don trouvé.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dons as $d): ?>
                            <tr>
                                <td><small class="text-muted">#<?= $d['id'] ?></small></td>
                                <td><small><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></small></td>
                                <td>
                                    <?php if ($d['type'] === 'anonyme'): ?>
                                        <span class="text-muted"><i class="bi bi-incognito me-1"></i>Anonyme</span>
                                    <?php else: ?>
                                        <div class="fw-semibold"><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></div>
                                        <?php if ($d['email']): ?>
                                            <small class="text-muted"><?= htmlspecialchars($d['email']) ?></small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <?= htmlspecialchars($d['pays'] ?: '—') ?>
                                        <?= $d['ville'] ? '/ ' . htmlspecialchars($d['ville']) : '' ?>
                                    </small>
                                </td>
                                <td class="fw-bold text-warning"><?= number_format($d['montant'], 0, ',', ' ') ?> <?= $devise ?></td>
                                <td>
                                    <?php if ($d['methode_paiement'] === 'api_mobile_money'): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-phone me-1"></i>
                                            <?= htmlspecialchars($d['operateur'] ?: 'Mobile Money') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-credit-card me-1"></i>Virement
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted font-monospace"><?= htmlspecialchars($d['reference_transaction'] ?: '—') ?></small></td>
                                <td>
                                    <?php
                                    $badges = ['succes' => 'success', 'echec' => 'danger', 'en_attente' => 'secondary'];
                                    $labels = ['succes' => 'Confirmé', 'echec' => 'Échoué', 'en_attente' => 'En attente'];
                                    $s = $d['statut'] ?? 'en_attente';
                                    ?>
                                    <span class="badge bg-<?= $badges[$s] ?? 'secondary' ?>">
                                        <?= $labels[$s] ?? $s ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-2 border-top border-secondary">
                    <small class="text-muted"><?= count($dons) ?> don(s) trouvé(s)</small>
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
