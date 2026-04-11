<?php
$titre = 'Merci pour votre don — Sourires de Pâques';
require __DIR__ . '/layout/header.php';
?>

<section class="merci-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">

                <div class="merci-icon animate-pulse mb-4">
                    <div class="merci-circle">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                </div>

                <h1 class="merci-titre mb-3" data-i18n="merci_titre">Merci pour votre générosité !</h1>

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
                        <div class="col-6">
                            <small class="text-muted" data-i18n="merci_statut_don">Statut :</small>
                            <div>
                                <?php if ($don['statut'] === 'succes'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check me-1"></i>Confirmé</span>
                                <?php elseif ($don['statut'] === 'echec'): ?>
                                    <span class="badge bg-danger"><i class="bi bi-x me-1"></i>Échoué</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass me-1"></i>
                                        <span data-i18n="statut_attente">En attente de confirmation</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($don['reference_transaction']): ?>
                        <div class="col-12 mt-2">
                            <small class="text-muted" data-i18n="merci_ref">Référence transaction :</small>
                            <div class="fw-semibold text-info small"><?= htmlspecialchars($don['reference_transaction']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($don['statut'] === 'en_attente' && $don['methode_paiement'] === 'api_mobile_money'): ?>
                    <div class="alert alert-info mt-3 mb-0 small">
                        <i class="bi bi-phone me-2"></i>
                        <span data-i18n="merci_attente_ussd">
                            Une demande de confirmation a été envoyée sur votre téléphone. Validez avec votre code PIN pour finaliser le paiement.
                        </span>
                    </div>
                    <?php endif; ?>
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

<?php require __DIR__ . '/layout/footer.php'; ?>
