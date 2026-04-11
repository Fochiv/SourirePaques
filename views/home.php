<?php
$titre = 'Sourires de Pâques — Donnez de l\'espoir';
$params = $data['params'] ?? [];
$lang = $_SESSION['lang'] ?? 'fr';
$msg_accueil = $lang === 'en' ? ($params['message_accueil_en'] ?? '') : ($params['message_accueil_fr'] ?? '');
$presentation = $lang === 'en' ? ($params['presentation_orphelinat_en'] ?? '') : ($params['presentation_orphelinat_fr'] ?? '');
$objectif = (float)($params['objectif_financier'] ?? 500000);
$devise = $params['devise'] ?? 'XAF';
require __DIR__ . '/layout/header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-overlay"></div>
    <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1600&q=80')"></div>

    <div class="container position-relative z-2 py-5 text-center text-white">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-cross animate-float mb-3">✝</div>
                <h1 class="hero-title display-4 fw-bold mb-3" data-i18n="hero_titre">
                    Sourires de Pâques
                </h1>
                <p class="hero-subtitle lead mb-4" data-i18n="hero_sous_titre">
                    Chaque don, une lumière d'espoir dans une vie d'enfant.
                </p>
                <div class="hero-verse p-3 rounded mb-4">
                    <?php if ($data['verset_aleatoire']): ?>
                        <em class="d-block" data-lang-fr="<?= htmlspecialchars($data['verset_aleatoire']['texte_fr']) ?>"
                            data-lang-en="<?= htmlspecialchars($data['verset_aleatoire']['texte_en']) ?>">
                            "<?= htmlspecialchars($data['verset_aleatoire']['texte_fr']) ?>"
                        </em>
                        <small class="text-warning mt-1 d-block"
                               data-lang-fr="<?= htmlspecialchars($data['verset_aleatoire']['reference_fr']) ?>"
                               data-lang-en="<?= htmlspecialchars($data['verset_aleatoire']['reference_en']) ?>">
                            — <?= htmlspecialchars($data['verset_aleatoire']['reference_fr']) ?>
                        </small>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="index.php?page=don" class="btn btn-donate btn-lg px-5 py-3">
                        <i class="bi bi-heart-fill me-2"></i>
                        <span data-i18n="btn_donner_maintenant">Donner maintenant</span>
                    </a>
                    <a href="#progression" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        <span data-i18n="btn_progression">Voir la progression</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="var(--bg-body)"/>
        </svg>
    </div>
</section>

<!-- ===== STATISTIQUES ===== -->
<section class="stats-section py-5" id="progression">
    <div class="container">
        <div class="row g-4 justify-content-center text-center mb-5">
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-3 h-100">
                    <div class="stat-icon mb-2"><i class="bi bi-cash-coin fs-2 text-warning"></i></div>
                    <div class="stat-number fw-bold" id="statMontant">
                        <?= number_format($data['montant_total'], 0, ',', ' ') ?> <?= $devise ?>
                    </div>
                    <div class="stat-label small" data-i18n="stat_collecte">Collecté</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-3 h-100">
                    <div class="stat-icon mb-2"><i class="bi bi-flag-fill fs-2 text-success"></i></div>
                    <div class="stat-number fw-bold">
                        <?= number_format($objectif, 0, ',', ' ') ?> <?= $devise ?>
                    </div>
                    <div class="stat-label small" data-i18n="stat_objectif">Objectif</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-3 h-100">
                    <div class="stat-icon mb-2"><i class="bi bi-people-fill fs-2 text-info"></i></div>
                    <div class="stat-number fw-bold"><?= $data['nombre_contributeurs'] ?></div>
                    <div class="stat-label small" data-i18n="stat_donateurs">Donateurs</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-3 h-100">
                    <div class="stat-icon mb-2"><i class="bi bi-percent fs-2 text-danger"></i></div>
                    <div class="stat-number fw-bold"><?= $data['pourcentage'] ?>%</div>
                    <div class="stat-label small" data-i18n="stat_atteint">Atteint</div>
                </div>
            </div>
        </div>

        <!-- Barre de progression -->
        <div class="progress-section p-4 rounded-3">
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-semibold" data-i18n="progression_label">Progression vers l'objectif</span>
                <span class="text-warning fw-bold"><?= $data['pourcentage'] ?>%</span>
            </div>
            <div class="progress" style="height: 20px; border-radius: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                     role="progressbar"
                     style="width: <?= $data['pourcentage'] ?>%"
                     aria-valuenow="<?= $data['pourcentage'] ?>"
                     aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <p class="text-body-readable small mt-2 text-center" data-i18n="progression_aide">
                Aidez-nous à atteindre notre objectif. Chaque contribution compte !
            </p>
        </div>
    </div>
