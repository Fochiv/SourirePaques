<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sourires de Pâques — Collecte de dons pour l'orphelinat Saint-Joseph. Offrons de la joie aux enfants.">
    <meta property="og:title" content="Sourires de Pâques — Donnez de la joie">
    <meta property="og:description" content="Ensemble, soutenons les enfants de l'orphelinat Saint-Joseph à l'occasion de Pâques.">
    <title><?= $titre ?? 'Sourires de Pâques' ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ===== BARRE DE NAVIGATION ===== -->
<nav class="navbar navbar-expand-lg sticky-top navbar-custom" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <span class="brand-cross">✝</span>
            <span class="brand-text">Sourires <span class="brand-accent">de Pâques</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php" data-i18n="nav_accueil">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=don" data-i18n="nav_donner">Faire un don</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact" data-i18n="nav_contact">Nous contacter</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                <button class="btn btn-nav-ctrl btn-sm px-3" id="btnLang" onclick="toggleLang()">
                    <i class="bi bi-translate me-1"></i>
                    <span id="langLabel">EN</span>
                </button>
                <button class="btn btn-nav-ctrl btn-sm px-2" id="btnTheme" onclick="toggleTheme()" title="Changer le thème">
                    <i class="bi bi-sun" id="themeIcon"></i>
                </button>
                <a href="index.php?page=don" class="btn btn-donate btn-sm" data-i18n="btn_donner">
                    <i class="bi bi-heart-fill me-1"></i>
                    <span>Donner maintenant</span>
                </a>
            </div>
        </div>
    </div>
</nav>
