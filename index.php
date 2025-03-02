<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro práci s přihlášením
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once 'config.php';

// ====== NAČTENÍ DAT ======
// Načtení uživatelů z JSON souboru
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
// Načtení stylů z JSON souboru
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// ====== NAČTENÍ OBSAHU ======
// Získání cesty k souboru s obsahem
$content_file = getFilePath('data', 'content.json');
// Načtení a dekódování JSON souboru s obsahem
$content = json_decode(file_get_contents($content_file), true);

// ====== ZPRACOVÁNÍ DAT ======
// Získání dat pro úvodní stránku, použití operátoru ?? pro výchozí hodnotu null
$homepage = $content['homepage'] ?? null;
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <!-- Dynamický titulek stránky s fallback hodnotou -->
    <title><?php echo htmlspecialchars($homepage['title'] ?? 'Úvodní stránka'); ?></title>
    
    <!-- ====== VLOŽENÍ HLAVIČKY ====== -->
    <!-- Načtení společné hlavičky s meta tagy a styly -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <!-- ====== VYHLEDÁVACÍ SEKCE ====== -->
    <section class="search">
        <!-- Formulář pro vyhledávání s přesměrováním na search.php -->
        <form action="<?php echo getWebPath('includes/search.php'); ?>" method="get">
            <input type="text" 
                   name="keywords" 
                   placeholder="Napište text" 
                   required>
            <button type="submit">Hledat</button>
        </form>
    </section>

    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <section class="content">
            <?php if ($homepage): ?>
                <!-- Nadpis stránky -->
                <h1><?php echo htmlspecialchars($homepage['title']); ?></h1>
                
                <!-- Hlavní obsah stránky -->
                <div class="homepage-content">
                    <!-- Použití nl2br pro zachování formátování textu -->
                    <?php echo nl2br(htmlspecialchars($homepage['content'])); ?>
                </div>
                
                <!-- Informace o poslední úpravě -->
                <div class="last-edit">
                    <small>
                        Naposledy upraveno: 
                        <!-- Formátování data do českého formátu -->
                        <?php echo date('d.m.Y H:i', strtotime($homepage['last_edited'])); ?>
                        (<?php echo htmlspecialchars($homepage['edited_by']); ?>)
                    </small>
                </div>
            <?php else: ?>
                <!-- Zobrazení chybové hlášky při nedostupnosti obsahu -->
                <p>Obsah stránky není dostupný.</p>
            <?php endif; ?>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <!-- Načtení společné patičky webu -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
