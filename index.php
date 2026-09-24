<?php

require_once "klase/Sesija.php";
require_once "klase/Korisnik.php";


$sesija = new Sesija();


$greska = "";


// ---------------------------------------------------------
// AKO JE KORISNIK VEĆ PRIJAVLJEN
// ---------------------------------------------------------

if (
    $sesija->postoji("korisnik")
) {

    header(
        "Location: stranice/pocetna.php"
    );

    exit;
}


// ---------------------------------------------------------
// PRIJAVA
// ---------------------------------------------------------

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
) {

    $korisnickoIme =
        trim(
            $_POST["korisnicko_ime"] ?? ""
        );


    $lozinka =
        $_POST["lozinka"] ?? "";


    $korisnik =
        new Korisnik();


    $podaci =
        $korisnik->proveriPrijavu(
            $korisnickoIme,
            $lozinka
        );


    if ($podaci) {

        $sesija->prijavi(
            $podaci["korisnicko_ime"]
        );


        header(
            "Location: stranice/pocetna.php"
        );

        exit;

    } else {

        $greska =
            "Pogrešno korisničko ime ili lozinka.";
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
    Prijava
</title>

<link rel="stylesheet"
      href="css/stil.css">

</head>

<body>


<main class="sadrzaj">


    <h2>
        Prijava
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


        <label for="korisnicko_ime">
            Korisničko ime
        </label>


        <input
            type="text"
            id="korisnicko_ime"
            name="korisnicko_ime"
            required
        >


        <label for="lozinka">
            Lozinka
        </label>


        <input
            type="password"
            id="lozinka"
            name="lozinka"
            required
        >


        <button
            type="submit"
            class="dugme"
        >
            Prijavi se
        </button>


    </form>


</main>


</body>

</html>