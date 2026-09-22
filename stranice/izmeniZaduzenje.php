
<?php

// =====================================================
// POKRETANJE SESIJE
// =====================================================

session_start();


// =====================================================
// PROVERA DA LI JE KORISNIK PRIJAVLJEN
// =====================================================

if (!isset($_SESSION["korisnik"])) {

    header("Location: ../index.php");
    exit;
}


// =====================================================
// UKLJUČIVANJE POTREBNIH KLASA
// =====================================================

require_once "../klase/Zaduzenje.php";
require_once "../klase/StavkaZaduzenja.php";
require_once "../klase/Oprema.php";
require_once "../klase/BaznaTransakcija.php";


// =====================================================
// PROVERA ID-A ZADUŽENJA
// =====================================================

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header("Location: pregledZaduzenja.php");
    exit;
}


$idZaduzenja = (int) $_GET["id"];


// =====================================================
// KREIRANJE OBJEKTA ZADUŽENJA
// =====================================================

$zaduzenje = new Zaduzenje();


// =====================================================
// UČITAVANJE PODATAKA ZADUŽENJA
// =====================================================

$podaci = $zaduzenje->pronadjiPoId(
    $idZaduzenja
);


if (!$podaci) {

    die("Zaduženje ne postoji.");
}


// =====================================================
// UČITAVANJE STAVKI ZADUŽENJA
// =====================================================

$stavkeRezultat =
    $zaduzenje->pronadjiStavke(
        $idZaduzenja
    );


$stavke = array();


while (
    $stavka =
    $stavkeRezultat->fetch_assoc()
) {

    $stavke[] = $stavka;
}


// =====================================================
// UČITAVANJE SVE OPREME
// =====================================================

$oprema = new Oprema();

$rezultatOprema =
    $oprema->pronadjiSve();


$svaOprema = array();


while (
    $red =
    $rezultatOprema->fetch_assoc()
) {

    $svaOprema[] = $red;
}


// =====================================================
// PROMENLJIVE ZA GREŠKE
// =====================================================

$poruka = "";

$greske = array();


