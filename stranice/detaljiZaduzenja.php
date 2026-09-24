<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


// ---------------------------------------------------------
// ID ZADUŽENJA
// ---------------------------------------------------------

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header(
        "Location: pregledZaduzenja.php"
    );

    exit;
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

    header(
        "Location: pregledZaduzenja.php"
    );

    exit;
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

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Detalji zaduženja</title>

<link rel="stylesheet"
      href="../css/stil.css">

</head>

<body>

<?php require_once "zaglavlje.php"; ?>


<main class="sadrzaj">

    <h2>
        Detalji zaduženja
    </h2>


    <p>
        <strong>Broj zapisnika:</strong>

        <?php
        echo htmlspecialchars(
            $zaduzenje->getBrojZapisnika()
        );
        ?>
    </p>


    <p>
        <strong>Datum:</strong>

        <?php
        echo htmlspecialchars(
            $zaduzenje->getDatum()
        );
        ?>
    </p>


    <p>
        <strong>Zaposleni:</strong>

        <?php
        echo htmlspecialchars(
            $zaduzenje->getZaposleni()
        );
        ?>
    </p>


    <p>
        <strong>Odeljenje:</strong>

        <?php
        echo htmlspecialchars(
            $zaduzenje->getOdeljenje()
        );
        ?>
    </p>


    <p>
        <strong>Napomena:</strong>

        <?php
        echo htmlspecialchars(
            $zaduzenje->getNapomena()
        );
        ?>
    </p>


    <h3>
        Oprema
    </h3>


    <table class="tabela">

        <thead>

            <tr>

                <th>Oprema</th>
                <th>Proizvođač</th>
                <th>Količina</th>
                <th>Stanje</th>
                <th>Napomena</th>

            </tr>

        </thead>


        <tbody>

        <?php foreach ($stavke as $stavka): ?>

            <?php
            $oprema =
                $stavka->getOprema();
            ?>

            <tr>

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


    <br>


    <a
        href="pregledZaduzenja.php"
        class="dugme"
    >
        Nazad
    </a>


    <a
        href="../stampa/zapisnik.php?id=<?php
            echo $idZaduzenja;
        ?>"
        class="dugme"
        target="_blank"
    >
        Štampaj zapisnik
    </a>

</main>

</body>

</html>