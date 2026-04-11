<?php
$titre = 'Paiement Wave — Sourires de Pâques';
require __DIR__ . '/layout/header.php';
?>
<section class="merci-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="merci-icon mb-4">
                    <div class="merci-circle" style="background: linear-gradient(135deg, #00b9f1, #0073e6);">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-3" data-i18n="wave_titre">Finaliser avec Wave</h2>
                <p class="text-muted mb-4" data-i18n="wave_desc">
                    Cliquez sur le bouton ci-dessous pour ouvrir l'application Wave et confirmer votre paiement de
                    <strong class="text-warning"><?= number_format($montant, 0, ',', ' ') ?> XAF</strong>.
                </p>
                <a href="<?= htmlspecialchars($waveUrl) ?>" target="_blank" class="btn btn-lg px-5 py-3 mb-4"
                   style="background: linear-gradient(135deg, #00b9f1, #0073e6); color: white; border-radius: 50px;">
                    <i class="bi bi-arrow-up-right-circle me-2"></i>
                    <span data-i18n="btn_wave">Ouvrir Wave pour payer</span>
                </a>
                <div class="text-muted small" data-i18n="wave_apres">
                    Après confirmation dans Wave, votre don sera automatiquement enregistré.
                </div>
                <div class="mt-4">
                    <a href="index.php?page=merci&don_id=<?= (int)$donId ?>" class="btn btn-outline-light">
                        <span data-i18n="wave_deja_paye">J'ai déjà payé</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/layout/footer.php'; ?>
