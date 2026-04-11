# Sourires de Pâques

Application web de collecte de dons pour l'orphelinat Saint-Joseph à l'occasion de Pâques.

## Architecture

- **Backend** : PHP 8.2 natif, architecture MVC simplifiée
- **Base de données** : SQLite (Replit) / MySQL (WampServer) via PDO
- **Frontend** : HTML5, CSS3, JavaScript natif + Bootstrap 5
- **Paiements** : API Ashtech Pay (Mobile Money — 16 pays africains)

## Structure

```
/
├── index.php                  # Routeur principal
├── config/
│   ├── database.php           # Config PDO (MySQL ou SQLite)
│   └── init_sqlite.php        # Initialisation SQLite
├── models/
│   ├── Don.php                # Gestion des dons
│   ├── Admin.php              # Authentification admin
│   ├── Verset.php             # Versets bibliques
│   └── Parametre.php          # Paramètres du site
├── controllers/
│   ├── HomeController.php     # Page d'accueil
│   ├── DonController.php      # Formulaire + API paiement
│   └── AdminController.php    # Dashboard admin
├── views/
│   ├── layout/header.php      # En-tête commun
│   ├── layout/footer.php      # Pied de page commun
│   ├── home.php               # Page d'accueil
│   ├── don.php                # Formulaire de don
│   ├── merci.php              # Page de remerciement
│   ├── wave.php               # Redirection Wave
│   └── admin/                 # Vues admin
├── assets/
│   ├── css/style.css          # CSS principal (dark theme)
│   └── js/main.js             # JS (thème, langue, formulaire)
├── database.sql               # Script MySQL pour WampServer
└── database.sqlite            # BDD SQLite (auto-générée)
```

## Fonctionnalités

### Public
- Page d'accueil avec hero sombre, versets bibliques aléatoires
- Barre de progression dynamique vers l'objectif
- Formulaire de don anonyme ou identifié
- Paiement Mobile Money via API Ashtech Pay (MTN, Orange, Wave, Moov, Airtel...)
- Gestion des flux OTP (SMS/USSD) et Wave
- Page de remerciement avec verset biblique de bénédiction
- Bilingue FR/EN avec switch instantané
- Mode clair/sombre avec localStorage
- Responsive (mobile, tablette, desktop)

### Admin (`/index.php?page=admin_login`)
- Identifiants par défaut : `admin / Admin@2024`
- Dashboard avec KPIs (montant total, aujourd'hui, semaine, mois)
- Graphiques JS natifs (évolution par jour et semaine)
- Tableau des dons avec recherche et export CSV
- Gestion des versets bibliques (CRUD + activer/désactiver)
- Paramètres du site (objectif, messages, devise)
- Changement de mot de passe sécurisé

## API de paiement

- **Provider** : Ashtech Pay (`https://ashtechpay.top`)
- **Endpoint collect** : `POST /v1/collect`
- **Clé API** : dans `controllers/DonController.php`
- **Webhook** : `GET /index.php?page=webhook`

## Démarrage

```bash
php -S 0.0.0.0:5000 -t .
```

## Migration vers WampServer

1. Importer `database.sql` dans MySQL
2. Modifier `config/database.php` : changer `DB_TYPE` en `'mysql'`
3. Renseigner les identifiants MySQL si nécessaire