// =====================================================
// OBRADA FORME
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
) {


    // =================================================
    // PREUZIMANJE PODATAKA
    // =================================================

    $brojZapisnika =
        trim(
            $_POST["broj_zapisnika"] ?? ""
        );

    $datum =
        $_POST["datum"] ?? "";

    $zaposleni =
        trim(
            $_POST["zaposleni"] ?? ""
        );

    $odeljenje =
        trim(
            $_POST["odeljenje"] ?? ""
        );

    $napomena =
        trim(
            $_POST["napomena"] ?? ""
        );


    // =================================================
    // VALIDACIJA GLAVNIH PODATAKA
    // =================================================


    // -----------------------------------------------
    // BROJ ZAPISNIKA
    // -----------------------------------------------

    if ($brojZapisnika == "") {

        $greske[] =
            "Broj zapisnika je obavezan.";

    } elseif (
        strlen($brojZapisnika) > 20
    ) {

        $greske[] =
            "Broj zapisnika može imati najviše 20 karaktera.";
    }


    // -----------------------------------------------
    // DATUM
    // -----------------------------------------------

    if ($datum == "") {

        $greske[] =
            "Datum je obavezan.";

    } else {

        $datumObjekat =
            DateTime::createFromFormat(
                "Y-m-d",
                $datum
            );

        if (
            !$datumObjekat ||
            $datumObjekat->format("Y-m-d")
            != $datum
        ) {

            $greske[] =
                "Datum nije ispravan.";
        }
    }


    // -----------------------------------------------
    // ZAPOSLENI
    // -----------------------------------------------

    if ($zaposleni == "") {

        $greske[] =
            "Ime zaposlenog je obavezno.";

    } elseif (
        strlen($zaposleni) > 100
    ) {

        $greske[] =
            "Ime zaposlenog može imati najviše 100 karaktera.";
    }


    // -----------------------------------------------
    // ODELJENJE
    // -----------------------------------------------

    if ($odeljenje == "") {

        $greske[] =
            "Odeljenje je obavezno.";

    } elseif (
        strlen($odeljenje) > 100
    ) {

        $greske[] =
            "Odeljenje može imati najviše 100 karaktera.";
    }


    // -----------------------------------------------
    // GLAVNA NAPOMENA
    // -----------------------------------------------

    if (
        strlen($napomena) > 500
    ) {

        $greske[] =
            "Napomena može imati najviše 500 karaktera.";
    }


    // =================================================
    // PROVERA JEDINSTVENOSTI BROJA ZAPISNIKA
    // =================================================

    if ($brojZapisnika != "") {

        $proveraKonekcija =
            new BaznaKonekcija();


        $upitProvera = "
            SELECT id_zaduzenja
            FROM zaduzenje
            WHERE broj_zapisnika = ?
            AND id_zaduzenja != ?
        ";


        $stmtProvera =
            $proveraKonekcija
            ->getKonekcija()
            ->prepare($upitProvera);


        if ($stmtProvera) {

            $stmtProvera->bind_param(
                "si",
                $brojZapisnika,
                $idZaduzenja
            );


            $stmtProvera->execute();


            $rezultatProvera =
                $stmtProvera->get_result();


            if (
                $rezultatProvera->num_rows > 0
            ) {

                $greske[] =
                    "Broj zapisnika već postoji.";
            }


            $stmtProvera->close();
        }
    }


    // =================================================
    // PROVERA STAVKI
    // =================================================

    if (
        !isset($_POST["id_stavke"]) ||
        !is_array($_POST["id_stavke"]) ||
        count($_POST["id_stavke"]) == 0
    ) {

        $greske[] =
            "Zaduženje mora imati najmanje jednu stavku.";
    }


    // =================================================
    // PREUZIMANJE STAVKI
    // =================================================

    if (
        isset($_POST["id_stavke"]) &&
        is_array($_POST["id_stavke"])
    ) {

        $idStavki =
            $_POST["id_stavke"];

        $idOpreme =
            $_POST["id_opreme"] ?? array();

        $kolicine =
            $_POST["kolicina"] ?? array();

        $stanja =
            $_POST["stanje"] ?? array();

        $napomeneStavki =
            $_POST["napomena_stavke"] ?? array();


        // ---------------------------------------------
        // DOZVOLJENA STANJA
        // ---------------------------------------------

        $dozvoljenaStanja =
            array(
                "Novo",
                "Polovno",
                "Oštećeno"
            );


        // ---------------------------------------------
        // PROLAZAK KROZ STAVKE
        // ---------------------------------------------

        for (
            $i = 0;
            $i < count($idStavki);
            $i++
        ) {


            // -----------------------------------------
            // ID STAVKE
            // -----------------------------------------

            if (
                !is_numeric(
                    $idStavki[$i]
                ) ||
                (int)$idStavki[$i] <= 0
            ) {

                $greske[] =
                    "ID stavke nije ispravan.";
            }


            // -----------------------------------------
            // ID OPREME
            // -----------------------------------------

            if (
                !isset($idOpreme[$i]) ||
                !is_numeric($idOpreme[$i]) ||
                (int)$idOpreme[$i] <= 0
            ) {

                $greske[] =
                    "Izabrana oprema nije ispravna.";
            }


            // -----------------------------------------
            // KOLIČINA
            // -----------------------------------------

            if (
                !isset($kolicine[$i]) ||
                !filter_var(
                    $kolicine[$i],
                    FILTER_VALIDATE_INT
                ) ||
                (int)$kolicine[$i] <= 0
            ) {

                $greske[] =
                    "Količina mora biti ceo broj veći od 0.";
            }


            // -----------------------------------------
            // STANJE
            // -----------------------------------------

            if (
                !isset($stanja[$i]) ||
                !in_array(
                    $stanja[$i],
                    $dozvoljenaStanja
                )
            ) {

                $greske[] =
                    "Izabrano stanje opreme nije dozvoljeno.";
            }


            // -----------------------------------------
            // NAPOMENA STAVKE
            // -----------------------------------------

            $napomenaStavke =
                trim(
                    $napomeneStavki[$i] ?? ""
                );


            if (
                strlen($napomenaStavke) > 500
            ) {

                $greske[] =
                    "Napomena stavke može imati najviše 500 karaktera.";
            }
        }
    }


    // =================================================
    // AKO NEMA GREŠAKA - IZVRŠAVAMO TRANSAKCIJU
    // =================================================

    if (count($greske) == 0) {


        // =============================================
        // KREIRANJE TRANSAKCIJE
        // =============================================

        $transakcija =
            new BaznaTransakcija();


        // =============================================
        // UZIMANJE ISTE KONEKCIJE
        // =============================================

        $konekcija =
            $transakcija->getKonekcija();


        // =============================================
        // KREIRANJE ZADUŽENJA SA ISTOM KONEKCIJOM
        // =============================================

        $zaduzenje =
            new Zaduzenje(
                $konekcija
            );


        // =============================================
        // POSTAVLJANJE PODATAKA ZADUŽENJA
        // =============================================

        $zaduzenje->setIdZaduzenja(
            $idZaduzenja
        );

        $zaduzenje->setBrojZapisnika(
            $brojZapisnika
        );

        $zaduzenje->setDatum(
            $datum
        );

        $zaduzenje->setZaposleni(
            $zaposleni
        );

        $zaduzenje->setOdeljenje(
            $odeljenje
        );

        $zaduzenje->setNapomena(
            $napomena
        );


        try {


            // =========================================
            // POČETAK TRANSAKCIJE
            // =========================================

            $transakcija
                ->zapocniTransakciju();


            // =========================================
            // IZMENA GLAVNOG ZADUŽENJA
            // =========================================

            $zaduzenje->izmeni();


            // =========================================
            // IZMENA SVIH STAVKI
            // =========================================

            for (
                $i = 0;
                $i < count($idStavki);
                $i++
            ) {


                // -------------------------------------
                // KREIRANJE OPREME
                // Koristi istu konekciju
                // -------------------------------------

                $opremaObjekat =
                    new Oprema(
                        $konekcija
                    );


                $opremaObjekat->setIdOpreme(
                    $idOpreme[$i]
                );


                // -------------------------------------
                // KREIRANJE STAVKE
                // Koristi istu konekciju
                // -------------------------------------

                $stavka =
                    new StavkaZaduzenja(
                        $konekcija
                    );


                // -------------------------------------
                // POSTAVLJANJE PODATAKA STAVKE
                // -------------------------------------

                $stavka->setIdStavke(
                    $idStavki[$i]
                );

                $stavka->setIdZaduzenja(
                    $idZaduzenja
                );

                $stavka->setKolicina(
                    $kolicine[$i]
                );

                $stavka->setStanje(
                    $stanja[$i]
                );

                $stavka->setNapomena(
                    $napomeneStavki[$i]
                );


                // -------------------------------------
                // ASOCIJACIJA SA OPREMOM
                // -------------------------------------

                $stavka->setOprema(
                    $opremaObjekat
                );


                // -------------------------------------
                // IZMENA STAVKE
                // -------------------------------------

                $stavka->izmeni();
            }


            // =========================================
            // POTVRĐIVANJE TRANSAKCIJE
            // =========================================

            $transakcija
                ->potvrdiTransakciju();


            // =========================================
            // PRELAZAK NA DETALJE
            // =========================================

            header(
                "Location: detaljiZaduzenja.php?id="
                . $idZaduzenja
            );

            exit;


        } catch (Exception $greska) {


            // =========================================
            // AKO DOĐE DO GREŠKE -
            // PONIŠTAVAMO SVE IZMENE
            // =========================================

            $transakcija
                ->ponistiTransakciju();


            $poruka =
                "Greška pri izmeni: "
                . $greska->getMessage();
        }
    }
}


