<?php
$titre = 'Faire un don — Sourires de Pâques';
require __DIR__ . '/layout/header.php';
$erreur = $_SESSION['erreur'] ?? null;
unset($_SESSION['erreur']);
$etape         = $_GET['etape'] ?? 'formulaire';
$otp_required  = $_SESSION['otp_required'] ?? false;
$ussd_code     = $_SESSION['ussd_code'] ?? null;
$don_id_pending= $_SESSION['don_id_pending'] ?? null;
$form_data     = $_SESSION['form_data'] ?? [];
unset($_SESSION['otp_required'], $_SESSION['ussd_code'], $_SESSION['don_id_pending'], $_SESSION['form_data']);
?>

<section class="don-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Titre -->
                <div class="text-center mb-5">
                    <div class="section-badge mb-3 mx-auto">
                        <i class="bi bi-heart-fill me-2"></i>
                        <span data-i18n="badge_don">Faire un don</span>
                    </div>
                    <h1 class="section-title" data-i18n="don_titre">Soutenez l'Orphelinat</h1>
                    <p class="text-body-readable" data-i18n="don_sous_titre">
                        Votre contribution est sécurisée et transparente.
                    </p>
                </div>

                <!-- Contact avant le don -->
                <div class="contact-inline-box p-4 rounded-3 mb-4 d-flex align-items-center gap-3 flex-wrap">
                    <div class="contact-inline-icon">
                        <i class="bi bi-telegram fs-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold mb-1" data-i18n="contact_don_titre">Besoin d'aide pour votre don ?</div>
                        <p class="text-body-readable small mb-0" data-i18n="contact_don_desc">
                            En cas de problème de paiement, contactez-nous sur Telegram.
                        </p>
                    </div>
                    <a href="https://t.me/donorphelinat" target="_blank" class="btn btn-donate btn-sm px-4">
                        <i class="bi bi-telegram me-1"></i>+1 902 812 0154
                    </a>
                </div>

                <?php if ($erreur): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($erreur) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if ($otp_required): ?>
                <!-- === FORMULAIRE OTP === -->
                <div class="form-card p-4 p-md-5 rounded-4 mb-4">
                    <div class="alert alert-warning">
                        <i class="bi bi-shield-lock me-2"></i>
                        <strong data-i18n="otp_titre">Code OTP requis</strong>
                        <p class="mb-0 mt-2 small" data-i18n="otp_desc">
                            <?php if ($ussd_code): ?>
                                Composez <strong class="text-warning"><?= htmlspecialchars($ussd_code) ?></strong> sur votre téléphone pour recevoir votre code OTP, puis saisissez-le ci-dessous.
                            <?php else: ?>
                                Un code OTP a été envoyé par SMS sur votre téléphone. Saisissez-le ci-dessous.
                            <?php endif; ?>
                        </p>
                    </div>
                    <form action="index.php?page=traiter_don" method="POST">
                        <?php foreach ($form_data as $k => $v): ?>
                            <?php if ($k !== 'otp'): ?>
                                <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars(is_array($v) ? $v[0] : $v) ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" data-i18n="label_otp">Code OTP</label>
                            <input type="text" name="otp" class="form-control form-control-lg text-center letter-spacing-5"
                                   maxlength="8" placeholder="······" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-donate w-100 py-3">
                            <i class="bi bi-check-circle me-2"></i>
                            <span data-i18n="btn_valider_otp">Valider le paiement</span>
                        </button>
                    </form>
                </div>
                <?php else: ?>

                <!-- === FORMULAIRE PRINCIPAL === -->
                <form id="formDon" action="index.php?page=traiter_don" method="POST" novalidate>
                    <div class="form-card p-4 p-md-5 rounded-4">

                        <!-- ÉTAPE 1 : Type de don -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold" data-i18n="etape1_titre">
                                <span class="step-number">1</span> Votre identité
                            </h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="type-card w-100 text-center p-3 rounded-3 cursor-pointer active" id="labelAnonyme">
                                        <input type="radio" name="type" value="anonyme" class="d-none" checked onchange="toggleType(this.value)">
                                        <i class="bi bi-incognito fs-3 d-block mb-2"></i>
                                        <span class="fw-semibold" data-i18n="don_anonyme">Don Anonyme</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="type-card w-100 text-center p-3 rounded-3 cursor-pointer" id="labelIdentifie">
                                        <input type="radio" name="type" value="identifie" class="d-none" onchange="toggleType(this.value)">
                                        <i class="bi bi-person-check fs-3 d-block mb-2"></i>
                                        <span class="fw-semibold" data-i18n="don_identifie">Don Identifié</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Champs identité (masqués si anonyme) -->
                        <div id="champsIdentite" class="d-none mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" data-i18n="label_nom">Nom</label>
                                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($form_data['nom'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-i18n="label_prenom">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($form_data['prenom'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-i18n="label_pays">Pays</label>
                                    <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($form_data['pays'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-i18n="label_ville">Ville</label>
                                    <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($form_data['ville'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-i18n="label_email">Email (optionnel)</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <!-- ÉTAPE 2 : Montant -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold" data-i18n="etape2_titre">
                                <span class="step-number">2</span> Montant du don
                            </h5>
                            <div class="quick-amounts d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-amount" onclick="setMontant(1000)">1 000 XAF</button>
                                <button type="button" class="btn btn-amount" onclick="setMontant(2500)">2 500 XAF</button>
                                <button type="button" class="btn btn-amount" onclick="setMontant(5000)">5 000 XAF</button>
                                <button type="button" class="btn btn-amount" onclick="setMontant(10000)">10 000 XAF</button>
                                <button type="button" class="btn btn-amount" onclick="setMontant(25000)">25 000 XAF</button>
                            </div>
                            <div class="input-group input-group-lg">
                                <input type="number" name="montant" id="montantInput" class="form-control"
                                       placeholder="Montant personnalisé" min="100" step="100"
                                       value="<?= htmlspecialchars($form_data['montant'] ?? '') ?>" required>
                                <span class="input-group-text">XAF</span>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <!-- ÉTAPE 3 : Méthode de paiement -->
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold" data-i18n="etape3_titre">
                                <span class="step-number">3</span> Méthode de paiement
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="methode-card w-100 p-3 rounded-3 cursor-pointer active" id="labelMobileMoney">
                                        <input type="radio" name="methode" value="api_mobile_money" class="d-none" checked
                                               onchange="toggleMethode(this.value)">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-phone-fill fs-4 text-warning"></i>
                                            <div>
                                                <div class="fw-semibold" data-i18n="methode_mobile">Mobile Money</div>
                                                <small class="text-muted">MTN, Orange, Wave...</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="methode-card w-100 p-3 rounded-3 cursor-pointer" id="labelVirement">
                                        <input type="radio" name="methode" value="virement_bancaire" class="d-none"
                                               onchange="toggleMethode(this.value)">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-credit-card-fill fs-4 text-info"></i>
                                            <div>
                                                <div class="fw-semibold" data-i18n="methode_virement">Carte Bancaire</div>
                                                <small class="text-muted">Master Card</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Champs Mobile Money -->
                            <div id="champsMobileMoney" class="mt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" data-i18n="label_pays_paiement">Pays</label>
                                        <select name="code_pays_tel" id="selectPays" class="form-select" onchange="chargerOperateurs(this.value)" required>
                                            <option value="" data-i18n="option_pays">— Sélectionner votre pays —</option>
                                            <?php if (!empty($paysOperateurs)): ?>
                                                <?php foreach ($paysOperateurs as $p): ?>
                                                    <option value="<?= htmlspecialchars($p['code']) ?>"
                                                            data-operators="<?= htmlspecialchars(json_encode($p['operators'])) ?>"
                                                            data-currency="<?= htmlspecialchars($p['currency']) ?>">
                                                        <?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['currency']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" data-i18n="label_operateur">Opérateur</label>
                                        <select name="operateur" id="selectOperateur" class="form-select" required>
                                            <option value="" data-i18n="option_operateur">— Sélectionner d'abord le pays —</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" data-i18n="label_telephone">Numéro de téléphone</label>
                                        <input type="tel" name="telephone" class="form-control"
                                               placeholder="Ex: 670000000"
                                               value="<?= htmlspecialchars($form_data['telephone'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Carte bancaire (masquée par défaut, visible uniquement si sélectionnée) -->
                            <div id="champsVirement" class="d-none mt-3">
                                <div class="alert alert-info d-flex align-items-start gap-3">
                                    <i class="bi bi-info-circle-fill fs-4 mt-1"></i>
                                    <div>
                                        <strong data-i18n="virement_info_titre">Informations de virement</strong>
                                        <p class="mb-1 mt-2" data-i18n="virement_info_desc">Effectuez votre paiement vers la carte suivante :</p>
                                        <div class="card-number-display d-flex align-items-center justify-content-between gap-2">
                                            <div>
                                                <i class="bi bi-credit-card me-2"></i>
                                                <strong id="numeroCarteTexte" class="text-warning fs-5 tracking-wider">5430 0502 3923 6064</strong>
                                            </div>
                                            <button type="button" id="btnCopierCarte" onclick="copierNumeroCarte()" class="btn btn-sm btn-copy-card" title="Copier le numéro">
                                                <i class="bi bi-copy" id="iconeCopie"></i>
                                                <span id="texteCopie">Copier</span>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-2" data-i18n="virement_note">
                                            Notez la référence de votre virement pour suivi.
                                        </small>
                                        <div class="mt-3 p-3 rounded-3" style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);">
                                            <div class="fw-semibold small mb-1"><i class="bi bi-telegram me-1"></i>Notifiez votre paiement</div>
                                            <p class="text-muted small mb-2">Après le virement, contactez-nous sur Telegram pour confirmer.</p>
                                            <a href="https://t.me/donorphelinat" target="_blank" class="btn btn-donate btn-sm px-3">
                                                Telegram : +1 902 812 0154
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <!-- RÉSUMÉ AVANT PAIEMENT -->
                        <div class="resume-section p-4 rounded-3 mb-4" id="resumeDon">
                            <h6 class="fw-bold mb-3" data-i18n="resume_titre">
                                <i class="bi bi-clipboard-check me-2"></i>Résumé de votre don
                            </h6>
                            <div class="row g-2">
                                <div class="col-6"><small class="text-muted" data-i18n="resume_type">Type :</small></div>
                                <div class="col-6"><small id="resumeType" class="fw-semibold">Don Anonyme</small></div>
                                <div class="col-6"><small class="text-muted" data-i18n="resume_montant">Montant :</small></div>
                                <div class="col-6"><small id="resumeMontant" class="fw-semibold text-warning">—</small></div>
                                <div class="col-6"><small class="text-muted" data-i18n="resume_methode">Méthode :</small></div>
                                <div class="col-6"><small id="resumeMethode" class="fw-semibold">Mobile Money</small></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-donate w-100 py-3 fs-5" id="btnSubmit">
                            <i class="bi bi-lock-fill me-2"></i>
                            <span data-i18n="btn_payer">Confirmer et Payer</span>
                        </button>
                        <p class="text-center text-muted small mt-2">
                            <i class="bi bi-shield-check me-1 text-success"></i>
                            <span data-i18n="securise_label">Paiement sécurisé</span>
                        </p>
                    </div>
                </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
