<?php
/**
 * Initialisation de la base SQLite (Replit uniquement)
 * Lance ce script une seule fois pour créer les tables
 */

require_once __DIR__ . '/database.php';

$pdo = Database::getInstance()->getPDO();

// ============================================================
// Création des tables SQLite
// ============================================================

$pdo->exec("CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT (datetime('now'))
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS dons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT DEFAULT 'anonyme',
    nom TEXT,
    prenom TEXT,
    pays TEXT,
    ville TEXT,
    telephone TEXT,
    email TEXT,
    montant REAL NOT NULL,
    methode_paiement TEXT DEFAULT 'api_mobile_money',
    operateur TEXT,
    code_pays_tel TEXT,
    reference_transaction TEXT,
    statut TEXT DEFAULT 'en_attente',
    created_at DATETIME DEFAULT (datetime('now'))
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS versets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference_fr TEXT NOT NULL,
    texte_fr TEXT NOT NULL,
    reference_en TEXT NOT NULL,
    texte_en TEXT NOT NULL,
    actif INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT (datetime('now'))
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS paiements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    don_id INTEGER,
    transaction_id TEXT,
    montant REAL,
    montant_credite REAL,
    frais REAL,
    statut TEXT,
    payload_response TEXT,
    created_at DATETIME DEFAULT (datetime('now'))
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS parametres_site (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cle TEXT NOT NULL UNIQUE,
    valeur TEXT NOT NULL,
    updated_at DATETIME DEFAULT (datetime('now'))
)");

// ============================================================
// Données initiales
// ============================================================

// Admin par défaut : admin / Admin@2024
$stmt = $pdo->prepare("INSERT OR IGNORE INTO admins (username, password) VALUES (?, ?)");
$stmt->execute(['admin', password_hash('Admin@2024', PASSWORD_BCRYPT)]);

// Versets bibliques
$versets = [
    ['Actes 20:35', 'Il y a plus de bonheur à donner qu\'à recevoir.', 'Acts 20:35', 'It is more blessed to give than to receive.'],
    ['2 Corinthiens 9:7', 'Que chacun donne comme il l\'a résolu en son cœur, sans tristesse ni contrainte, car Dieu aime celui qui donne avec joie.', '2 Corinthians 9:7', 'Each one must give as he has decided in his heart, not reluctantly or under compulsion, for God loves a cheerful giver.'],
    ['Proverbes 19:17', 'Celui qui donne au pauvre prête à l\'Éternel, qui lui rendra ce qu\'il a donné.', 'Proverbs 19:17', 'Whoever is generous to the poor lends to the Lord, and he will repay him for his deed.'],
    ['Luc 21:1-4', 'Cette veuve pauvre a mis plus que tous les autres. Car tous ceux-là ont pris sur leur superflu pour faire leurs offrandes, mais elle a pris sur son indigence.', 'Luke 21:1-4', 'This poor widow has put in more than all of them. For they all contributed out of their abundance, but she out of her poverty put in all she had to live on.'],
    ['Matthieu 25:40', 'En vérité je vous le dis, dans la mesure où vous l\'avez fait à l\'un de ces plus petits de mes frères, c\'est à moi que vous l\'avez fait.', 'Matthew 25:40', 'Truly, I say to you, as you did it to one of the least of these my brothers, you did it to me.'],
    ['Isaïe 58:7', 'N\'est-ce pas partager ton pain avec celui qui a faim, et faire entrer dans ta maison les pauvres sans abri ?', 'Isaiah 58:7', 'Is it not to share your bread with the hungry and bring the homeless poor into your house, when you see the naked, to cover him?'],
    ['Deutéronome 15:10', 'Tu lui donneras généreusement et tu ne seras pas chagrin de cœur quand tu lui donneras, car à cause de cela l\'Éternel, ton Dieu, te bénira.', 'Deuteronomy 15:10', 'You shall give to him freely, and your heart shall not be grudging when you give to him, because for this the Lord your God will bless you in all your work.'],
];

$stmt = $pdo->prepare("INSERT OR IGNORE INTO versets (reference_fr, texte_fr, reference_en, texte_en, actif) VALUES (?,?,?,?,1)");
foreach ($versets as $v) {
    $stmt->execute($v);
}

// Paramètres
$params = [
    ['objectif_financier', '500000'],
    ['message_accueil_fr', 'Ensemble, offrons un sourire à chaque enfant en ce temps de Pâques. Votre don, si petit soit-il, peut changer une vie.'],
    ['message_accueil_en', 'Together, let us bring a smile to every child this Easter. Your donation, however small, can change a life.'],
    ['presentation_orphelinat_fr', 'Notre orphelinat accueille plus de 80 enfants âgés de 2 à 16 ans, privés de famille et de ressources. Chaque année à Pâques, nous organisons une célébration pour leur rappeler qu\'ils sont aimés et précieux.'],
    ['presentation_orphelinat_en', 'Our orphanage welcomes more than 80 children aged 2 to 16, deprived of family and resources. Every Easter, we organize a celebration to remind them that they are loved and precious.'],
    ['nom_orphelinat', 'Orphelinat Saint-Joseph'],
    ['devise', 'XAF'],
];

$stmt = $pdo->prepare("INSERT OR IGNORE INTO parametres_site (cle, valeur) VALUES (?,?)");
foreach ($params as $p) {
    $stmt->execute($p);
}

echo "Base de données initialisée avec succès.\n";
