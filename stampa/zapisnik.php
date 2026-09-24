<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


// ---------------------------------------------------------
// PROVERA ID-A
// ---------------------------------------------------------

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    die("Neispravan ID zaduženja.");
}


$idZaduzenja =
    (int) $_GET["id"];


// ---------------------------------------------------------
// KONTROLER
// ---------------------------------------------------------

$kontroler =
    new ZaduzenjeKontroler();


// ---------------------------------------------------------
// ZADUŽENJE
// ---------------------------------------------------------

$zaduzenje =
    $kontroler->pronadjiPoId(
        $idZaduzenja
    );


if (!$zaduzenje) {

    die("Zaduženje nije pronađeno.");
}


// ---------------------------------------------------------
// STAVKE
// ---------------------------------------------------------

$stavke =
    $kontroler->pronadjiStavke(
        $idZaduzenja
    );

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<title>
    Zapisnik o zaduživanju službene opreme
</title>

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #eeeeee;
    margin: 0;
    padding: 30px;
}

.dokument {
    width: 800px;
    min-height: 1000px;
    margin: auto;
    padding: 50px;
    background-color: white;
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

h1 {
    text-align: center;
    font-size: 22px;
    margin-bottom: 40px;
}

.podaci {
    margin-bottom: 30px;
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
}

th {
    background-color: #eeeeee;
}

.potpis {
    margin-top: 100px;
    display: flex;
    justify-content: space-between;
}

.potpis div {
    width: 40%;
    text-align: center;
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
        width: auto;
        min-height: auto;
        padding: 20px;
    }

}

</style>

</head>

<body>

<div class="dugmad">

    <button onclick="window.print()">
        Štampaj
    </button>

    <a href="../stranice/detaljiZaduzenja.php?id=<?php
        echo $idZaduzenja;
    ?>">
        Nazad
    </a>

</div>


<div class="dokument">

    <h1>
        ZAPISNIK O ZADUŽIVANJU
        SLUŽBENE OPREME
    </h1>


    <div class="podaci">

        <p>
            <strong>
                Broj zapisnika:
            </strong>

            <?php
            echo htmlspecialchars(
                $zaduzenje->getBrojZapisnika()
            );
            ?>
        </p>


        <p>
            <strong>
                Datum:
            </strong>

            <?php
            echo htmlspecialchars(
                $zaduzenje->getDatum()
            );
            ?>
        </p>


        <p>
            <strong>
                Zaposleni:
            </strong>

            <?php
            echo htmlspecialchars(
                $zaduzenje->getZaposleni()
            );
            ?>
        </p>


        <p>
            <strong>
                Odeljenje:
            </strong>

            <?php
            echo htmlspecialchars(
                $zaduzenje->getOdeljenje()
            );
            ?>
        </p>

    </div>


    <table>

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

        foreach ($stavke as $stavka):

            $oprema =
                $stavka->getOprema();

        ?>

            <tr>

                <td>
                    <?php
                    echo $redniBroj++;
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $oprema->getNaziv()
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $oprema->getProizvodjac()
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $stavka->getKolicina()
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $stavka->getStanje()
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $stavka->getNapomena()
                    );
                    ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>


    <?php if (
        trim($zaduzenje->getNapomena()) !== ""
    ): ?>

        <p>

            <strong>
                Napomena:
            </strong>

            <?php
            echo htmlspecialchars(
                $zaduzenje->getNapomena()
            );
            ?>

        </p>

    <?php endif; ?>


    <p style="margin-top: 40px;">

        Ovim zapisnikom potvrđuje se da je
        navedena službena oprema predata
        zaposlenom na korišćenje i čuvanje.

    </p>


    <div class="potpis">

        <div>

            Zaposleni

            <br><br><br>

            ______________________

        </div>


        <div>

            Odgovorno lice

            <br><br><br>

            ______________________

        </div>

    </div>

</div>

</body>

</html>