// =====================================================
// PRIKAZ GREŠAKA
// =====================================================

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Izmena zaduženja</title>

    <link
        rel="stylesheet"
        href="../css/stil.css"
    >

</head>

<body>


    <!-- =============================================
         ZAGLAVLJE I NAVIGACIJA
         ============================================= -->

    <?php require_once "zaglavlje.php"; ?>


    <main class="sadrzaj">


        <!-- =========================================
             NASLOV
             ========================================= -->

        <h2>Izmena zaduženja</h2>


        <!-- =========================================
             PRIKAZ VALIDACIONIH GREŠAKA
             ========================================= -->

        <?php if (count($greske) > 0): ?>

            <div class="greska">

                <?php foreach ($greske as $greska): ?>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $greska
                        );
                        ?>
                    </p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <!-- =========================================
             PRIKAZ GREŠKE TRANSAKCIJE
             ========================================= -->

        <?php if (
            $poruka != "" &&
            count($greske) == 0
        ): ?>

            <p class="greska">

                <?php
                echo htmlspecialchars(
                    $poruka
                );
                ?>

            </p>

        <?php endif; ?>


        <!-- =========================================
             FORMA ZA IZMENU
             ========================================= -->

        <form method="POST">


            <!-- =====================================
                 PODACI O ZADUŽENJU
                 ===================================== -->

            <h3>Podaci o zaduženju</h3>


            <!-- BROJ ZAPISNIKA -->

            <label for="broj_zapisnika">
                Broj zapisnika:
            </label>

            <input
                type="text"
                id="broj_zapisnika"
                name="broj_zapisnika"
                maxlength="20"
                value="<?php

                    echo htmlspecialchars(
                        $_POST["broj_zapisnika"]
                        ?? $podaci["broj_zapisnika"]
                    );

                ?>"
                required
            >


            <!-- DATUM -->

            <label for="datum">
                Datum:
            </label>

            <input
                type="date"
                id="datum"
                name="datum"
                value="<?php

                    echo htmlspecialchars(
                        $_POST["datum"]
                        ?? $podaci["datum"]
                    );

                ?>"
                required
            >


            <!-- ZAPOSLENI -->

            <label for="zaposleni">
                Zaposleni:
            </label>

            <input
                type="text"
                id="zaposleni"
                name="zaposleni"
                maxlength="100"
                value="<?php

                    echo htmlspecialchars(
                        $_POST["zaposleni"]
                        ?? $podaci["zaposleni"]
                    );

                ?>"
                required
            >


            <!-- ODELJENJE -->

            <label for="odeljenje">
                Odeljenje:
            </label>

            <input
                type="text"
                id="odeljenje"
                name="odeljenje"
                maxlength="100"
                value="<?php

                    echo htmlspecialchars(
                        $_POST["odeljenje"]
                        ?? $podaci["odeljenje"]
                    );

                ?>"
                required
            >


            <!-- GLAVNA NAPOMENA -->

            <label for="napomena">
                Napomena:
            </label>

            <textarea
                id="napomena"
                name="napomena"
                maxlength="500"
            ><?php

                echo htmlspecialchars(
                    $_POST["napomena"]
                    ?? $podaci["napomena"]
                );

            ?></textarea>


            <!-- =====================================
                 STAVKE OPREME
                 ===================================== -->

            <h3>Zadužena oprema</h3>


            <?php foreach (
                $stavke as $stavka
            ): ?>

                <div class="stavka">


                    <!-- ID STAVKE -->

                    <input
                        type="hidden"
                        name="id_stavke[]"
                        value="<?php
                            echo $stavka["id_stavke"];
                        ?>"
                    >


                    <!-- OPREMA -->

                    <label>
                        Oprema:
                    </label>

                    <select
                        name="id_opreme[]"
                        required
                    >

                        <?php foreach (
                            $svaOprema
                            as $jednaOprema
                        ): ?>

                            <option
                                value="<?php
                                    echo $jednaOprema["id_opreme"];
                                ?>"
                                <?php

                                if (
                                    $jednaOprema["id_opreme"]
                                    ==
                                    $stavka["id_opreme"]
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >

                                <?php

                                echo htmlspecialchars(
                                    $jednaOprema["naziv"]
                                    . " - "
                                    . $jednaOprema["proizvodjac"]
                                );

                                ?>

                            </option>

                        <?php endforeach; ?>

                    </select>


                    <!-- KOLIČINA -->

                    <label>
                        Količina:
                    </label>

                    <input
                        type="number"
                        name="kolicina[]"
                        min="1"
                        value="<?php
                            echo htmlspecialchars(
                                $stavka["kolicina"]
                            );
                        ?>"
                        required
                    >


                    <!-- STANJE -->

                    <label>
                        Stanje:
                    </label>

                    <select
                        name="stanje[]"
                        required
                    >

                        <option
                            value="Novo"
                            <?php

                            if (
                                $stavka["stanje"]
                                == "Novo"
                            ) {

                                echo "selected";
                            }

                            ?>
                        >
                            Novo
                        </option>


                        <option
                            value="Polovno"
                            <?php

                            if (
                                $stavka["stanje"]
                                == "Polovno"
                            ) {

                                echo "selected";
                            }

                            ?>
                        >
                            Polovno
                        </option>


                        <option
                            value="Oštećeno"
                            <?php

                            if (
                                $stavka["stanje"]
                                == "Oštećeno"
                            ) {

                                echo "selected";
                            }

                            ?>
                        >
                            Oštećeno
                        </option>

                    </select>


                    <!-- NAPOMENA STAVKE -->

                    <label>
                        Napomena:
                    </label>

                    <input
                        type="text"
                        name="napomena_stavke[]"
                        maxlength="500"
                        value="<?php
                            echo htmlspecialchars(
                                $stavka["napomena"]
                            );
                        ?>"
                    >

                </div>

            <?php endforeach; ?>


            <br>


            <!-- =====================================
                 ČUVANJE IZMENA
                 ===================================== -->

            <button type="submit">
                Sačuvaj izmene
            </button>


        </form>

    </main>

</body>

</html>

