<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


// ---------------------------------------------------------
// FILTERI
// ---------------------------------------------------------

$brojZapisnika =
    trim(
        $_GET["broj_zapisnika"] ?? ""
    );

$zaposleni =
    trim(
        $_GET["zaposleni"] ?? ""
    );

$odeljenje =
    trim(
        $_GET["odeljenje"] ?? ""
    );


// ---------------------------------------------------------
// KONTROLER
// ---------------------------------------------------------

$kontroler =
    new ZaduzenjeKontroler();


// ---------------------------------------------------------
// PODACI
// ---------------------------------------------------------

if (
    $brojZapisnika === "" &&
    $zaposleni === "" &&
    $odeljenje === ""
) {

    $zaduzenja =
        $kontroler->prikaziSvaZaduzenja();

} else {

    $zaduzenja =
        $kontroler->pronadjiPoFilterima(
            $brojZapisnika,
            $zaposleni,
            $odeljenje
        );
}

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Pregled zaduženja
</title>

<link rel="stylesheet"
      href="../css/stil.css">

</head>

<body>

<?php require_once "zaglavlje.php"; ?>


<main class="sadrzaj">


    <h2>
        Pregled zaduženja
    </h2>


    <form
        method="get"
        class="filter-form"
    >


        <label>
            Broj zapisnika
        </label>


        <input
            type="text"
            name="broj_zapisnika"
            value="<?php
                echo htmlspecialchars(
                    $brojZapisnika
                );
            ?>"
        >


        <label>
            Zaposleni
        </label>


        <input
            type="text"
            name="zaposleni"
            value="<?php
                echo htmlspecialchars(
                    $zaposleni
                );
            ?>"
        >


        <label>
            Odeljenje
        </label>


        <input
            type="text"
            name="odeljenje"
            value="<?php
                echo htmlspecialchars(
                    $odeljenje
                );
            ?>"
        >


        <button
            type="submit"
            class="dugme"
        >
            Filtriraj
        </button>


        <a
            href="pregledZaduzenja.php"
            class="dugme"
        >
            Poništi filter
        </a>


    </form>


    <table class="tabela">


        <thead>

            <tr>

                <th>Broj zapisnika</th>
                <th>Datum</th>
                <th>Zaposleni</th>
                <th>Odeljenje</th>
                <th>Napomena</th>
                <th>Akcije</th>

            </tr>

        </thead>


        <tbody>


        <?php if (!empty($zaduzenja)): ?>


            <?php foreach (
                $zaduzenja as $zaduzenje
            ): ?>


                <tr>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $zaduzenje
                                ->getBrojZapisnika()
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $zaduzenje
                                ->getDatum()
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $zaduzenje
                                ->getZaposleni()
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $zaduzenje
                                ->getOdeljenje()
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $zaduzenje
                                ->getNapomena()
                        );
                        ?>
                    </td>


                    <td>


                        <a
                            class="dugme"
                            href="detaljiZaduzenja.php?id=<?php
                                echo $zaduzenje
                                    ->getIdZaduzenja();
                            ?>"
                        >
                            Detalji
                        </a>


                        <a
                            class="dugme"
                            href="izmeniZaduzenje.php?id=<?php
                                echo $zaduzenje
                                    ->getIdZaduzenja();
                            ?>"
                        >
                            Izmeni
                        </a>


                        <a
                            class="dugme"
                            href="obrisiZaduzenje.php?id=<?php
                                echo $zaduzenje
                                    ->getIdZaduzenja();
                            ?>"
                            onclick="return confirm('Da li ste sigurni da želite da obrišete ovo zaduženje?');"
                        >
                            Obriši
                        </a>


                    </td>


                </tr>


            <?php endforeach; ?>


        <?php else: ?>


            <tr>

                <td colspan="6">
                    Nema rezultata.
                </td>

            </tr>


        <?php endif; ?>


        </tbody>


    </table>


</main>

</body>

</html>