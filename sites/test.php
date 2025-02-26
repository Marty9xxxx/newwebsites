<?php
// test.php
session_start();

// Load the configuration
require_once dirname(__DIR__) . '/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = "json_decode(file_get_contents(getFilePath('data', 'users.json')), true);";

if (file_exists($file)) {
    echo "Soubor existuje!<br>";
    echo "Cesta k souboru: " . realpath($file) . "<br>";
    echo "Obsah souboru:<br>";
    echo nl2br(file_get_contents($file));
} else {
    echo "Soubor nenalezen!";
}
?>
//             <label>Heslo:</label>   
//             <input type="password" name="password" required>
//         </div>
//         <button type="submit">Přihlásit</button> 
//           </form>
//     <a href="<?php echo getWebPath('includes/register.php'); ?>">Registrovat se</a>  
//     <?php include(getFilePath('includes', 'footer.php')); ?>
// </body>
