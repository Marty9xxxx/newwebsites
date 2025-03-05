<?php
// ====== NAČTENÍ KONFIGURACE A EDITORU ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';
// Načtení Simple Editoru pro parsování BB kódů
require_once dirname(__DIR__) . '/includes/simple_editor.php';

// ====== NAČTENÍ DAT ======
// Načtení novinek z JSON souboru a převod na PHP pole
// Použití getFilePath pro bezpečnou práci s cestami k souborům
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <title>Novinky</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ====== NAČTENÍ HLAVIČKY ====== -->
    <?php include(getFilePath('includes', 'header.php')); ?>
    
    <!-- ====== VLASTNÍ STYLY ====== -->
    <style>
        /* Styly pro seznam novinek */
        .news-list {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .news-item {
            margin-bottom: 20px;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .news-header {
            margin-bottom: 10px;
            color: #666;
            font-size: 0.9em;
        }
        
        .news-date {
            margin-right: 15px;
        }
        
        .news-author {
            font-weight: bold;
        }
        
        .news-text {
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <section class="content">
            <!-- Nadpis sekce -->
            <h2>Novinky</h2>
            
            <!-- Seznam novinek -->
            <div class="news-list">
                <?php if (!empty($news)): ?>
                    <!-- Iterace přes všechny novinky -->
                    <?php foreach ($news as $item): ?>
                        <!-- Jednotlivá novinka -->
                        <div class="news-item">
                            <!-- Hlavička novinky s metadaty -->
                            <div class="news-header">
                                <!-- Datum a čas publikace -->
                                <span class="news-date">
                                    <?php echo htmlspecialchars($item['datetime']); ?>
                                </span>
                                <!-- Autor novinky -->
                                <span class="news-author">
                                    <?php echo htmlspecialchars($item['author']); ?>
                                </span>
                            </div>
                            <!-- Text novinky -->
                            <div class="news-text">
                                <?php 
                                // Použití parseru pro převod BB kódů na HTML
                                echo SimpleEditor::parseContent($item['text']); 
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Zpráva při neexistenci novinek -->
                    <p>Žádné novinky k zobrazení.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- ====== PATIČKA ====== -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>