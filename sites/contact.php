
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ověření formuláře
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = $_POST['message'];
    $subject = $_POST['subject'];
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message) && !empty($subject)) {
        echo "Děkujeme za zprávu, odpovíme co nejdříve.";
        // Zde by se normálně posílal email nebo ukládal zpráva.
    } else {
        echo "Prosím vyplňte všechna pole správně.";
    }
}
?>

<?php include 'header.php'; ?>

<main>
    <section class="content">
        <h2>Napište mi</h2>
        <form method="post" action="contact.php">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="subject">Předmět:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Zpráva:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Odeslat</button>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
