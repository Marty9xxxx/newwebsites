<?php
// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <title>Portfolio</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio webových projektů">
    
    <!-- ====== NAČTENÍ STYLŮ ====== -->
    <!-- Hlavní CSS styl webu -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <!-- CSS pro tisk -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- ====== VLOŽENÍ HLAVIČKY ====== -->
    <?php include(getFilePath('includes', 'header.php')); ?>
    
    <!-- ====== VLASTNÍ STYLY PRO PORTFOLIO ====== -->
    <style>
        .portfolio-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .portfolio-item {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }
        
        .portfolio-item h3 {
            color: #573232;
            margin-bottom: 10px;
        }
        
        .portfolio-item p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .portfolio-item iframe {
            max-width: 100%;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .portfolio-item a {
            display: inline-block;
            padding: 8px 15px;
            background: #573232;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .portfolio-item a:hover {
            background: #6f4040;
        }
    </style>
</head>
<body>
    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <section class="content">
            <h2>Moje Portfolio</h2>
            
            <!-- Kontejner pro portfolio projekty -->
            <div class="portfolio-container">
                <!-- ====== PROJEKT 1: OSOBNÍ STRÁNKY ====== -->
                <div class="portfolio-item">
                    <h3>Osobní stránky</h3>
                    <p>Osobní stránky autora projektu a webblog.</p>
                    <!-- Náhled webu v iframe -->
                    <iframe src="https://www.svatousek.cz" 
                            width="300" 
                            height="200" 
                            frameborder="0" 
                            allowfullscreen
                            loading="lazy"
                            title="Náhled osobních stránek"></iframe>
                    <!-- Odkaz na plnou verzi -->
                    <a href="https://www.svatousek.cz" 
                       target="_blank" 
                       rel="noopener noreferrer">Zobrazit plnou verzi</a>
                </div>

                <!-- ====== PROJEKT 2: SUNLIGHT PRŮVODCE ====== -->
                <div class="portfolio-item">
                    <h3>Online Sunlight průvodce</h3>
                    <p>Zpracovávaná nápověda k systému Sunlight CMS, vše dle času a chuti.</p>
                    <iframe src="https://it.svatousek.cz" 
                            width="300" 
                            height="200" 
                            frameborder="0" 
                            allowfullscreen
                            loading="lazy"
                            title="Náhled Sunlight průvodce"></iframe>
                    <a href="https://it.svatousek.cz" 
                       target="_blank" 
                       rel="noopener noreferrer">Zobrazit plnou verzi</a>
                </div>

                <!-- ====== PROJEKT 3: ENDORA HOSTING ====== -->
                <div class="portfolio-item">
                    <h3>Endora/Webglobe hosting</h3>
                    <p>Aneb i na Endoře Sunlight CMS jede bez větších potíží.</p>
                    <iframe src="https://martysweb.4fan.cz" 
                            width="300" 
                            height="200" 
                            frameborder="0" 
                            allowfullscreen
                            loading="lazy"
                            title="Náhled Endora hostingu"></iframe>
                    <a href="https://martysweb.4fan.cz" 
                       target="_blank" 
                       rel="noopener noreferrer">Zobrazit plnou verzi</a>
                </div>
            </div>
        </section>
    </main>

    <!-- ====== PATIČKA ====== -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>