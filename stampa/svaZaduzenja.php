
<?php

session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../klase/Zaduzenje.php";

$zaduzenje = new Zaduzenje();

$rezultat = $zaduzenje->prikaziSvaZaduzenja();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <title>Štampanje svih zaduženja</title>

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
            padding: 10px 15px;
            margin-right: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            background-color: #3f6fb6;
            color: white;
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

        <a href="index.php">
            Nazad
        </a>

    </div>

    <h1>Pregled svih zaduženja</h1>

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

        </tbody>

    </table>

</body>

</html>

