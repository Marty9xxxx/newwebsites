<?php
/**
 * Třída pro práci s TinyMCE editorem
 */
class TinyMCEEditor {
    /** @var string ID editoru */
    private $id;
    
    /** @var string Výchozí obsah editoru */
    private $content;
    
    /**
     * Konstruktor třídy
     * @param string $id ID editoru
     * @param string $content Výchozí obsah
     */
    public function __construct($id, $content = '') {
        $this->id = $id;
        $this->content = $content;
    }
    
    /**
     * Vykreslení editoru
     */
    public function render() {
        ?>
        <textarea id="<?php echo htmlspecialchars($this->id); ?>" 
                  name="<?php echo htmlspecialchars($this->id); ?>" 
                  class="editor"><?php echo htmlspecialchars($this->content); ?></textarea>
        <?php
    }
}