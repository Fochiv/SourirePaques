<?php
$titre = 'Tableau de bord — Sourires de Pâques';
$devise = $data['params']['devise'] ?? 'XAF';
$nomOrphelinat = $data['params']['nom_orphelinat'] ?? 'Orphelinat Saint-Joseph';
$json_jour = json_encode(array_column($data['evolution_jour'], 'total'));
$json_labels_jour = json_encode(array_column($data['evolution_jour'], 'jour'));
$json_semaine = json_encode(array_column($data['evolution_semaine'], 'total'));
$json_labels_sem = json_encode(array_column($data['evolution_semaine'], 'semaine'));
$adminBase = $adminBase ?? 'adminpanel.php';
$adminUser = htmlspecialchars($_SESSION['admin_username'] ?? 'Administrateur');
$initiale = strtoupper(substr($adminUser, 0, 1));
$objectif = (float)($data['params']['objectif_financier'] ?? 500000);
$montantTotal = (float)($data['montant_total'] ?? 0);
$pct = $objectif > 0 ? min(100, round($montantTotal / $objectif * 100)) : 0;
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
<body>

<div class="admin-layout d-flex min-vh-100">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="d-flex flex-column flex-grow-1">

        <!-- Top bar -->
        <header class="admin-topbar d-flex align-items-center justify-content-between px-4">
            <div></div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn admin-bell-btn position-relative">
                    <i class="bi bi-bell"></i>
                    <span class="admin-notif-dot"></span>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <div class="admin-avatar-sm"><?= $initiale ?></div>
                    <span class="text-white small fw-semibold"><?= $adminUser ?></span>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="admin-main flex-grow-1 p-4">

            <!-- Page header -->
            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Tableau de bord</h2>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Gérez votre site depuis cette interface</span>
                        <span class="admin-online-badge"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>En ligne</span>
                    </div>
                </div>
                <a href="<?= $adminBase ?>?page=dashboard" class="btn btn-admin-refresh">
                    <i class="bi bi-arrow-clockwise me-2"></i>Rafraîchir
                </a>
            </div>

            <!-- KPI Cards — Row 1 -->
            <div class="row g-3 mb-3">
                <!-- Total collecté -->
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="admin-kpi-label">Total collecté</div>
                                <div class="admin-kpi-value"><?= number_format($data['montant_total'], 0, ',', ' ') ?> <small><?= $devise ?></small></div>
                            </div>
                            <div class="admin-kpi-icon admin-kpi-icon-yellow">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                        </div>
                        <a href="<?= $adminBase ?>?page=dons" class="admin-kpi-link">Voir tous les dons →</a>
                    </div>
                </div>
                <!-- Ce mois -->
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="admin-kpi-label">Ce mois</div>
                                <div class="admin-kpi-value"><?= number_format($data['montant_mois'], 0, ',', ' ') ?> <small><?= $devise ?></small></div>
                            </div>
                            <div class="admin-kpi-icon admin-kpi-icon-green">
                                <i class="bi bi-calendar-month"></i>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size:0.72rem;">Aujourd'hui : <?= number_format($data['montant_aujourdhui'], 0, ',', ' ') ?> <?= $devise ?></small>
                    </div>
                </div>
                <!-- Donateurs -->
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="admin-kpi-label">Donateurs</div>
                                <div class="admin-kpi-value"><?= $data['nombre_contributeurs'] ?></div>
                            </div>
                            <div class="admin-kpi-icon admin-kpi-icon-blue">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size:0.72rem;">Dont <?= $data['nombre_anonymes'] ?> anonyme(s)</small>
                    </div>
                </div>
                <!-- Objectif atteint -->
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card admin-kpi-card-accent p-3 rounded-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="admin-kpi-label">Objectif atteint</div>
                                <div class="admin-kpi-value"><?= $pct ?>%</div>
                            </div>
                            <div class="admin-kpi-icon admin-kpi-icon-purple">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                        </div>
                        <div class="admin-mini-progress">
                            <div class="admin-mini-progress-bar" style="width:<?= $pct ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards — Row 2 : métriques secondaires -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="admin-kpi-icon admin-kpi-icon-yellow" style="width:36px;height:36px;font-size:1rem;"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <div class="admin-kpi-label">Cette semaine</div>
                                <div class="fw-bold text-white"><?= number_format($data['montant_semaine'], 0, ',', ' ') ?> <small class="text-muted" style="font-size:0.7rem;"><?= $devise ?></small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="admin-kpi-icon admin-kpi-icon-blue" style="width:36px;height:36px;font-size:1rem;"><i class="bi bi-calculator"></i></div>
                            <div>
                                <div class="admin-kpi-label">Don moyen</div>
                                <div class="fw-bold text-white"><?= number_format($data['don_moyen'], 0, ',', ' ') ?> <small class="text-muted" style="font-size:0.7rem;"><?= $devise ?></small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="admin-kpi-icon admin-kpi-icon-green" style="width:36px;height:36px;font-size:1rem;"><i class="bi bi-award"></i></div>
                            <div>
                                <div class="admin-kpi-label">Plus gros don</div>
                                <div class="fw-bold text-white"><?= number_format($data['plus_gros_don'], 0, ',', ' ') ?> <small class="text-muted" style="font-size:0.7rem;"><?= $devise ?></small></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="admin-kpi-card p-3 rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="admin-kpi-icon admin-kpi-icon-purple" style="width:36px;height:36px;font-size:1rem;"><i class="bi bi-incognito"></i></div>
                            <div>
                                <div class="admin-kpi-label">Dons anonymes</div>
                                <div class="fw-bold text-white"><?= $data['nombre_anonymes'] ?> <small class="text-muted" style="font-size:0.7rem;">sur <?= $data['nombre_contributeurs'] ?></small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts + Donateurs -->
            <div class="row g-4 mb-4">

                <!-- Graphique principal -->
                <div class="col-lg-7">
                    <div class="admin-chart-card p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <h6 class="fw-bold mb-0">Aperçu des dons</h6>
                            <div class="admin-period-btns">
                                <button class="admin-period-btn active" onclick="showPeriod('jour', this)">7 jours</button>
                                <button class="admin-period-btn" onclick="showPeriod('semaine', this)">30 jours</button>
                                <button class="admin-period-btn" onclick="showPeriod('all', this)">Année</button>
                            </div>
                        </div>
                        <canvas id="chartPrincipal" height="200"></canvas>
                    </div>
                </div>

                <!-- Progression vers objectif -->
                <div class="col-lg-5">
                    <div class="admin-chart-card p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="admin-kpi-icon admin-kpi-icon-purple">
                                <i class="bi bi-trophy-fill"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Progression</div>
                                <div class="text-white small">Objectif : <?= number_format($objectif, 0, ',', ' ') ?> <?= $devise ?></div>
                            </div>
                        </div>
                        <div class="admin-kpi-value text-center mb-1" style="font-size:2.5rem;">
                            <?= $pct ?>%
                        </div>
                        <p class="text-muted text-center small mb-3"><?= number_format($montantTotal, 0, ',', ' ') ?> / <?= number_format($objectif, 0, ',', ' ') ?> <?= $devise ?></p>
                        <div class="admin-mini-progress mb-2" style="height:8px;">
                            <div class="admin-mini-progress-bar" style="width:<?= $pct ?>%"></div>
                        </div>

                        <div class="admin-divider my-3"></div>

                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="admin-stat-mini-label">Aujourd'hui</div>
                                <div class="admin-stat-mini-value"><?= number_format($data['montant_aujourdhui'], 0, ',', ' ') ?> <small class="text-muted" style="font-size:0.65rem;"><?= $devise ?></small></div>
                            </div>
                            <div class="col-6">
                                <div class="admin-stat-mini-label">Don moyen</div>
                                <div class="admin-stat-mini-value"><?= number_format($data['don_moyen'], 0, ',', ' ') ?> <small class="text-muted" style="font-size:0.65rem;"><?= $devise ?></small></div>
                            </div>
                            <div class="col-6">
                                <div class="admin-stat-mini-label">Donateurs</div>
                                <div class="admin-stat-mini-value"><?= $data['nombre_contributeurs'] ?></div>
                            </div>
                            <div class="col-6">
                                <div class="admin-stat-mini-label">Anonymes</div>
                                <div class="admin-stat-mini-value"><?= $data['nombre_anonymes'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accès rapides -->
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="<?= $adminBase ?>?page=dons&export=csv" class="admin-kpi-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none">
                        <div class="admin-kpi-icon admin-kpi-icon-green"><i class="bi bi-download"></i></div>
                        <span class="fw-semibold text-white">Exporter CSV</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= $adminBase ?>?page=versets" class="admin-kpi-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none">
                        <div class="admin-kpi-icon admin-kpi-icon-blue"><i class="bi bi-book"></i></div>
                        <span class="fw-semibold text-white">Gérer les versets</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= $adminBase ?>?page=parametres" class="admin-kpi-card p-3 rounded-3 d-flex align-items-center gap-3 text-decoration-none">
                        <div class="admin-kpi-icon admin-kpi-icon-yellow"><i class="bi bi-gear"></i></div>
                        <span class="fw-semibold text-white">Paramètres du site</span>
                    </a>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const dataJour    = <?= $json_jour ?>;
const labelsJour  = <?= $json_labels_jour ?>;
const dataSem     = <?= $json_semaine ?>;
const labelsSem   = <?= $json_labels_sem ?>;

let currentPeriod = 'jour';

function drawLineChart(canvasId, labels, data, color) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const W = canvas.width = canvas.offsetWidth;
    const H = canvas.height = parseInt(canvas.getAttribute('height') || 200);
    const max = Math.max(...data, 1);
    const padL = 50, padB = 40, padT = 20, padR = 20;
    const chartW = W - padL - padR;
    const chartH = H - padT - padB;

    ctx.clearRect(0, 0, W, H);

    // Grid
    ctx.strokeStyle = 'rgba(255,255,255,0.05)';
    ctx.lineWidth = 1;
    for (let i = 0; i <= 4; i++) {
        const y = padT + (chartH / 4) * i;
        ctx.beginPath(); ctx.moveTo(padL, y); ctx.lineTo(W - padR, y); ctx.stroke();
        ctx.fillStyle = 'rgba(255,255,255,0.3)';
        ctx.font = '10px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(Math.round(max - (max / 4) * i).toLocaleString(), padL - 4, y + 4);
    }

    if (data.length < 2) return;

    const step = chartW / (data.length - 1);

    // Fill
    const grad = ctx.createLinearGradient(0, padT, 0, H - padB);
    grad.addColorStop(0, color + '33');
    grad.addColorStop(1, color + '00');
    ctx.beginPath();
    data.forEach((val, i) => {
        const x = padL + i * step;
        const y = padT + chartH - (val / max) * chartH;
        i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
    });
    ctx.lineTo(padL + (data.length - 1) * step, H - padB);
    ctx.lineTo(padL, H - padB);
    ctx.closePath();
    ctx.fillStyle = grad;
    ctx.fill();

    // Line
    ctx.beginPath();
    ctx.strokeStyle = color;
    ctx.lineWidth = 2;
    data.forEach((val, i) => {
        const x = padL + i * step;
        const y = padT + chartH - (val / max) * chartH;
        i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
    });
    ctx.stroke();

    // Dots
    data.forEach((val, i) => {
        const x = padL + i * step;
        const y = padT + chartH - (val / max) * chartH;
        ctx.beginPath();
        ctx.arc(x, y, 3, 0, Math.PI * 2);
        ctx.fillStyle = color;
        ctx.fill();
    });

    // Labels
    const skip = Math.ceil(data.length / 8);
    data.forEach((val, i) => {
        if (i % skip !== 0 && i !== data.length - 1) return;
        if (!labels[i]) return;
        const x = padL + i * step;
        ctx.save();
        ctx.translate(x, H - padB + 8);
        ctx.rotate(-0.5);
        ctx.fillStyle = 'rgba(255,255,255,0.4)';
        ctx.font = '9px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(labels[i], 0, 0);
        ctx.restore();
    });
}

function showPeriod(period, btn) {
    document.querySelectorAll('.admin-period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentPeriod = period;
    redraw();
}

function redraw() {
    if (currentPeriod === 'semaine' || currentPeriod === 'all') {
        drawLineChart('chartPrincipal', labelsSem, dataSem, '#6c5ce7');
    } else {
        const last7 = dataJour.slice(-7);
        const last7Labels = labelsJour.slice(-7);
        drawLineChart('chartPrincipal', last7Labels, last7, '#6c5ce7');
    }
}

window.addEventListener('load', redraw);
window.addEventListener('resize', redraw);

// Theme toggle sync
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeIcons(next);
}
function updateThemeIcons(theme) {
    const icons = ['themeIconAdmin'];
    const texts = ['themeTextAdmin'];
    icons.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    });
    texts.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = theme === 'dark' ? 'Mode clair' : 'Mode sombre';
    });
}
(function(){
    const saved = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    updateThemeIcons(saved);
})();
</script>
</body>
</html>