</section>

<!-- ===== PRÉSENTATION ORPHELINAT ===== -->
<section class="orphelinat-section py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="section-badge mb-3">
                    <i class="bi bi-house-heart me-2"></i>
                    <span data-i18n="badge_orphelinat">Notre orphelinat</span>
                </div>
                <h2 class="section-title mb-4" data-i18n-placeholder="params_nom_orphelinat">
                    <?= htmlspecialchars($params['nom_orphelinat'] ?? 'Orphelinat Saint-Joseph') ?>
                </h2>
                <p class="text-body-readable lead mb-4" data-i18n-placeholder="params_presentation">
                    <?= htmlspecialchars($presentation) ?>
                </p>
                <a href="index.php?page=don" class="btn btn-donate px-4 py-2">
                    <i class="bi bi-heart me-2"></i>
                    <span data-i18n="btn_soutenir">Soutenir l'orphelinat</span>
                </a>
            </div>
            <div class="col-lg-6">
                <div class="images-grid">
                    <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=600&q=80"
                         alt="Enfant orphelin" class="img-grid-main rounded-3">
                    <div class="img-grid-side">
                        <img src="https://images.unsplash.com/photo-1519340241574-2cec6aef0c01?w=400&q=80"
                             alt="Enfant dans le besoin" class="rounded-3 mb-2">
                        <img src="https://images.unsplash.com/photo-1547234935-80c7145ec969?w=400&q=80"
                             alt="Orphelins" class="rounded-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SECTION SPIRITUELLE ===== -->
<section class="spirituelle-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge mb-3 mx-auto">
                <i class="bi bi-book me-2"></i>
                <span data-i18n="badge_parole">Paroles Inspirantes</span>
            </div>
            <h2 class="section-title" data-i18n="titre_spirituel">La Générosité dans la Bible</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <?php
            $versets_affichage = [
                ['fr' => 'Il y a plus de bonheur à donner qu\'à recevoir.', 'ref_fr' => 'Actes 20:35', 'en' => 'It is more blessed to give than to receive.', 'ref_en' => 'Acts 20:35'],
                ['fr' => 'Que chacun donne comme il l\'a résolu en son cœur, car Dieu aime celui qui donne avec joie.', 'ref_fr' => '2 Corinthiens 9:7', 'en' => 'Each one must give as he has decided in his heart, for God loves a cheerful giver.', 'ref_en' => '2 Corinthians 9:7'],
                ['fr' => 'Celui qui donne au pauvre prête à l\'Éternel, qui lui rendra ce qu\'il a donné.', 'ref_fr' => 'Proverbes 19:17', 'en' => 'Whoever is generous to the poor lends to the Lord, and he will repay him for his deed.', 'ref_en' => 'Proverbs 19:17'],
                ['fr' => 'En vérité je vous le dis, dans la mesure où vous l\'avez fait à l\'un de ces plus petits, c\'est à moi que vous l\'avez fait.', 'ref_fr' => 'Matthieu 25:40', 'en' => 'Truly, I say to you, as you did it to one of the least of these, you did it to me.', 'ref_en' => 'Matthew 25:40'],
            ];
            foreach ($versets_affichage as $v): ?>
            <div class="col-md-6 col-lg-3">
                <div class="verset-card p-4 rounded-3 h-100 text-center">
                    <div class="verset-icon mb-3">✝</div>
                    <blockquote class="verset-texte mb-2">
                        <em data-lang-fr="<?= htmlspecialchars($v['fr']) ?>"
                            data-lang-en="<?= htmlspecialchars($v['en']) ?>">
                            "<?= htmlspecialchars($v['fr']) ?>"
                        </em>
                    </blockquote>
                    <cite class="verset-ref d-block"
                          data-lang-fr="<?= htmlspecialchars($v['ref_fr']) ?>"
                          data-lang-en="<?= htmlspecialchars($v['ref_en']) ?>">
                        — <?= htmlspecialchars($v['ref_fr']) ?>
                    </cite>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== APPEL À L'ACTION ===== -->
<section class="cta-section py-5 text-center">
    <div class="container">
        <div class="cta-box p-5 rounded-4">
            <h2 class="display-6 fw-bold mb-3" data-i18n="cta_titre">Votre générosité peut tout changer</h2>
            <p class="cta-desc mb-4" data-i18n="cta_desc">
                Un simple geste de votre part peut offrir un repas, un vêtement, un sourire — et surtout de l'amour — à un enfant qui en a désespérément besoin.
            </p>
            <a href="index.php?page=don" class="btn btn-donate btn-lg px-5 py-3">
                <i class="bi bi-heart-fill me-2"></i>
                <span data-i18n="btn_donner_maintenant">Donner maintenant</span>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
