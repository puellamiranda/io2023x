<?php
function get_conn_string()
{
    $ini = parse_ini_file("php.ini");
    $host = $ini["dbhost"];
    $db = $ini["dbname"];
    $usr = $ini["dbuser"];
    $pass = $ini["dbpass"];
    $conn_string = "host=$host port=5432 dbname=$db user=$usr password=$pass";
    return $conn_string;
}

// Pobierz identyfikator choroby z parametru URL
$id = $_GET['id'];

// Obsługa aktualizacji
if (isset($_POST['submit'])) {
    // Pobierz wartości z formularza
    $choroba = $_POST['choroba'];
    $objawy_ogolne = $_POST['objawy_ogolne'];
    $objawy_ju = $_POST['objawy_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];
    $id_wirus = $_POST['id_wirus'];

    // Połączenie z bazą danych PostgreSQL
    $conn = pg_connect(get_conn_string());
    
    // Zabezpieczenie przed SQL Injection
    $choroba = pg_escape_string($conn, $choroba);
    $objawy_ogolne = pg_escape_string($conn, $objawy_ogolne);
    $objawy_ju = pg_escape_string($conn, $objawy_ju);
    $rozpoznanie = pg_escape_string($conn, $rozpoznanie);
    $roznicowanie = pg_escape_string($conn, $roznicowanie);
    $id_wirus = pg_escape_string($conn, $id_wirus);

    // Aktualizacja danych choroby w bazie danych
    $query = "UPDATE choroba SET choroba = '$choroba', objawy_ogolne = '$objawy_ogolne', objawy_ju = '$objawy_ju', rozpoznanie = '$rozpoznanie', roznicowanie = '$roznicowanie', id_wirus = $id_wirus WHERE id = $id";
    pg_query($conn, $query);

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);

    // Wyświetlenie messageboxa po aktualizacji bazy wirusów
    echo "<script>alert('Baza wirusów została zaktualizowana');</script>";
}
?>

<!-- Obsługa wyświetlania -->
<?php
// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

$query = "SELECT c.id, c.choroba, w.nazwa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie, c.id_wirus
          FROM choroba c
          JOIN wirus w ON c.id_wirus = w.id
          WHERE c.id = $id";
