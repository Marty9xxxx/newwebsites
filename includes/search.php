<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== ZPRACOVÁNÍ VSTUPNÍCH DAT ======
// Získání klíčových slov z GET parametrů
// Použití operátoru ?? pro výchozí prázdnou hodnotu
$keywords = $_GET['keywords'] ?? '';

// ====== NAČTENÍ JSON DAT ======
// Načtení všech potřebných JSON souborů pro vyhledávání
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);

// ====== FUNKCE PRO VYHLEDÁVÁNÍ ======
/**
 * Vyhledá klíčová slova v poli dat
 * @param array $array Pole pro prohledání
 * @param string $keywords Hledaný výraz
 * @return array Nalezené výsledky
 */
function searchInArray($array, $keywords) {
    $results = [];
    // Procházení všech položek pole
    foreach ($array as $item) {
        // Převod položky na JSON pro fulltextové vyhledávání
        // stripos pro case-insensitive vyhledávání
        if (stripos(json_encode($item), $keywords) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// ====== VYHLEDÁVÁNÍ V DATECH ======
// Aplikace vyhledávání na jednotlivé datové zdroje
$userResults = searchInArray($users, $keywords);
$styleResults = searchInArray($styles, $keywords);
$newsResults = searchInArray($news, $keywords);

// ====== NAČTENÍ ŠABLONY ======
// Vložení hlavičky stránky
include(getFilePath('includes', 'header.php'));
?>

<!-- ====== HTML STRUKTURA ====== -->
<main>
    <section class="content">
        <!-- Zobrazení hledaného výrazu -->
        <h2>Výsledky vyhledávání pro "<?php echo htmlspecialchars($keywords); ?>"</h2>
        
        <!-- ====== SEKCE UŽIVATELŮ ====== -->
        <h3>Uživatelé</h3>
        <ul>
            <?php foreach ($userResults as $user): ?>
                <li><?php echo htmlspecialchars($user['username']); ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- ====== SEKCE STYLŮ ====== -->
        <h3>Styly</h3>
        <ul>
            <?php foreach ($styleResults as $style): ?>
                <li><?php echo htmlspecialchars($style['name']); ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- ====== SEKCE NOVINEK ====== -->
        <h3>Novinky</h3>
        <ul>
            <?php foreach ($newsResults as $newsItem): ?>
                <li><?php echo htmlspecialchars($newsItem['text']); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>

<!-- ====== PATIČKA ====== -->
<?php include(getFilePath('includes', 'footer.php')); ?>

<!-- ====== DOPORUČENÉ CSS STYLY ====== -->
<style>
    /* Styly pro výsledky vyhledávání */
    .content {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .content h2 {
        color: #333;
        margin-bottom: 20px;
    }

    .content h3 {
        color: #573232;
        margin: 15px 0;
    }

    .content ul {
        list-style: none;
        padding: 0;
    }

    .content li {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .content li:hover {
        background-color: #f5f5f5;
    }
</style>