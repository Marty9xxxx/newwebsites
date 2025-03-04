/**
 * Vloží BB kód do textového pole
 * @param {string} editorId - ID editoru pro vložení kódu
 * @param {string} tag - Typ BB kódu (b, i, url)
 */
function insertTag(editorId, tag) {
    // Najde textarea podle ID
    const textarea = document.getElementById(editorId);
    // Získá pozici kurzoru nebo označeného textu
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    // Získá označený text, pokud nějaký je
    const selected = textarea.value.substring(start, end);
    
    let insert;
    // Podle typu tagu vytvoří příslušný BB kód
    switch(tag) {
        case 'b':
            // Tučný text: [b]text[/b]
            insert = `[b]${selected}[/b]`;
            break;
        case 'i':
            // Kurzíva: [i]text[/i]
            insert = `[i]${selected}[/i]`;
            break;
        case 'url':
            // Odkaz: [url=https://...]text[/url]
            const url = prompt('Zadejte URL:', 'https://');
            if (url) {
                // Pokud není vybraný text, použije "odkaz"
                insert = `[url=${url}]${selected || 'odkaz'}[/url]`;
            }
            break;
    }
    
    // Vloží BB kód do textarea
    if (insert) {
        textarea.value = textarea.value.substring(0, start) + 
                        insert + 
                        textarea.value.substring(end);
    }
}

/**
 * Zobrazí dialog pro výběr obrázku
 * @param {string} editorId - ID editoru pro vložení obrázku
 */
function showImagePicker(editorId) {
    // Zobrazí modální okno s výběrem obrázků
    document.getElementById('imagePicker').style.display = 'flex';
    currentEditorId = editorId;
}

function closeImagePicker() {
    document.getElementById('imagePicker').style.display = 'none';
}

function insertImage(editorId, imagePath) {
    const textarea = document.getElementById(editorId);
    const imageCode = `[img]${imagePath}[/img]`;
    
    const start = textarea.selectionStart;
    textarea.value = textarea.value.substring(0, start) + 
                    imageCode + 
                    textarea.value.substring(textarea.selectionEnd);
    
    closeImagePicker();
}

/**
 * Nahraje obrázek na server pomocí upload.php
 * @param {string} editorId - ID editoru pro vložení obrázku
 * @param {File} file - Soubor k nahrání
 */
function uploadImage(editorId, file) {
    // Vytvoření FormData objektu pro odeslání souboru
    const formData = new FormData();
    formData.append('file', file);
    
    // Odeslání požadavku na server
    fetch('../../admin/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Vložení odkazu na nahraný obrázek do editoru
            insertImage(editorId, data.url);
        } else {
            // Zobrazení chybové hlášky
            alert('Chyba při nahrávání: ' + (data.error || 'Neznámá chyba'));
        }
    })
    .catch(error => {
        alert('Chyba při nahrávání: ' + error);
    });
}

/**
 * Přidání drag & drop podpory pro nahrávání obrázků
 * @param {string} editorId - ID editoru
 */
function initImageDragDrop(editorId) {
    const textarea = document.getElementById(editorId);
    
    // Zamezení výchozímu chování prohlížeče
    textarea.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
    });
    
    // Zpracování přetaženého souboru
    textarea.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Kontrola, zda byl přetažen soubor
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            const file = e.dataTransfer.files[0];
            
            // Kontrola typu souboru
            if (file.type.startsWith('image/')) {
                uploadImage(editorId, file);
            } else {
                alert('Prosím, přetáhněte pouze obrázky.');
            }
        }
    });
}

// Inicializace drag & drop při vytvoření editoru
document.addEventListener('DOMContentLoaded', () => {
    // Najde všechny editory na stránce
    const editors = document.querySelectorAll('.simple-editor-area');
    editors.forEach(editor => {
        initImageDragDrop(editor.id);
    });
});