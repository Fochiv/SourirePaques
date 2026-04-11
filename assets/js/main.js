/**
 * Sourires de Pâques — JavaScript principal
 * Gère : thème, langue, formulaire don, graphiques
 */

'use strict';

// ============================================================
// TRADUCTIONS (Bilingue FR / EN)
// ============================================================
const translations = {
    fr: {
        // Navigation
        nav_accueil: 'Accueil',
        nav_donner: 'Faire un don',
        btn_donner: 'Donner maintenant',

        // Hero
        hero_titre: 'Sourires de Pâques',
        hero_sous_titre: 'Chaque don, une lumière d\'espoir dans une vie d\'enfant.',
        btn_donner_maintenant: 'Donner maintenant',
        btn_progression: 'Voir la progression',

        // Stats
        stat_collecte: 'Collecté',
        stat_objectif: 'Objectif',
        stat_donateurs: 'Donateurs',
        stat_atteint: 'Atteint',
        progression_label: 'Progression vers l\'objectif',
        progression_aide: 'Aidez-nous à atteindre notre objectif. Chaque contribution compte !',

        // Orphelinat
        badge_orphelinat: 'Notre orphelinat',
        btn_soutenir: 'Soutenir l\'orphelinat',

        // Spirituel
        badge_parole: 'Paroles Inspirantes',
        titre_spirituel: 'La Générosité dans la Bible',

        // CTA
        cta_titre: 'Votre générosité peut tout changer',
        cta_desc: 'Un simple geste de votre part peut offrir un repas, un vêtement, un sourire — et surtout de l\'amour — à un enfant qui en a désespérément besoin.',

        // Contact
        badge_contact: 'Nous Contacter',
        contact_titre: 'Un problème ou une question ?',
        contact_desc: 'Contactez-nous via Telegram si vous avez un problème de paiement ou souhaitez notifier votre don.',
        btn_telegram: 'Telegram : +1 902 812 0154',
        nav_contact: 'Nous contacter',
        contact_don_titre: 'Besoin d\'aide pour votre don ?',
        contact_don_desc: 'En cas de problème de paiement, contactez-nous sur Telegram.',
        contact_merci_titre: 'Besoin d\'aide ou problème de paiement ?',
        contact_merci_desc: 'Contactez l\'administrateur via Telegram pour toute question ou pour notifier votre paiement.',

        // Footer
        footer_description: 'Une initiative humanitaire pour offrir de la joie et de l\'espoir aux enfants orphelins à l\'occasion de Pâques.',
        footer_liens: 'Liens rapides',
        footer_parole: 'Parole de Vie',
        footer_verset_texte: '"Dieu aime celui qui donne avec joie."',
        footer_verset_ref: '2 Corinthiens 9:7',
        footer_copyright: '© 2025 Sourires de Pâques. Tous droits réservés.',
        footer_securise: 'Paiements sécurisés',

        // Formulaire don
        badge_don: 'Faire un don',
        don_titre: 'Soutenez l\'Orphelinat',
        don_sous_titre: 'Votre contribution est sécurisée et transparente.',
        etape1_titre: 'Votre identité',
        don_anonyme: 'Don Anonyme',
        don_identifie: 'Don Identifié',
        label_nom: 'Nom',
        label_prenom: 'Prénom',
        label_pays: 'Pays',
        label_ville: 'Ville',
        label_email: 'Email (optionnel)',
        etape2_titre: 'Montant du don',
        etape3_titre: 'Méthode de paiement',
        methode_mobile: 'Mobile Money',
        methode_virement: 'Carte Bancaire',
        label_pays_paiement: 'Pays',
        label_operateur: 'Opérateur',
        option_pays: '— Sélectionner votre pays —',
        option_operateur: '— Sélectionner d\'abord le pays —',
        label_telephone: 'Numéro de téléphone',
        virement_info_titre: 'Informations de virement',
        virement_info_desc: 'Effectuez votre paiement vers la carte suivante :',
        virement_note: 'Notez la référence de votre virement pour suivi.',
        resume_titre: 'Résumé de votre don',
        resume_type: 'Type :',
        resume_montant: 'Montant :',
        resume_methode: 'Méthode :',
        btn_payer: 'Confirmer et Payer',
        securise_label: 'Paiement sécurisé',

        // OTP
        otp_titre: 'Code OTP requis',

        // Merci
        merci_titre: 'Merci pour votre générosité !',
        merci_message: 'Votre contribution apportera de la joie à des enfants à l\'occasion de Pâques. Que Dieu vous bénisse pour cet acte d\'amour.',
        merci_details: 'Détails de votre don',
        merci_date: 'Date :',
        merci_montant_don: 'Montant :',
        merci_methode_don: 'Méthode :',
        merci_statut_don: 'Statut :',
        merci_ref: 'Référence transaction :',
        statut_attente: 'En attente de confirmation',
        merci_attente_ussd: 'Une demande de confirmation a été envoyée sur votre téléphone. Validez avec votre code PIN pour finaliser le paiement.',
        btn_retour_accueil: 'Retour à l\'accueil',
        btn_nouveau_don: 'Faire un autre don',
        merci_partage: 'Partagez cette cause avec vos proches pour amplifier l\'impact de votre geste.',

        // Wave
        wave_titre: 'Finaliser avec Wave',
        btn_wave: 'Ouvrir Wave pour payer',
        wave_apres: 'Après confirmation dans Wave, votre don sera automatiquement enregistré.',
        wave_deja_paye: 'J\'ai déjà payé',
    },

    en: {
        nav_accueil: 'Home',
        nav_donner: 'Donate',
        btn_donner: 'Donate Now',
        hero_titre: 'Easter Smiles',
        hero_sous_titre: 'Every gift, a ray of hope in a child\'s life.',
        btn_donner_maintenant: 'Donate Now',
        btn_progression: 'View Progress',
        stat_collecte: 'Collected',
        stat_objectif: 'Goal',
        stat_donateurs: 'Donors',
        stat_atteint: 'Reached',
        progression_label: 'Progress toward goal',
        progression_aide: 'Help us reach our goal. Every contribution counts!',
        badge_orphelinat: 'Our orphanage',
        btn_soutenir: 'Support the orphanage',
        badge_parole: 'Inspiring Words',
        titre_spirituel: 'Generosity in the Bible',
        cta_titre: 'Your generosity can change everything',
        cta_desc: 'A simple gesture from you can provide a meal, clothing, a smile — and above all love — to a child who desperately needs it.',
        badge_contact: 'Contact Us',
        contact_titre: 'A problem or a question?',
        contact_desc: 'Contact us via Telegram if you have a payment problem or want to notify your donation.',
        btn_telegram: 'Telegram: +1 902 812 0154',
        nav_contact: 'Contact us',
        contact_don_titre: 'Need help with your donation?',
        contact_don_desc: 'In case of a payment problem, contact us on Telegram.',
        contact_merci_titre: 'Need help or a payment problem?',
        contact_merci_desc: 'Contact the administrator via Telegram for any question or to notify your payment.',
        footer_description: 'A humanitarian initiative to bring joy and hope to orphaned children at Easter.',
        footer_liens: 'Quick Links',
        footer_parole: 'Word of Life',
        footer_verset_texte: '"God loves a cheerful giver."',
        footer_verset_ref: '2 Corinthians 9:7',
        footer_copyright: '© 2025 Easter Smiles. All rights reserved.',
        footer_securise: 'Secure payments',
        badge_don: 'Make a donation',
        don_titre: 'Support the Orphanage',
        don_sous_titre: 'Your contribution is secure and transparent.',
        etape1_titre: 'Your identity',
        don_anonyme: 'Anonymous Donation',
        don_identifie: 'Identified Donation',
        label_nom: 'Last Name',
        label_prenom: 'First Name',
        label_pays: 'Country',
        label_ville: 'City',
        label_email: 'Email (optional)',
        etape2_titre: 'Donation Amount',
        etape3_titre: 'Payment Method',
        methode_mobile: 'Mobile Money',
        methode_virement: 'Bank Card',
        label_pays_paiement: 'Country',
        label_operateur: 'Operator',
        option_pays: '— Select your country —',
        option_operateur: '— Select a country first —',
        label_telephone: 'Phone Number',
        virement_info_titre: 'Transfer Information',
        virement_info_desc: 'Make your payment to the following card:',
        virement_note: 'Note your transfer reference for tracking.',
        resume_titre: 'Donation Summary',
        resume_type: 'Type:',
        resume_montant: 'Amount:',
        resume_methode: 'Method:',
        btn_payer: 'Confirm & Pay',
        securise_label: 'Secure payment',
        otp_titre: 'OTP Code Required',
        merci_titre: 'Thank you for your generosity!',
        merci_message: 'Your contribution will bring joy to children this Easter. May God bless you for this act of love.',
        merci_details: 'Donation Details',
        merci_date: 'Date:',
        merci_montant_don: 'Amount:',
        merci_methode_don: 'Method:',
        merci_statut_don: 'Status:',
        merci_ref: 'Transaction Reference:',
        statut_attente: 'Awaiting confirmation',
        merci_attente_ussd: 'A confirmation request has been sent to your phone. Validate with your PIN to complete the payment.',
        btn_retour_accueil: 'Back to Home',
        btn_nouveau_don: 'Make another donation',
        merci_partage: 'Share this cause with your loved ones to amplify the impact of your gesture.',
        wave_titre: 'Complete with Wave',
        btn_wave: 'Open Wave to Pay',
        wave_apres: 'After confirming in Wave, your donation will be automatically recorded.',
        wave_deja_paye: 'I already paid',
    }
};

