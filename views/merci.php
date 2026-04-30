<?php
$titre = 'Merci pour votre don — Sourires de Pâques';
require __DIR__ . '/layout/header.php';
$estEnAttente = ($don && $don['statut'] === 'en_attente' && $don['methode_paiement'] === 'api_mobile_money');
$donId = $don['id'] ?? 0;
?>

<section class="merci-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">

                <div class="merci-icon animate-pulse mb-4">
                    <div class="merci-circle" id="merciCircle">
                        <i class="bi bi-heart-fill" id="merciIcon"></i>
                    </div>
                </div>

                <h1 class="merci-titre mb-3" data-i18n="merci_titre" id="merciTitre">Merci pour votre générosité !</h1>

                <!-- Message de confirmation succès (caché par défaut, affiché par JS) -->
                <div id="alertSucces" class="alert alert-success d-none mb-3 fw-semibold fs-5" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Votre transaction a été validée avec succès !
                </div>

                <!-- Message echec (caché par défaut) -->
                <div id="alertEchec" class="alert alert-danger d-none mb-3" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    La transaction a échoué ou a été annulée. Veuillez réessayer.
                </div>

                <p class="lead text-body-readable mb-4" data-i18n="merci_message">
                    Votre contribution apportera de la joie à des enfants à l'occasion de Pâques.
                    Que Dieu vous bénisse pour cet acte d'amour.
                </p>

                <!-- Détails du don -->
                <?php if ($don): ?>
                <div class="merci-details p-4 rounded-4 text-start mb-4">
                    <h5 class="fw-bold mb-3 text-center" data-i18n="merci_details">Détails de votre don</h5>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted" data-i18n="merci_date">Date :</small>
                            <div class="fw-semibold"><?= date('d/m/Y H:i', strtotime($don['created_at'])) ?></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted" data-i18n="merci_montant_don">Montant :</small>
                            <div class="fw-semibold text-warning fs-5">
                                <?= number_format($don['montant'], 0, ',', ' ') ?> XAF
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted" data-i18n="merci_methode_don">Méthode :</small>
                            <div class="fw-semibold">
                                <?= $don['methode_paiement'] === 'api_mobile_money' ? 'Mobile Money' : 'Carte Bancaire' ?>
                            </div>
                        </div>
                        <?php if (!empty($don['telephone'])): ?>
                        <div class="col-6">
                            <small class="text-muted">Téléphone :</small>
                            <div class="fw-semibold"><i class="bi bi-phone me-1 text-warning"></i><?= htmlspecialchars($don['telephone']) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($don['operateur'])): ?>
                        <div class="col-6">
                            <small class="text-muted">Opérateur :</small>
                            <div class="fw-semibold"><?= htmlspecialchars($don['operateur']) ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="col-6">
                            <small class="text-muted" data-i18n="merci_statut_don">Statut :</small>
                            <div id="statutBadgeContainer">
                                <?php if ($don['statut'] === 'succes'): ?>
                                    <span class="badge bg-success" id="statutBadge"><i class="bi bi-check me-1"></i>Confirmé</span>
                                <?php elseif ($don['statut'] === 'echec'): ?>
                                    <span class="badge bg-danger" id="statutBadge"><i class="bi bi-x me-1"></i>Échoué</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark" id="statutBadge">
                                        <i class="bi bi-hourglass me-1"></i>
                                        <span data-i18n="statut_attente">En attente de confirmation</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($don['reference_transaction']): ?>
                        <div class="col-12 mt-2">
                            <small class="text-muted" data-i18n="merci_ref">Référence transaction :</small>
                            <div class="fw-semibold text-info small" id="refTransaction"><?= htmlspecialchars($don['reference_transaction']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($estEnAttente): ?>
                    <!-- Compteur + message d'attente (affiché uniquement si en attente) -->
                    <div id="zoneAttente" class="alert alert-info mt-3 mb-0">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-phone me-2"></i>
                                    <span data-i18n="merci_attente_ussd">
                                        Une demande de confirmation a été envoyée sur votre téléphone. Validez avec votre code PIN pour finaliser le paiement.
                                    </span>
                                </div>
                                <div class="small text-muted">Vérification automatique du statut en cours...</div>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold fs-4 text-warning" id="compteurAffichage">08:00</div>
                                <div class="small text-muted">Temps restant</div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height:6px; border-radius:3px;">
                            <div class="progress-bar bg-warning progress-bar-animated" id="compteurBar" style="width:100%;"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Bouton réessayer (caché par défaut, affiché en cas d'échec) -->
                    <div id="zoneRetry" class="d-none mt-3 text-center">
                        <a href="index.php?page=don" class="btn btn-donate px-4 py-2">
                            <i class="bi bi-arrow-clockwise me-2"></i>Réessayer le paiement
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Verset de bénédiction -->
                <?php if ($verset): ?>
                <div class="merci-verset p-4 rounded-4 mb-4">
                    <div class="merci-verset-icon mb-3">✝</div>
                    <blockquote>
                        <em class="fs-5 d-block mb-2"
                            data-lang-fr="<?= htmlspecialchars($verset['texte_fr']) ?>"
                            data-lang-en="<?= htmlspecialchars($verset['texte_en']) ?>">
                            "<?= htmlspecialchars($verset['texte_fr']) ?>"
                        </em>
                        <cite class="text-warning"
                              data-lang-fr="<?= htmlspecialchars($verset['reference_fr']) ?>"
                              data-lang-en="<?= htmlspecialchars($verset['reference_en']) ?>">
                            — <?= htmlspecialchars($verset['reference_fr']) ?>
                        </cite>
                    </blockquote>
                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="d-flex gap-3 justify-content-center flex-wrap mb-5">
                    <a href="index.php" class="btn btn-donate btn-lg px-5">
                        <i class="bi bi-house me-2"></i>
                        <span data-i18n="btn_retour_accueil">Retour à l'accueil</span>
                    </a>
                    <a href="index.php?page=don" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-heart me-2"></i>
                        <span data-i18n="btn_nouveau_don">Faire un autre don</span>
                    </a>
                </div>

                <!-- Contact après le don -->
                <div class="contact-inline-box p-4 rounded-3 mb-4">
                    <h5 class="fw-bold mb-2">
                        <i class="bi bi-telegram me-2 text-warning"></i>
                        <span data-i18n="contact_merci_titre">Besoin d'aide ou problème de paiement ?</span>
                    </h5>
                    <p class="text-body-readable small mb-3" data-i18n="contact_merci_desc">
                        Contactez l'administrateur via Telegram pour toute question ou pour notifier votre paiement.
                    </p>
                    <a href="https://t.me/donorphelinat" target="_blank" class="btn btn-donate px-4">
                        <i class="bi bi-telegram me-2"></i>Telegram : +1 902 812 0154
                    </a>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="bi bi-link-45deg me-1"></i>
                        <a href="https://t.me/donorphelinat" target="_blank" class="text-warning text-decoration-none">t.me/donorphelinat</a>
                    </p>
                </div>

                <p class="text-body-readable small" data-i18n="merci_partage">
                    Partagez cette cause avec vos proches pour amplifier l'impact de votre geste.
                </p>

            </div>
        </div>
    </div>
</section>

<?php if ($estEnAttente && $donId > 0): ?>
<script>
(function() {
    var donId        = <?= (int)$donId ?>;
    var totalSecs    = 480; // 8 minutes
    var remaining    = totalSecs;
    var pollingDone  = false;
    var intervalId   = null;
    var counterId    = null;

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function renderCompteur() {
        var mins = Math.floor(remaining / 60);
        var secs = remaining % 60;
        var el = document.getElementById('compteurAffichage');
        var bar = document.getElementById('compteurBar');
        if (el) el.textContent = pad(mins) + ':' + pad(secs);
        if (bar) bar.style.width = ((remaining / totalSecs) * 100) + '%';
    }

    function stopAll() {
        if (intervalId) { clearInterval(intervalId); intervalId = null; }
        if (counterId)  { clearInterval(counterId);  counterId  = null; }
        pollingDone = true;
    }

    function onSuccess() {
        stopAll();
        var za = document.getElementById('zoneAttente');
        if (za) za.classList.add('d-none');

        var zr = document.getElementById('zoneRetry');
        if (zr) zr.classList.add('d-none');

        var alertS = document.getElementById('alertSucces');
        if (alertS) alertS.classList.remove('d-none');

        var alertE = document.getElementById('alertEchec');
        if (alertE) alertE.classList.add('d-none');

        var badge = document.getElementById('statutBadge');
        if (badge) {
            badge.className = 'badge bg-success';
            badge.innerHTML = '<i class="bi bi-check me-1"></i>Confirmé';
        }

        var circle = document.getElementById('merciCircle');
        if (circle) circle.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function onEchec() {
        stopAll();
        var za = document.getElementById('zoneAttente');
        if (za) za.classList.add('d-none');

        var alertE = document.getElementById('alertEchec');
        if (alertE) alertE.classList.remove('d-none');

        var badge = document.getElementById('statutBadge');
        if (badge) {
            badge.className = 'badge bg-danger';
            badge.innerHTML = '<i class="bi bi-x me-1"></i>Échoué';
        }

        var zr = document.getElementById('zoneRetry');
        if (zr) zr.classList.remove('d-none');

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function onTimeout() {
        // Compteur arrivé à 0 : on demande au serveur de marquer en échec
        // (avec une dernière vérification API)
        fetch('index.php?page=marquer_echec&don_id=' + donId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.statut === 'succes') {
                    onSuccess();
                } else {
                    onEchec();
                }
            })
            .catch(function() {
                onEchec();
            });
    }

    function checkStatut() {
        if (pollingDone) return;
        fetch('index.php?page=check_statut&don_id=' + donId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data) return;
                if (data.statut === 'succes') {
                    onSuccess();
                } else if (data.statut === 'echec') {
                    onEchec();
                }
            })
            .catch(function() { /* on continue */ });
    }

    // Polling toutes les 3 secondes
    intervalId = setInterval(checkStatut, 3000);

    // Compteur chaque seconde
    renderCompteur();
    counterId = setInterval(function() {
        remaining--;
        if (remaining <= 0) {
            remaining = 0;
            renderCompteur();
            stopAll();
            onTimeout();
        } else {
            renderCompteur();
        }
    }, 1000);

    // Première vérification immédiate
    checkStatut();
})();
</script>
<?php endif; ?>

<?php require __DIR__ . '/layout/footer.php'; ?>
