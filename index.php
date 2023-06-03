<!DOCTYPE html>
<html>

<head>
    <title>Tabela Chorób</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- <h1>Jeden wirus może powodować wiele chorób</h1>
    <h2>Choroba może być powodowana tylko przez jednego wirusa!!</h2>
    <img src="doc/wirusy.png" width=500 /><br> -->
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
    ?>
    <h1>Wybrane choroby wirusowe manifestujące objawy w jamie ustnej</h1>

    <button type="button" class="btn btn-primary" onclick="location.href='dodaj_chorobe.php';">Dodaj chorobę</button>
    <button type="button" class="btn btn-secondary" onclick="location.href='wirusy.php';">Wyświetl wirusy</button>
    <br><br>
    <?php
      
    // Połączenie z bazą danych PostgreSQL
    $conn = pg_connect(get_conn_string());

    // Pobranie danych z tabeli choroba, wraz z nazwą wirusa
    $query = "SELECT c.id, c.choroba, w.nazwa AS nazwa_wirusa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie
    FROM choroba c
    JOIN wirus w ON c.id_wirus = w.id
    ORDER BY c.id";

    $result = pg_query($conn, $query);
              
    // Sprawdzenie, czy są dostępne dane
    if (pg_num_rows($result) > 0) {
      echo '<table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Lp.</th>
                  <th scope="col">Jednostka chorobowa</th>
                  <th scope="col">Czynnik etiologiczny</th>
                  <th scope="col">Objawy ogólne lub miejscowe poza jamą ustną</th>
                  <th scope="col">Objawy miejscowe w jamie ustnej</th>
                  <th scope="col">Rozpoznanie</th>
                  <th scope="col">Różnicowanie</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>';

      $lp = 1; // Zmienna licznikowa

      // Iterujesz przez wyniki zapytania i generujesz wiersze tabeli HTML
      while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo '<th scope="row">' . $lp . "</td>";
        echo "<td>" . $row['choroba'] . "</td>";
        echo "<td><a href='wirusy.php?nazwa=" . urlencode($row['nazwa_wirusa']) . "'>" . $row['nazwa_wirusa'] . "</a></td>";
        echo "<td>" . $row['objawy_ogolne'] . "</td>";
        echo "<td>" . $row['objawy_ju'] . "</td>";
        echo "<td>" . $row['rozpoznanie'] . "</td>";
        echo "<td>" . $row['roznicowanie'] . "</td>";
        echo "<td><button type='button' class='btn btn-primary' onclick='location.href=\"edytuj_chorobe.php?id={$row['id']}\"'>Edytuj</button></td>";
        echo "<td><button type='button' class='btn btn-danger' onclick='location.href=\"usun_chorobe.php?id={$row['id']}\"'>Usuń</button></td>";
        echo "</tr>";
        
              $lp++; // Inkrementacja zmiennej licznikowej
      }

        echo '</tbody>
            </table>';
    } else {
      echo "Brak dostępnych danych.";
    }

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);
  ?>
</body>

</html>