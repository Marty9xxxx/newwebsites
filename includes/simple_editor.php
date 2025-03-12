<?php
// Načtení helper funkcí
require_once dirname(__DIR__) . '/includes/debug_helper.php';

/**
 * Třída SimpleEditor - Jednoduchý WYSIWYG editor s BB kódy
 * Umožňuje základní formátování textu pomocí BB kódů
 */
class SimpleEditor {
    /** @var string ID editoru pro identifikaci v DOM */
    private $id;
    
    /** @var string Výchozí obsah editoru */
    private $content;
    
    /**
     * Konstruktor třídy
     * @param string $id Jedinečné ID editoru
     * @param string $content Výchozí obsah editoru (nepovinné)
     */
    public function __construct($id, $content = '') {
        $this->id = $id;
        $this->content = $content;
    }
    
    /**
     * Vykreslení editoru do HTML
     * Generuje toolbar s tlačítky a textarea pro obsah
     */
    public function render() {
        debugLog('Rendering Simple Editor: ' . $this->id);
        ?>
        <!-- Kontejner editoru -->
        <div class="simple-editor">
            <!-- Panel nástrojů s tlačítky pro formátování -->
            <div class="editor-toolbar">
                <!-- Tlačítka pro formátování textu -->
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'b')" title="Tučné písmo">
                    Tučně
                </button>
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'i')" title="Kurzíva">
                    Kurzíva
                </button>
                
                <!-- Nová tlačítka pro zarovnání -->
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'left')" title="Zarovnat doleva">
                    Doleva
                </button>
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'center')" title="Zarovnat na střed">
                    Na střed
                </button>
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'right')" title="Zarovnat doprava">
                    Doprava
                </button>
                
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'url')" title="Vložit odkaz">
                    Odkaz
                </button>
                <button type="button" onclick="showImagePicker('<?php echo $this->id; ?>')">Obrázek</button>
                
                <!-- Tlačítko pro přepínání zdrojového kódu -->
                <button type="button" 
                        onclick="toggleSource('<?php echo $this->id; ?>')" 
                        class="source-button"
                        title="Zobrazit/skrýt zdrojový kód">
                    Zdrojový kód
                </button>
            </div>
            
            <!-- Hlavní editační pole -->
            <textarea id="<?php echo $this->id; ?>" 
                      name="<?php echo $this->id; ?>" 
                      class="simple-editor-area"><?php echo htmlspecialchars($this->content); ?></textarea>
            
            <!-- Dialog pro výběr obrázku -->
            <div id="imagePicker" class="image-picker" style="display: none;">
                <div class="image-picker-content">
                    <h3>Vyberte obrázek</h3>
                    <div class="image-grid">
                        <?php
                        // Definice cesty k obrázkům
                        $imageDir = dirname(__DIR__) . '/uploads/images/';
                        debugLog('Načítání obrázků z: ' . $imageDir);
                        
                        // Kontrola existence složky
                        if (!is_dir($imageDir)) {
                            debugLog('Složka neexistuje, vytvářím...', 'WARN');
                            mkdir($imageDir, 0777, true);
                        }
                        
                        // Načtení všech obrázků
                        $images = glob($imageDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                        debugLog('Nalezené obrázky: ' . print_r($images, true));
                        
                        if (empty($images)) {
                            echo '<p class="no-images">Žádné obrázky k dispozici</p>';
                        } else {
                            foreach ($images as $image) {
                                $filename = basename($image);
                                $webPath = getWebPath('uploads/images/' . $filename);
                                echo "<div class='image-item' onclick='insertImage(\"{$this->id}\", \"{$webPath}\")'>";
                                echo "<img src='{$webPath}' alt='{$filename}' />";
                                echo "<span class='image-name'>{$filename}</span>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                    <button type="button" onclick="closeImagePicker()">Zavřít</button>
                </div>
            </div>
            
            <!-- Styly pro výběr obrázků -->
            <style>
            .image-picker {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.8);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
            
            .image-picker-content {
                background: white;
                padding: 20px;
                border-radius: 5px;
                max-width: 800px;
                max-height: 80vh;
                overflow-y: auto;
            }
            
            .image-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 10px;
                margin: 15px 0;
            }
            
            .image-item {
                cursor: pointer;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 4px;
                text-align: center;
            }
            
            .image-item:hover {
                border-color: #573232;
            }
            
            .image-item img {
                max-width: 100%;
                height: auto;
            }
            
            .image-name {
                display: block;
                margin-top: 5px;
                font-size: 12px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .no-images {
                text-align: center;
                padding: 20px;
                color: #666;
            }
            </style>
        </div>
        <?php
    }
    
    /**
     * Statická metoda pro převod BB kódů na HTML
     * @param string $content Text s BB kódy
     * @return string HTML formátovaný text
     */
    public static function parseContent($content) {
        // Ochrana proti XSS útokům
        $content = htmlspecialchars($content);
        
        // Debug výpis
        debugLog('Parsování obsahu:', 'DEBUG');
        debugLog($content, 'DEBUG');
        
        // BB kódy pro obrázky - upraveno pro správné zobrazení
        $content = preg_replace(
            '/\[img\](.*?)\[\/img\]/i',
            '<img src="$1" alt="Obrázek" class="content-image">',
            $content
        );
        
        // BB kódy pro odkazy
        $content = preg_replace(
            '/\[url\](.*?)\[\/url\]/i',
            '<a href="$1" target="_blank">$1</a>',
            $content
        );
        $content = preg_replace(
            '/\[url=(.*?)\](.*?)\[\/url\]/i',
            '<a href="$1" target="_blank">$2</a>',
            $content
        );
        
        // Ostatní BB kódy
        $content = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>$1</strong>', $content);
        $content = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>$1</em>', $content);
        $content = preg_replace('/\[u\](.*?)\[\/u\]/is', '<u>$1</u>', $content);
        
        // Zarovnání textu
        $content = preg_replace('/\[left\](.*?)\[\/left\]/is', '<div style="text-align: left">$1</div>', $content);
        $content = preg_replace('/\[center\](.*?)\[\/center\]/is', '<div style="text-align: center">$1</div>', $content);
        $content = preg_replace('/\[right\](.*?)\[\/right\]/is', '<div style="text-align: right">$1</div>', $content);
        
        // Převod nových řádků
        return nl2br($content);
    }
}