// ============================================================
// GESTION DU THÈME (Dark / Light)
// ============================================================

function initTheme() {
    const saved = localStorage.getItem('theme') || 'dark';
    applyTheme(saved);
}

function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-fill';
    }
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    applyTheme(current === 'dark' ? 'light' : 'dark');
}

// ============================================================
// GESTION DE LA LANGUE (FR / EN)
// ============================================================

function initLang() {
    const saved = localStorage.getItem('lang') || 'fr';
    applyLang(saved, false);
}

function applyLang(lang, save = true) {
    if (save) localStorage.setItem('lang', lang);

    // Mettre à jour le bouton
    const label = document.getElementById('langLabel');
    if (label) label.textContent = lang === 'fr' ? 'EN' : 'FR';

    const t = translations[lang] || translations.fr;

    // Éléments avec data-i18n
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (t[key] !== undefined) {
            el.innerHTML = t[key];
        }
    });

    // Éléments avec data-lang-fr / data-lang-en
    document.querySelectorAll('[data-lang-fr]').forEach(el => {
        const text = el.getAttribute('data-lang-' + lang);
        if (text) el.textContent = '"' + text + '"';
    });
    document.querySelectorAll('cite[data-lang-fr]').forEach(el => {
        const text = el.getAttribute('data-lang-' + lang);
        if (text) el.textContent = '— ' + text;
    });
    document.querySelectorAll('small[data-lang-fr]').forEach(el => {
        const text = el.getAttribute('data-lang-' + lang);
        if (text) el.textContent = '— ' + text;
    });
}

