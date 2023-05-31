<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dodawanie jednostki chorobowej</title>
</head>

<body>
            // combobox
        <label for="wirus">Wirus:</label>
        <select id="wirus" name="wirus">
            <?php
            // Dane do połączenia z bazą danych
            $host = "localhost";
            $dbname = "nazwa_bazy_danych";
            $username = "nazwa_uzytkownika";
            $password = "haslo";

            // Tworzenie połączenia z bazą danych
            $conn = pg_connect("host=$host dbname=$dbname user=$username password=$password");

            // Pobieranie danych dla comboboxa
            $query = "SELECT nazwa FROM wirusy";
            $result = pg_query($conn, $query);

            while ($row = pg_fetch_assoc($result)) {
                echo '<option value="' . $row['nazwa'] . '">' . $row['nazwa'] . '</option>';
            }

            pg_close($conn);
            ?>
        </select><br>
        //
    <form action="adres_do_przetwarzania_danych.php" method="post">
        <label for="jednostka_chorobowa">Jednostka chorobowa:</label>
        <input type="text" id="jednostka_chorobowa" name="jednostka_chorobowa" required><br>

        <label for="objawy_ogolne_miejscowe">Objawy ogólne i miejscowe poza j.u.:</label>
        <textarea id="objawy_ogolne_miejscowe" name="objawy_ogolne_miejscowe" required></textarea><br>

        <label for="objawy_miejscowe_ju">Objawy miejscowe w j.u.:</label>
        <textarea id="objawy_miejscowe_ju" name="objawy_miejscowe_ju" required></textarea><br>

        <label for="rozpoznanie">Rozpoznanie:</label>
        <textarea id="rozpoznanie" name="rozpoznanie" required></textarea><br>

        <label for="roznicowanie">Różnicowanie:</label>
        <textarea id="roznicowanie" name="roznicowanie" required></textarea><br>

        <input type="submit" value="Wyślij">
    </form>

</body>

</html>