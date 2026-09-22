
<?php

session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../klase/Zaduzenje.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Zaduženje nije pravilno izabrano.");
}

$idZaduzenja = (int) $_GET["id"];

$zaduzenje = new Zaduzenje();

$podaci = $zaduzenje->pronadjiPoId($idZaduzenja);

if (!$podaci) {
    die("Zaduženje ne postoji.");
}

$stavke = $zaduzenje->pronadjiStavke($idZaduzenja);

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Zapisnik o zaduživanju službene opreme</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #eeeeee;
            margin: 0;
            padding: 30px;
        }

        .dugmad {
            width: 800px;
            margin: 0 auto 20px auto;
        }

        .dugmad button,
        .dugmad a {
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

        .dokument {
            width: 800px;
            min-height: 1000px;
            margin: 0 auto;
            padding: 50px;
            box-sizing: border-box;
            background-color: white;
        }

        h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 40px;
        }

        .podaci {
            margin-bottom: 30px;
        }

        .podaci p {
            margin: 10px 0;
        }

        .tabela {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .tabela th,
        .tabela td {
            border: 1px solid black;
            padding: 8px;
        }

        .tabela th {
            text-align: center;
        }

        .tabela td {
            vertical-align: top;
        }

        .napomena {
            margin-top: 30px;
        }

        .potpisi {
            display: flex;
            justify-content: space-between;
            margin-top: 100px;
        }

        .potpis {
            width: 250px;
            text-align: center;
        }

        .linija {
            border-top: 1px solid black;
            margin-bottom: 8px;
        }

        @media print {

            body {
                background-color: white;
                padding: 0;
            }

            .dugmad {
                display: none;
            }

            .dokument {
                width: 100%;
                min-height: auto;
                padding: 20px;
                margin: 0;
            }

        }

    </style>

</head>

<body>

    <div class="dugmad">

        <button onclick="window.print()">
            Štampaj zapisnik
        </button>

        <a href="../stranice/pregledZaduzenja.php">
            Nazad
        </a>

    </div>

    <div class="dokument">

        <h1>
            ZAPISNIK O ZADUŽIVANJU SLUŽBENE OPREME
        </h1>

        <div class="podaci">

            <p>
                <strong>Broj zapisnika:</strong>
                <?php echo htmlspecialchars($podaci["broj_zapisnika"]); ?>
            </p>

            <p>
                <strong>Datum:</strong>
                <?php echo htmlspecialchars($podaci["datum"]); ?>
            </p>

            <p>
                <strong>Zaposleni:</strong>
                <?php echo htmlspecialchars($podaci["zaposleni"]); ?>
            </p>

            <p>
                <strong>Odeljenje:</strong>
                <?php echo htmlspecialchars($podaci["odeljenje"]); ?>
            </p>

        </div>

        <p>
            Zaposlenom je predata sledeća službena oprema:
        </p>

        <table class="tabela">

            <thead>

                <tr>
                    <th>Redni broj</th>
                    <th>Oprema</th>
                    <th>Proizvođač</th>
                    <th>Količina</th>
                    <th>Stanje</th>
                    <th>Napomena</th>
                </tr>

            </thead>

            <tbody>

                <?php

                $redniBroj = 1;

                while ($stavka = $stavke->fetch_assoc()):

                ?>

                    <tr>

                        <td style="text-align: center;">
                            <?php echo $redniBroj; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["naziv"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["proizvodjac"]); ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($stavka["kolicina"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["stanje"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["napomena"]); ?>
                        </td>

                    </tr>

                <?php

                    $redniBroj++;

                endwhile;

                ?>

            </tbody>

        </table>

        <?php if (!empty($podaci["napomena"])): ?>

            <div class="napomena">

                <strong>Napomena:</strong>

                <p>
                    <?php echo nl2br(htmlspecialchars($podaci["napomena"])); ?>
                </p>

            </div>

        <?php endif; ?>

        <p style="margin-top: 40px;">
            Potpisivanjem ovog zapisnika zaposleni potvrđuje da je navedenu
            službenu opremu preuzeo i da je upoznat sa obavezom njenog
            odgovornog korišćenja.
        </p>

        <div class="potpisi">

            <div class="potpis">

                <div class="linija"></div>

                Zaposleni

            </div>

            <div class="potpis">

                <div class="linija"></div>

                Odgovorno lice

            </div>

        </div>

    </div>

</body>

</html>