$result = pg_query($conn, $query);
$row = pg_fetch_assoc($result);

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edytuj chorobę wirusową</title>
</head>
<body>
    <h2>Edytuj chorobę wirusową</h2>

    <form action="edytuj_chorobe.php?id=<?php echo $id; ?>" method="POST">
        <label for="choroba">Jednostka chorobowa:</label>
        <input type="text" id="choroba" name="choroba" value="<?php echo $row['choroba']; ?>" required>
        <br><br>

        <label for="id_wirus">Czynnik etiologiczny:</label>
        <select id="id_wirus" name="id_wirus" required>
            <?php
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Pobranie wszystkich wirusów
            $query = "SELECT * FROM wirus ORDER BY id";
            $result = pg_query($conn, $query);

            // Iterujesz przez wyniki zapytania i generujesz opcje w formularzu
            while ($virus = pg_fetch_assoc($result)) {
                $selected = ($virus['id'] == $row['id_wirus']) ? "selected" : "";
                echo "<option value='{$virus['id']}' $selected>{$virus['nazwa']}</option>";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
            ?>
        </select>
        <br><br>

        <label for="objawy_ogolne">Objawy ogólne lub miejscowe poza jamą ustną:</label>
        <textarea id="objawy_ogolne" name="objawy_ogolne" required><?php echo $row['objawy_ogolne']; ?></textarea>
        <br><br>

        <label for="objawy_ju">Objawy miejscowe w jamie ustnej:</label>
        <textarea id="objawy_ju" name="objawy_ju" required><?php echo $row['objawy_ju']; ?></textarea>
        <br><br>

        <label for="rozpoznanie">Rozpoznanie:</label>
        <textarea id="rozpoznanie" name="rozpoznanie" required><?php echo $row['rozpoznanie']; ?></textarea>
        <br><br>

        <label for="roznicowanie">Różnicowanie:</label>
        <textarea id="roznicowanie" name="roznicowanie" required><?php echo $row['roznicowanie']; ?></textarea>
        <br><br>

        <input type="submit" name="submit" value="Zapisz zmiany">
    </form>
</body>
</html>

<br><br>
<a href="index.php">Powrót do strony głównej</a>


<!--
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edytuj chorobę wirusową</title>

    <script>
        function showMessageBox() {
            alert("Baza wirusów została zaktualizowana");
       }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <h1>Edytuj chorobę wirusową</h1>

    <?php
    function get_conn_string()
    {
        $ini = parse_ini_file("php.ini");
        $host = $ini["dbhost"];
        $db = $ini["dbname"];
        $usr = $ini["dbuser"];
        $pass = $ini["dbpass"];
        $conn_string = "host=$host port=5432 dbname=$db user=$usr password=$pass";
        return $conn_string;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Pobranie danych z formularza
        $choroba = $_POST['choroba'];
        $objawy_ogolne = $_POST['objawy_ogolne'];
        $objawy_ju = $_POST['objawy_ju'];
        $rozpoznanie = $_POST['rozpoznanie'];
        $roznicowanie = $_POST['roznicowanie'];
        $id_wirus = $_POST['id_wirus'];

        // Sprawdzenie, czy wszystkie pola formularza są wypełnione
        if (empty($choroba) || empty($objawy_ogolne) || empty($objawy_ju) || empty($rozpoznanie) || empty($roznicowanie)) {
            echo "Wypełnij wszystkie pola formularza.";
        } else {
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Zabezpieczenie przed SQL Injection
            $choroba = pg_escape_string($conn, $choroba);
            $objawy_ogolne = pg_escape_string($conn, $objawy_ogolne);
            $objawy_ju = pg_escape_string($conn, $objawy_ju);
            $rozpoznanie = pg_escape_string($conn, $rozpoznanie);
            $roznicowanie = pg_escape_string($conn, $roznicowanie);

            // Wstawienie danych choroby do bazy danych
            $id = $_GET['id']; // Pobierz identyfikator choroby z parametru URL
            $query = "UPDATE choroba SET choroba = '$choroba', objawy_ogolne = '$objawy_ogolne', objawy_ju = '$objawy_ju', rozpoznanie = '$rozpoznanie', roznicowanie = '$roznicowanie', id_wirus = $id_wirus WHERE id = $id";
            $result = pg_query($conn, $query);

            if ($result) {
//                echo "Baza chorób została zaktualizowana.";
                echo "<script>showMessageBox();</script>";
            } else {
                echo "Wystąpił błąd podczas edycji choroby.";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
        }
    } else {
        // Jeżeli nie jest to zapytanie POST, wyświetl formularz edycji
        $id = $_GET['id'];
        $conn = pg_connect(get_conn_string());
        $query = "SELECT * FROM choroba WHERE id = $id";
        $result = pg_query($conn, $query);
        $row = pg_fetch_assoc($result);
        $choroba = $row['choroba'];
        $objawy_ogolne = $row['objawy_ogolne'];
        $objawy_ju = $row['objawy_ju'];
        $rozpoznanie = $row['rozpoznanie'];
        $roznicowanie = $row['roznicowanie'];
        $id_wirus = $row['id_wirus'];

        // Pobierz wirusy dla listy rozwijanej
        $query_wirus = "SELECT * FROM wirus";
        $result_wirus = pg_query($conn, $query_wirus);

        pg_close($conn);
    }
?>

    <form action="edytuj_chorobe.php?id=<?php echo $id; ?>" method="POST">
    <div class="form-floating mb-3">
-->  <!-- <span class="input-group-text" id="basic-addon1">Jednostka chorobowa</span> --> <!--
            <input type="text" id="choroba" name="choroba" required class="form-control">
            <label for="choroba" class="form-label">Jednostka chorobowa:</label>
        </div>    
  
        <label for="id_wirus">Czynnik etiologiczny:</label>
        <div class="row">
            <div class="col">
                <div class="form mb-3">
                <select class="form-select" id="id_wirus" name="id_wirus">
                <?php
                // Pobierz wirusy dla listy rozwijanej
                $query_wirus = "SELECT * FROM wirus";
                $result_wirus = pg_query($conn, $query_wirus);

                if ($result_wirus && pg_num_rows($result_wirus) > 0) {
                    while ($wirus = pg_fetch_assoc($result_wirus)) {
                        echo "<option value='" . $wirus['id'] . "'>" . $wirus['nazwa'] . "</option>";
                    }
                }
                ?>
                </select>
                </div>
            </div>
 
        <div class="form-floating mb-3">
            <input type="text" id="objawy_ogolne" name="objawy_ogolne" required class="form-control">
            <label for="objawy_ogolne" class="form-label">Objawy ogólne i miejscowe poza jamą ustną:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="objawy_ju" name="objawy_ju" required class="form-control">
            <label for="objawy_ju" class="form-label">Objawy miejscowe w jamie ustnej:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="rozpoznanie" name="rozpoznanie" required class="form-control">
            <label for="rozpoznanie" class="form-label">Rozpoznanie:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="roznicowanie" name="roznicowanie" required class="form-control">
            <label for="roznicowanie" class="form-label">Różnicowanie:</label>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Zapisz zmiany</button>
        </div>
    </form>

    <br><br>
    <button type="button" class="btn btn-secondary" onclick="location.href='index.php';">Wróć na stronę główną</button>

</body>
</html>
            -->