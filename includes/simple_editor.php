<?php
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
                <button type="button" onclick="insertTag('<?php echo $this->id; ?>', 'url')" title="Vložit odkaz">
                    Odkaz
                </button>
                <button type="button" onclick="showImagePicker('<?php echo $this->id; ?>')">Obrázek</button>
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
                        $imageDir = dirname(__DIR__) . '/uploads/images/';
                        if (is_dir($imageDir)) {
                            $images = glob($imageDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                            foreach ($images as $image) {
                                $filename = basename($image);
                                $webPath = getWebPath('uploads/images/' . $filename);
                                echo "<div class='image-item' onclick='insertImage(\"{$this->id}\", \"{$webPath}\")'>";
                                echo "<img src='{$webPath}' alt='{$filename}' />";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                    <button type="button" onclick="closeImagePicker()">Zavřít</button>
                </div>
            </div>
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
        
        // Převod BB kódů na HTML značky
        // [b]tučný text[/b] -> <strong>tučný text</strong>
        $content = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>$1</strong>', $content);
        
        // [i]kurzíva[/i] -> <em>kurzíva</em>
        $content = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>$1</em>', $content);
        
        // [url=http://...]text odkazu[/url] -> <a href="http://...">text odkazu</a>
        $content = preg_replace(
            '/\[url=(.*?)\](.*?)\[\/url\]/is', 
            '<a href="$1" target="_blank" rel="noopener noreferrer">$2</a>', 
            $content
        );
        
        // [img]obrázek[/img] -> <img src="obrázek" alt="Obrázek" class="editor-image">
        $content = preg_replace('/\[img\](.*?)\[\/img\]/is', '<img src="$1" alt="Obrázek" class="editor-image">', $content);
        
        // Převod nových řádků na HTML značky <br>
        return nl2br($content);
    }
}