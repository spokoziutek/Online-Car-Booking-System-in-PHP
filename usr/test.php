<?php
// Włącz wyświetlanie błędów (dla celów debugowania)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Rozpocznij sesję
session_start();

// Połączenie z bazą danych
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();

// Sprawdź, czy formularz został przesłany
if (isset($_POST['submit'])) {
    // Pobierz dane z formularza
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Przygotuj zapytanie SQL do wstawienia danych do tabeli
    $query = "INSERT INTO tms_test (first_name, last_name) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Powiąż parametry z zapytaniem
        $stmt->bind_param('ss', $first_name, $last_name);
        // Wykonaj zapytanie
        $stmt->execute();

        // Sprawdź, czy zapytanie się powiodło
        if ($stmt->affected_rows > 0) {
            $message = "Dane zostały pomyślnie zapisane.";
        } else {
            $message = "Wystąpił błąd. Spróbuj ponownie.";
        }
        // Zamknij zapytanie
        $stmt->close();
    } else {
        $message = "Błąd bazy danych: Nie można przygotować zapytania.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prosty Formularz</title>
</head>
<body>
    <h2>Formularz</h2>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    <form method="post" action="">
        <div>
            <label for="first_name">Imię:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div>
            <label for="last_name">Nazwisko:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div>
            <button type="submit" name="submit">Wyślij</button>
        </div>
    </form>
</body>
</html>
