<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


// ---------------------------------------------------------
// ID
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


$id =
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
    $kontroler->pronadjiPoId($id);


if (!$zaduzenje) {

    header(
        "Location: pregledZaduzenja.php"
    );

    exit;
}


// ---------------------------------------------------------
// STAVKE I OPREMA
// ---------------------------------------------------------

$stavke =
    $kontroler->pronadjiStavke($id);

$svaOprema =
    $kontroler->pronadjiSvuOpremu();


// ---------------------------------------------------------
// GREŠKA
// ---------------------------------------------------------

$greska = "";


// ---------------------------------------------------------
// OBRADA FORME
// ---------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $podaci = array(

        "broj_zapisnika" =>
            trim(
                $_POST["broj_zapisnika"] ?? ""
            ),

        "datum" =>
            trim(
                $_POST["datum"] ?? ""
            ),

        "zaposleni" =>
            trim(
                $_POST["zaposleni"] ?? ""
            ),

        "odeljenje" =>
            trim(
                $_POST["odeljenje"] ?? ""
            ),

        "napomena" =>
            trim(
                $_POST["napomena"] ?? ""
            )

    );


    $stavkeZaIzmenu =
        isset($_POST["stavke"]) &&
        is_array($_POST["stavke"])
            ? $_POST["stavke"]
            : array();


    try {

        $kontroler->izmeni(
            $id,
            $podaci,
            $stavkeZaIzmenu
        );


        header(
            "Location: detaljiZaduzenja.php?id="
            . $id
        );

        exit;

    } catch (Exception $e) {

        $greska =
            $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Izmeni zaduženje
</title>

<link rel="stylesheet"
      href="../css/stil.css">

</head>


<body>

<?php require_once "zaglavlje.php"; ?>


<main class="sadrzaj">


    <h2>
        Izmeni zaduženje
    </h2>


    <?php if ($greska !== ""): ?>

        <div class="greska">

            <?php
            echo htmlspecialchars(
                $greska
            );
            ?>

        </div>

    <?php endif; ?>


    <form method="post">


        <label for="broj_zapisnika">
            Broj zapisnika
        </label>


        <input
            type="text"
            id="broj_zapisnika"
            name="broj_zapisnika"
            maxlength="20"
            value="<?php
                echo htmlspecialchars(
                    $zaduzenje
                        ->getBrojZapisnika()
                );
            ?>"
            required
        >


        <label for="datum">
            Datum
        </label>


        <input
            type="date"
            id="datum"
            name="datum"
            value="<?php
                echo htmlspecialchars(
                    $zaduzenje->getDatum()
                );
            ?>"
            required
        >


        <label for="zaposleni">
            Zaposleni
        </label>


        <input
            type="text"
            id="zaposleni"
            name="zaposleni"
            maxlength="100"
            value="<?php
                echo htmlspecialchars(
                    $zaduzenje
                        ->getZaposleni()
                );
            ?>"
            required
        >


        <label for="odeljenje">
            Odeljenje
        </label>


        <input
            type="text"
            id="odeljenje"
            name="odeljenje"
            maxlength="100"
            value="<?php
                echo htmlspecialchars(
                    $zaduzenje
                        ->getOdeljenje()
                );
            ?>"
            required
        >


        <label for="napomena">
            Napomena
        </label>


        <textarea
            id="napomena"
            name="napomena"
            maxlength="500"
        ><?php

        echo htmlspecialchars(
            $zaduzenje
                ->getNapomena()
        );

        ?></textarea>


        <h3>
            Oprema
        </h3>


        <div id="stavke">


        <?php foreach (
            $stavke as $indeks => $stavka
        ): ?>


            <?php
            $oprema =
                $stavka->getOprema();
            ?>


            <div class="stavka">


                <input
                    type="hidden"
                    name="stavke[<?php
                        echo $indeks;
                    ?>][id_stavke]"
                    value="<?php
                        echo $stavka
                            ->getIdStavke();
                    ?>"
                >


                <label>
                    Oprema
                </label>


                <select
                    name="stavke[<?php
                        echo $indeks;
                    ?>][id_opreme]"
                    required
                >


                    <?php foreach (
                        $svaOprema as $jednaOprema
                    ): ?>


                        <option
                            value="<?php
                                echo $jednaOprema
                                    ->getIdOpreme();
                            ?>"
                            <?php

                            if (
                                $oprema
                                    ->getIdOpreme()
                                ==
                                $jednaOprema
                                    ->getIdOpreme()
                            ) {

                                echo "selected";
                            }

                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $jednaOprema
                                    ->getNaziv()
                            );
                            ?>

                        </option>


                    <?php endforeach; ?>


                </select>


                <label>
                    Količina
                </label>


                <input
                    type="number"
                    min="1"
                    name="stavke[<?php
                        echo $indeks;
                    ?>][kolicina]"
                    value="<?php
                        echo htmlspecialchars(
                            $stavka
                                ->getKolicina()
                        );
                    ?>"
                    required
                >


                <label>
                    Stanje
                </label>


                <select
                    name="stavke[<?php
                        echo $indeks;
                    ?>][stanje]"
                    required
                >


                    <?php
                    $stanja = array(
                        "Novo",
                        "Polovno",
                        "Oštećeno"
                    );
                    ?>


                    <?php foreach (
                        $stanja as $stanje
                    ): ?>


                        <option
                            value="<?php
                                echo $stanje;
                            ?>"
                            <?php

                            if (
                                $stavka
                                    ->getStanje()
                                ===
                                $stanje
                            ) {

                                echo "selected";
                            }

                            ?>
                        >

                            <?php
                            echo $stanje;
                            ?>

                        </option>


                    <?php endforeach; ?>


                </select>


                <label>
                    Napomena
                </label>


                <textarea
                    name="stavke[<?php
                        echo $indeks;
                    ?>][napomena]"
                    maxlength="500"
                ><?php

                echo htmlspecialchars(
                    $stavka
                        ->getNapomena()
                );

                ?></textarea>


            </div>


        <?php endforeach; ?>


        </div>


        <button
            type="submit"
            class="dugme"
        >
            Sačuvaj izmene
        </button>


        <a
            href="detaljiZaduzenja.php?id=<?php
                echo $id;
            ?>"
            class="dugme"
        >
            Odustani
        </a>


    </form>


</main>


</body>

</html>