function toggleLang() {
    const current = localStorage.getItem('lang') || 'fr';
    applyLang(current === 'fr' ? 'en' : 'fr');
}

// ============================================================
// FORMULAIRE DE DON
// ============================================================

/**
 * Bascule entre don anonyme et identifié
 */
function toggleType(type) {
    const champsIdentite = document.getElementById('champsIdentite');
    const labelAnonyme   = document.getElementById('labelAnonyme');
    const labelIdentifie = document.getElementById('labelIdentifie');
    const resumeType     = document.getElementById('resumeType');
    const t = translations[localStorage.getItem('lang') || 'fr'];

    if (type === 'identifie') {
        champsIdentite?.classList.remove('d-none');
        labelAnonyme?.classList.remove('active');
        labelIdentifie?.classList.add('active');
        if (resumeType) resumeType.textContent = t.don_identifie || 'Don Identifié';
    } else {
        champsIdentite?.classList.add('d-none');
        labelAnonyme?.classList.add('active');
        labelIdentifie?.classList.remove('active');
        if (resumeType) resumeType.textContent = t.don_anonyme || 'Don Anonyme';
    }
}

/**
 * Bascule entre Mobile Money et Virement
 */
function toggleMethode(methode) {
    const champsMobile  = document.getElementById('champsMobileMoney');
    const champsVirement= document.getElementById('champsVirement');
    const labelMobile   = document.getElementById('labelMobileMoney');
    const labelVirement = document.getElementById('labelVirement');
    const resumeMethode = document.getElementById('resumeMethode');
    const t = translations[localStorage.getItem('lang') || 'fr'];

    if (methode === 'api_mobile_money') {
        champsMobile?.classList.remove('d-none');
        champsVirement?.classList.add('d-none');
        labelMobile?.classList.add('active');
        labelVirement?.classList.remove('active');
        if (resumeMethode) resumeMethode.textContent = t.methode_mobile || 'Mobile Money';
    } else {
        champsMobile?.classList.add('d-none');
        champsVirement?.classList.remove('d-none');
        labelMobile?.classList.remove('active');
        labelVirement?.classList.add('active');
        if (resumeMethode) resumeMethode.textContent = t.methode_virement || 'Carte Bancaire';
    }
}

/**
 * Définit le montant via les boutons rapides
 */
