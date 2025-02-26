<?php
session_start();
// Load the configuration   
require_once dirname(__DIR__) . '/config.php';

// Získání klíčových slov z GET parametrů
$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';

// Načtení dat z JSON souborů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);

// Funkce pro vyhledávání v JSON datech
function searchInArray($array, $keywords) {
    $results = [];
    foreach ($array as $item) {
        if (stripos(json_encode($item), $keywords) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Vyhledávání v jednotlivých souborech
$userResults = searchInArray($users, $keywords);
$styleResults = searchInArray($styles, $keywords);
$newsResults = searchInArray($news, $keywords);

// Vložení hlavičky
include(getFilePath('includes', 'header.php'));
?>

<main>
    <section class="content">
        <h2>Výsledky vyhledávání pro "<?php echo htmlspecialchars($keywords); ?>"</h2>
        
        <h3>Uživatelé</h3>
        <ul>
            <?php foreach ($userResults as $user): ?>
                <li><?php echo htmlspecialchars($user['username']); ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Styly</h3>
        <ul>
            <?php foreach ($styleResults as $style): ?>
                <li><?php echo htmlspecialchars($style['name']); ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Novinky</h3>
        <ul>
            <?php foreach ($newsResults as $newsItem): ?>
                <li><?php echo htmlspecialchars($newsItem['text']); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>

<?php include(getFilePath('includes', 'footer.php')); ?>