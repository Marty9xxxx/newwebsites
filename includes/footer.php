<?php
// Kontrola bezpečnostní konstanty
if (!defined('SECURITY')) {
    // Místo ukončení skriptu přesměrujeme na hlavní stránku
    header('Location: ' . getWebPath('index.php'));
    exit;
}
?>

<!-- ====== PATIČKA WEBU ====== -->
<footer>
    <!-- Copyright informace s dynamickým rokem -->
    <p>&copy; <?php echo date("Y"); ?> 
        <!-- Odkaz na autora webu -->
        <a href="https://svatousek.cz" 
           target="_blank" 
           rel="noopener noreferrer">Marty9xxxx</a>
    </p>

    <!-- Další informace v patičce -->
    <div class="footer-info">
        <!-- Kontaktní údaje -->
        <p>Kontakt: 
            <a href="mailto:webazantmarty@gmail.com">webazantmarty@gmail.com</a>
        </p>
        
        <!-- Odkazy na sociální sítě -->
        <div class="social-links">
            <a href="https://facebook.com/martin.svaty/" title="Facebook" rel="noopener noreferrer">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="https://x.com/marty9xxxx/" title="Twitter" rel="noopener noreferrer">
                <i class="fab fa-x-twitter"></i>
            </a>
        </div>
    </div>
</footer>

<!-- Zavření HTML dokumentu -->
</body>
</html>