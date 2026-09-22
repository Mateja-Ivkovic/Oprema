```php
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
require_once "../klase/BaznaTransakcija.php";
require_once "../klase/Oprema.php";


// =====================================================
// PROMENLJIVE ZA PORUKE I GREŠKE
// =====================================================

$poruka = "";
$greske = array();


// =====================================================
// OBRADA FORME NAKON KLIKA NA "SAČUVAJ"
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =================================================
    // PREUZIMANJE PODATAKA IZ FORME
    // =================================================

    $brojZapisnika = trim($_POST["broj_zapisnika"] ?? "");
    $datum = $_POST["datum"] ?? "";
    $zaposleni = trim($_POST["zaposleni"] ?? "");
    $odeljenje = trim($_POST["odeljenje"] ?? "");
    $napomena = trim($_POST["napomena"] ?? "");


    // =================================================
    // VALIDACIJA BROJA ZAPISNIKA
    // =================================================

    if ($brojZapisnika == "") {

        $greske[] = "Broj zapisnika je obavezan.";

    } elseif (strlen($brojZapisnika) > 20) {

        $greske[] =
            "Broj zapisnika može imati najviše 20 karaktera.";
    }


    // =================================================
    // VALIDACIJA DATUMA
    // =================================================

    if ($datum == "") {

        $greske[] = "Datum je obavezan.";

    } else {

        $datumObjekat = DateTime::createFromFormat(
            "Y-m-d",
            $datum
        );

        if (
            !$datumObjekat ||
            $datumObjekat->format("Y-m-d") != $datum
        ) {

            $greske[] = "Datum nije ispravan.";
        }
    }


    // =================================================
    // VALIDACIJA ZAPOSLENOG
    // =================================================

    if ($zaposleni == "") {

        $greske[] = "Ime zaposlenog je obavezno.";

    } elseif (strlen($zaposleni) > 100) {

        $greske[] =
            "Ime zaposlenog može imati najviše 100 karaktera.";
    }


    // =================================================
    // VALIDACIJA ODELJENJA
    // =================================================

    if ($odeljenje == "") {

        $greske[] = "Odeljenje je obavezno.";

    } elseif (strlen($odeljenje) > 100) {

        $greske[] =
            "Odeljenje može imati najviše 100 karaktera.";
    }


    // =================================================
    // VALIDACIJA GLAVNE NAPOMENE
    // =================================================

    if (strlen($napomena) > 500) {

        $greske[] =
            "Napomena može imati najviše 500 karaktera.";
    }


    // =================================================
    // PROVERA JEDINSTVENOSTI BROJA ZAPISNIKA
    // =================================================

    if ($brojZapisnika != "") {

        $proveraKonekcija = new BaznaKonekcija();

        $upitProvera = "
            SELECT id_zaduzenja
            FROM zaduzenje
            WHERE broj_zapisnika = ?
        ";

        $stmtProvera = $proveraKonekcija
            ->getKonekcija()
            ->prepare($upitProvera);

        if ($stmtProvera) {

            $stmtProvera->bind_param(
                "s",
                $brojZapisnika
            );

            $stmtProvera->execute();

            $rezultatProvera =
                $stmtProvera->get_result();

            if ($rezultatProvera->num_rows > 0) {

                $greske[] =
                    "Broj zapisnika već postoji.";
            }

            $stmtProvera->close();
        }
    }


    // =================================================
    // PROVERA DA LI POSTOJI BAR JEDNA STAVKA OPREME
    // =================================================

    if (
        !isset($_POST["id_opreme"]) ||
        !is_array($_POST["id_opreme"]) ||
        count($_POST["id_opreme"]) == 0
    ) {

        $greske[] =
            "Morate uneti najmanje jednu stavku opreme.";
    }


    // =================================================
    // VALIDACIJA STAVKI OPREME
    // =================================================

    if (
        isset($_POST["id_opreme"]) &&
        is_array($_POST["id_opreme"])
    ) {

        // ---------------------------------------------
        // DOZVOLJENA STANJA OPREME
        // ---------------------------------------------

        $dozvoljenaStanja = array(
            "Novo",
            "Polovno",
            "Oštećeno"
        );


        // ---------------------------------------------
        // PROLAZAK KROZ SVE STAVKE
        // ---------------------------------------------

        for (
            $i = 0;
            $i < count($_POST["id_opreme"]);
            $i++
        ) {

            $idOpreme =
                $_POST["id_opreme"][$i] ?? "";

            $kolicina =
                $_POST["kolicina"][$i] ?? "";

            $stanje =
                $_POST["stanje"][$i] ?? "";

            $napomenaStavke =
                trim(
                    $_POST["napomena_stavke"][$i] ?? ""
                );


            // -----------------------------------------
            // PROVERA ID-A OPREME
            // -----------------------------------------

            if (
                !is_numeric($idOpreme) ||
                (int)$idOpreme <= 0
            ) {

                $greske[] =
                    "Izabrana oprema nije ispravna.";
            }


            // -----------------------------------------
            // PROVERA KOLIČINE
            // -----------------------------------------

            if (
                !filter_var(
                    $kolicina,
                    FILTER_VALIDATE_INT
                ) ||
                (int)$kolicina <= 0
            ) {

                $greske[] =
                    "Količina mora biti ceo broj veći od 0.";
            }


            // -----------------------------------------
            // PROVERA STANJA OPREME
            // -----------------------------------------

            if (
                !in_array(
                    $stanje,
                    $dozvoljenaStanja
                )
            ) {

                $greske[] =
                    "Izabrano stanje opreme nije dozvoljeno.";
            }


            // -----------------------------------------
            // PROVERA NAPOMENE STAVKE
            // -----------------------------------------

            if (strlen($napomenaStavke) > 500) {

                $greske[] =
                    "Napomena stavke može imati najviše 500 karaktera.";
            }
        }
    }


    // =================================================
    // AKO NEMA GREŠAKA - ČUVANJE PODATAKA
    // =================================================

    if (count($greske) == 0) {

        // =============================================
        // PREUZIMANJE PODATAKA O STAVKAMA
        // =============================================

        $idOpreme = $_POST["id_opreme"];
        $kolicine = $_POST["kolicina"];
        $stanja = $_POST["stanje"];
        $napomeneStavki = $_POST["napomena_stavke"];


        // =============================================
        // KREIRANJE TRANSAKCIJE
        // =============================================

        $transakcija = new BaznaTransakcija();

        // Uzimamo ISTU konekciju koju koristi transakcija
        $konekcija = $transakcija->getKonekcija();


        // =============================================
        // KREIRANJE OBJEKTA ZADUŽENJA
        // Koristi istu konekciju kao transakcija
        // =============================================

        $zaduzenje = new Zaduzenje($konekcija);


        // =============================================
        // POSTAVLJANJE PODATAKA ZADUŽENJA
        // =============================================

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

            $transakcija->zapocniTransakciju();


            // =========================================
            // ČUVANJE GLAVNOG ZADUŽENJA
            // =========================================

            $idZaduzenja =
                $zaduzenje->sacuvaj();


            // =========================================
            // ČUVANJE SVIH STAVKI OPREME
            // =========================================

            for (
                $i = 0;
                $i < count($idOpreme);
                $i++
            ) {

                // -------------------------------------
                // KREIRANJE OBJEKTA OPREME
                // Koristi istu konekciju
                // -------------------------------------

                $opremaObjekat =
                    new Oprema($konekcija);

                $opremaObjekat->setIdOpreme(
                    $idOpreme[$i]
                );


                // -------------------------------------
                // KREIRANJE OBJEKTA STAVKE
                // Koristi istu konekciju
                // -------------------------------------

                $stavka =
                    new StavkaZaduzenja(
                        $konekcija
                    );


                // -------------------------------------
                // POSTAVLJANJE PODATAKA STAVKE
                // -------------------------------------

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
                // ČUVANJE STAVKE
                // -------------------------------------

                $stavka->sacuvaj();


                // -------------------------------------
                // KOMPOZICIJA
                // Zaduženje sadrži svoje stavke
                // -------------------------------------

                $zaduzenje->dodajStavku(
                    $stavka
                );
            }


            // =========================================
            // POTVRĐIVANJE TRANSAKCIJE
            // =========================================

            $transakcija->potvrdiTransakciju();


            // =========================================
            // PRELAZAK NA PREGLED ZADUŽENJA
            // =========================================

            header(
                "Location: pregledZaduzenja.php"
            );

            exit;


        } catch (Exception $greska) {

            // =========================================
            // AKO DOĐE DO GREŠKE -
            // PONIŠTAVAMO CELO ZADUŽENJE
            // =========================================

            $transakcija->ponistiTransakciju();

            $poruka =
                "Greška pri čuvanju zaduženja: "
                . $greska->getMessage();
        }
    }


    // =================================================
    // AKO POSTOJE GREŠKE - PRIKAZ GREŠAKA
    // =================================================

    if (count($greske) > 0) {

        $poruka =
            implode(
                "\n",
                $greske
            );
    }
}


// =====================================================
// UČITAVANJE SVE OPREME IZ BAZE
// =====================================================

$oprema = new Oprema();

$rezultatOprema =
    $oprema->pronadjiSve();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Novo zaduženje</title>

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
             NASLOV STRANICE
             ========================================= -->

        <h2>Novo zaduženje</h2>


        <!-- =========================================
             PRIKAZ GREŠAKA
             ========================================= -->

        <?php if (count($greske) > 0): ?>

            <div class="greska">

                <?php foreach ($greske as $greska): ?>

                    <p>
                        <?php
                        echo htmlspecialchars($greska);
                        ?>
                    </p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <!-- =========================================
             PRIKAZ GREŠKE TRANSAKCIJE
             ========================================= -->

        <?php if ($poruka != "" && count($greske) == 0): ?>

            <p class="greska">
                <?php
                echo htmlspecialchars($poruka);
                ?>
            </p>

        <?php endif; ?>


        <!-- =========================================
             FORMA ZA UNOS ZADUŽENJA
             ========================================= -->

        <form
            method="POST"
            action=""
            id="forma-zaduzenje"
        >


            <!-- =====================================
                 PODACI O GLAVNOM ZADUŽENJU
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
                required
                value="<?php
                    echo htmlspecialchars(
                        $_POST["broj_zapisnika"] ?? ""
                    );
                ?>"
            >


            <!-- DATUM -->

            <label for="datum">
                Datum:
            </label>

            <input
                type="date"
                id="datum"
                name="datum"
                required
                value="<?php
                    echo htmlspecialchars(
                        $_POST["datum"] ?? ""
                    );
                ?>"
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
                required
                value="<?php
                    echo htmlspecialchars(
                        $_POST["zaposleni"] ?? ""
                    );
                ?>"
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
                required
                value="<?php
                    echo htmlspecialchars(
                        $_POST["odeljenje"] ?? ""
                    );
                ?>"
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
                    $_POST["napomena"] ?? ""
                );
            ?></textarea>


            <!-- =====================================
                 STAVKE OPREME
                 ===================================== -->

            <h3>Oprema</h3>


            <div id="stavke">


                <!-- =================================
                     PRVA STAVKA OPREME
                     ================================= -->

                <div class="stavka">


                    <!-- IZBOR OPREME -->

                    <label>
                        Oprema:
                    </label>

                    <select
                        name="id_opreme[]"
                        required
                    >

                        <option value="">
                            -- Izaberite opremu --
                        </option>


                        <?php while (
                            $red =
                            $rezultatOprema->fetch_assoc()
                        ): ?>

                            <option
                                value="<?php
                                    echo $red["id_opreme"];
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $red["naziv"]
                                    . " - "
                                    . $red["proizvodjac"]
                                );

                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>


                    <!-- KOLIČINA -->

                    <label>
                        Količina:
                    </label>

                    <input
                        type="number"
                        name="kolicina[]"
                        min="1"
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

                        <option value="">
                            -- Izaberite stanje --
                        </option>

                        <option value="Novo">
                            Novo
                        </option>

                        <option value="Polovno">
                            Polovno
                        </option>

                        <option value="Oštećeno">
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
                    >

                </div>

            </div>


            <!-- =====================================
                 DODAVANJE NOVE STAVKE
                 ===================================== -->

            <button
                type="button"
                onclick="dodajStavku()"
            >
                + Dodaj opremu
            </button>


            <br>
            <br>


            <!-- =====================================
                 ČUVANJE CELOG ZADUŽENJA
                 ===================================== -->

            <button type="submit">
                Sačuvaj zaduženje
            </button>


        </form>

    </main>


    <!-- =============================================
         JAVASCRIPT
         ============================================= -->

    <script src="../js/zaduzenje.js"></script>

</body>

</html>
```
