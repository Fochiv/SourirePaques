-- ============================================================
-- Script SQL complet - Sourires de Pâques
-- Compatible MySQL / WampServer
-- ============================================================

CREATE DATABASE IF NOT EXISTS sourires_de_paques
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sourires_de_paques;

-- ============================================================
-- Table : admins
-- ============================================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Table : dons
-- ============================================================
CREATE TABLE IF NOT EXISTS dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('anonyme', 'identifie') DEFAULT 'anonyme',
    nom VARCHAR(100),
    prenom VARCHAR(100),
    pays VARCHAR(100),
    ville VARCHAR(100),
    telephone VARCHAR(30),
    email VARCHAR(150),
    montant DECIMAL(12, 2) NOT NULL,
    methode_paiement ENUM('api_mobile_money', 'virement_bancaire') DEFAULT 'api_mobile_money',
    operateur VARCHAR(100),
    code_pays_tel VARCHAR(10),
    reference_transaction VARCHAR(255),
    statut ENUM('en_attente', 'succes', 'echec') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Table : versets
-- ============================================================
CREATE TABLE IF NOT EXISTS versets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_fr VARCHAR(100) NOT NULL,
    texte_fr TEXT NOT NULL,
    reference_en VARCHAR(100) NOT NULL,
    texte_en TEXT NOT NULL,
    actif TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Table : paiements (log brut des réponses API)
-- ============================================================
CREATE TABLE IF NOT EXISTS paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT,
    transaction_id VARCHAR(255),
    montant DECIMAL(12, 2),
    montant_credite DECIMAL(12, 2),
    frais DECIMAL(12, 2),
    statut VARCHAR(50),
    payload_response TEXT COMMENT 'Réponse complète JSON de l API',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Table : parametres_site
-- ============================================================
CREATE TABLE IF NOT EXISTS parametres_site (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(100) NOT NULL UNIQUE,
    valeur TEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Données initiales
-- ============================================================

-- Admin par défaut : admin / Admin@2024
INSERT IGNORE INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Versets bibliques
INSERT IGNORE INTO versets (reference_fr, texte_fr, reference_en, texte_en, actif) VALUES
('Actes 20:35', 'Il y a plus de bonheur à donner qu''à recevoir.', 'Acts 20:35', 'It is more blessed to give than to receive.', 1),
('2 Corinthiens 9:7', 'Que chacun donne comme il l''a résolu en son cœur, sans tristesse ni contrainte, car Dieu aime celui qui donne avec joie.', '2 Corinthians 9:7', 'Each one must give as he has decided in his heart, not reluctantly or under compulsion, for God loves a cheerful giver.', 1),
('Proverbes 19:17', 'Celui qui donne au pauvre prête à l''Éternel, qui lui rendra ce qu''il a donné.', 'Proverbs 19:17', 'Whoever is generous to the poor lends to the Lord, and he will repay him for his deed.', 1),
('Luc 21:1-4', 'Cette veuve pauvre a mis plus que tous les autres. Car tous ceux-là ont pris sur leur superflu pour faire leurs offrandes, mais elle a pris sur son indigence et elle a mis tout ce qu''elle avait pour vivre.', 'Luke 21:1-4', 'This poor widow has put in more than all of them. For they all contributed out of their abundance, but she out of her poverty put in all she had to live on.', 1),
('Matthieu 25:40', 'En vérité je vous le dis, dans la mesure où vous l''avez fait à l''un de ces plus petits de mes frères, c''est à moi que vous l''avez fait.', 'Matthew 25:40', 'Truly, I say to you, as you did it to one of the least of these my brothers, you did it to me.', 1),
('Isaïe 58:7', 'N''est-ce pas partager ton pain avec celui qui a faim, et faire entrer dans ta maison les pauvres sans abri ? Quand tu vois un homme nu, le couvrir ?', 'Isaiah 58:7', 'Is it not to share your bread with the hungry and bring the homeless poor into your house, when you see the naked, to cover him?', 1),
('Deutéronome 15:10', 'Tu lui donneras généreusement et tu ne seras pas chagrin de cœur quand tu lui donneras, car à cause de cela l''Éternel, ton Dieu, te bénira dans tout ton travail et dans tout ce que tu entreprends.', 'Deuteronomy 15:10', 'You shall give to him freely, and your heart shall not be grudging when you give to him, because for this the Lord your God will bless you in all your work.', 1);

-- Paramètres du site
INSERT IGNORE INTO parametres_site (cle, valeur) VALUES
('objectif_financier', '500000'),
('message_accueil_fr', 'Ensemble, offrons un sourire à chaque enfant en ce temps de Pâques. Votre don, si petit soit-il, peut changer une vie.'),
('message_accueil_en', 'Together, let us bring a smile to every child this Easter. Your donation, however small, can change a life.'),
('presentation_orphelinat_fr', 'Notre orphelinat accueille plus de 80 enfants âgés de 2 à 16 ans, privés de famille et de ressources. Chaque année à Pâques, nous organisons une célébration pour leur rappeler qu''ils sont aimés et précieux.'),
('presentation_orphelinat_en', 'Our orphanage welcomes more than 80 children aged 2 to 16, deprived of family and resources. Every Easter, we organize a celebration to remind them that they are loved and precious.'),
('nom_orphelinat', 'Orphelinat Saint-Joseph'),
('devise', 'XAF');