function setMontant(montant) {
    const input = document.getElementById('montantInput');
    if (input) {
        input.value = montant;
        updateResumeMontant(montant);
    }
    // Highlight bouton actif
    document.querySelectorAll('.btn-amount').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
}

/**
 * Met à jour le résumé du montant
 */
function updateResumeMontant(val) {
    const resumeMontant = document.getElementById('resumeMontant');
    if (resumeMontant && val > 0) {
        resumeMontant.textContent = parseInt(val).toLocaleString('fr-FR') + ' XAF';
    }
}

/**
 * Charge les opérateurs selon le pays sélectionné
 */
function chargerOperateurs(codePays) {
    const select = document.getElementById('selectPays');
    const operateurSelect = document.getElementById('selectOperateur');
    if (!select || !operateurSelect) return;

    const option = select.querySelector(`option[value="${codePays}"]`);
    if (!option) return;

    const lang = localStorage.getItem('lang') || 'fr';
    const operators = JSON.parse(option.getAttribute('data-operators') || '[]');
    const currency  = option.getAttribute('data-currency') || 'XAF';

    // Mettre à jour la devise affichée
    const montantInput = document.querySelector('[name="montant"]');
    const inputGroup   = montantInput?.nextElementSibling;
    if (inputGroup) inputGroup.textContent = currency;

    // Peupler les opérateurs
    operateurSelect.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = lang === 'en' ? '— Select operator —' : '— Sélectionner l\'opérateur —';
    operateurSelect.appendChild(placeholder);

    operators.forEach(op => {
        const opt = document.createElement('option');
        opt.value = op;
        opt.textContent = op;
        operateurSelect.appendChild(opt);
    });
}

// ============================================================
// INITIALISATION
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    // Thème et langue
    initTheme();
    initLang();

    // Mise à jour résumé en temps réel
    const montantInput = document.getElementById('montantInput');
    if (montantInput) {
        montantInput.addEventListener('input', () => {
            updateResumeMontant(montantInput.value);
        });
    }

    // Validation formulaire Bootstrap
    const form = document.getElementById('formDon');
    if (form) {
        form.addEventListener('submit', (e) => {
            const montant = parseFloat(document.getElementById('montantInput')?.value || '0');
            if (montant <= 0) {
                e.preventDefault();
                const input = document.getElementById('montantInput');
                input?.setCustomValidity('Le montant doit être supérieur à 0.');
                input?.reportValidity();
                return;
            }

            // Animation bouton de soumission
            const btn = document.getElementById('btnSubmit');
            if (btn) {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement en cours...';
                btn.disabled = true;
            }
        });
    }

    // Animation de la barre de progression au scroll
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const targetWidth = progressBar.style.width;
        progressBar.style.width = '0%';
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        progressBar.style.transition = 'width 1.5s ease-in-out';
                        progressBar.style.width = targetWidth;
                    }, 200);
                    observer.unobserve(entry.target);
                }
            });
        });
        observer.observe(progressBar);
    }

    // Changement de navbar au scroll
    const navbar = document.getElementById('mainNav');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.4)';
            } else {
                navbar.style.boxShadow = 'none';
            }
        });
    }
});

// ============================================================
// COPIER LE NUMÉRO DE CARTE BANCAIRE
// ============================================================
function copierNumeroCarte() {
    const numeroCarte = document.getElementById('numeroCarteTexte')?.innerText?.trim() || '5430 0502 3923 6064';
    const btn         = document.getElementById('btnCopierCarte');
    const icone       = document.getElementById('iconeCopie');
    const texte       = document.getElementById('texteCopie');

    navigator.clipboard.writeText(numeroCarte).then(() => {
        // Feedback visuel : bouton vert + icône check
        btn?.classList.add('copied');
        if (icone) icone.className = 'bi bi-check-lg';
        if (texte) texte.textContent = 'Copié !';

        // Remettre l'état initial après 2,5 secondes
        setTimeout(() => {
            btn?.classList.remove('copied');
            if (icone) icone.className = 'bi bi-copy';
            if (texte) texte.textContent = 'Copier';
        }, 2500);
    }).catch(() => {
        // Fallback si le clipboard API n'est pas disponible
        const temp = document.createElement('textarea');
        temp.value = numeroCarte;
        temp.style.position = 'fixed';
        temp.style.opacity = '0';
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);

        btn?.classList.add('copied');
        if (icone) icone.className = 'bi bi-check-lg';
        if (texte) texte.textContent = 'Copié !';
        setTimeout(() => {
            btn?.classList.remove('copied');
            if (icone) icone.className = 'bi bi-copy';
            if (texte) texte.textContent = 'Copier';
        }, 2500);
    });
}
