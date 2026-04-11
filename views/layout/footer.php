<!-- ===== SECTION CONTACT ===== -->
<section id="contact" class="contact-section py-5">
    <div class="container">
        <div class="contact-box p-4 p-md-5 rounded-4 text-center">
            <div class="section-badge mb-3 mx-auto">
                <i class="bi bi-chat-dots me-2"></i>
                <span data-i18n="badge_contact">Nous Contacter</span>
            </div>
            <h3 class="fw-bold mb-3" data-i18n="contact_titre">Un problème ou une question ?</h3>
            <p class="text-body-readable mb-4" data-i18n="contact_desc">
                Contactez-nous via Telegram si vous avez un problème de paiement ou souhaitez notifier votre don.
            </p>
            <a href="https://t.me/donorphelinat" target="_blank" rel="noopener" class="btn btn-donate btn-lg px-5">
                <i class="bi bi-telegram me-2"></i>
                <span data-i18n="btn_telegram">Telegram : +1 902 812 0154</span>
            </a>
            <p class="text-body-readable small mt-3 mb-0">
                <i class="bi bi-link-45deg me-1"></i>
                <a href="https://t.me/donorphelinat" target="_blank" class="text-warning text-decoration-none">t.me/donorphelinat</a>
            </p>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer mt-auto py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-cross">✝</span>
                    <h5 class="mb-0 brand-text">Sourires <span class="brand-accent">de Pâques</span></h5>
                </div>
                <p class="footer-text small" data-i18n="footer_description">
                    Une initiative humanitaire pour offrir de la joie et de l'espoir aux enfants orphelins à l'occasion de Pâques.
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-3" data-i18n="footer_liens">Liens rapides</h6>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="footer-link" data-i18n="nav_accueil">Accueil</a></li>
                    <li><a href="index.php?page=don" class="footer-link" data-i18n="nav_donner">Faire un don</a></li>
                    <li><a href="#contact" class="footer-link" data-i18n="nav_contact">Nous contacter</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-3" data-i18n="footer_parole">Parole de Vie</h6>
                <blockquote class="footer-verset">
                    <em data-i18n="footer_verset_texte">"Dieu aime celui qui donne avec joie."</em>
                    <footer class="blockquote-footer mt-1" data-i18n="footer_verset_ref">2 Corinthiens 9:7</footer>
                </blockquote>
            </div>
        </div>
        <hr class="footer-divider mt-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="footer-text" data-i18n="footer_copyright">
                    &copy; <?= date('Y') ?> Sourires de Pâques. Tous droits réservés.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <small class="footer-text">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    <span data-i18n="footer_securise">Paiements sécurisés</span>
                </small>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
