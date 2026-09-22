```php
<?php

session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../klase/Zaduzenje.php";

$brojZapisnika = isset($_GET["broj_zapisnika"])
    ? trim($_GET["broj_zapisnika"])
    : "";

$zaposleni = isset($_GET["zaposleni"])
    ? trim($_GET["zaposleni"])
    : "";

$odeljenje = isset($_GET["odeljenje"])
    ? trim($_GET["odeljenje"])
    : "";

$zaduzenje = new Zaduzenje();

$upit = "SELECT
            id_zaduzenja,
            broj_zapisnika,
            datum,
            zaposleni,
            odeljenje,
            napomena
         FROM zaduzenje
         WHERE broj_zapisnika LIKE ?
         AND zaposleni LIKE ?
         AND odeljenje LIKE ?
         ORDER BY datum DESC";

$stmt = $zaduzenje->getKonekcija()->prepare($upit);

$brojFilter = "%" . $brojZapisnika . "%";
$zaposleniFilter = "%" . $zaposleni . "%";
$odeljenjeFilter = "%" . $odeljenje . "%";

$stmt->bind_param(
    "sss",
    $brojFilter,
    $zaposleniFilter,
    $odeljenjeFilter
);

$stmt->execute();

$rezultat = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <title>Štampanje filtriranih zaduženja</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1 {
            text-align: center;
        }

        .dugmad {
            margin-bottom: 20px;
        }

        button,
        a {
            display: inline-block;
            padding: 10px 15px;
            margin-right: 5px;
            background-color: #3f6fb6;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filteri {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eeeeee;
        }

        @media print {

            .dugmad {
                display: none;
            }

            body {
                margin: 10mm;
            }

        }

    </style>

</head>

<body>

    <div class="dugmad">

        <button onclick="window.print()">
            Štampaj
        </button>

        <a href="../stranice/pregledZaduzenja.php">
            Nazad
        </a>

    </div>

    <h1>Pregled filtriranih zaduženja</h1>

    <div class="filteri">

        <strong>Izabrani filteri:</strong>

        <br><br>

        Broj zapisnika:
        <?php echo htmlspecialchars($brojZapisnika); ?>

        <br>

        Zaposleni:
        <?php echo htmlspecialchars($zaposleni); ?>

        <br>

        Odeljenje:
        <?php echo htmlspecialchars($odeljenje); ?>

    </div>

    <table>

        <thead>

            <tr>
                <th>Broj zapisnika</th>
                <th>Datum</th>
                <th>Zaposleni</th>
                <th>Odeljenje</th>
                <th>Napomena</th>
            </tr>

        </thead>

        <tbody>

            <?php if ($rezultat->num_rows > 0): ?>

                <?php while ($red = $rezultat->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($red["broj_zapisnika"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($red["datum"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($red["zaposleni"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($red["odeljenje"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($red["napomena"]); ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="5">
                        Nema rezultata za zadate filtere.
                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</body>

</html>
```
