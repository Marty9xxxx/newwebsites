<?php
// ====== INICIALIZACE ======
// Kontrola, zda je soubor načítán přímo nebo pomocí include
if (!defined('SECURITY')) {
    die('Přímý přístup není povolen');
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
            <a href="mailto:info@example.com">info@example.com</a>
        </p>
        
        <!-- Odkazy na sociální sítě -->
        <div class="social-links">
            <a href="#" title="Facebook" rel="noopener noreferrer">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="#" title="Twitter" rel="noopener noreferrer">
                <i class="fab fa-twitter"></i>
            </a>
        </div>
    </div>
</footer>

<!-- Zavření HTML dokumentu -->
</body>
</html>