<?php

session_start();

require_once "klase/Korisnik.php";

$poruka = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $korisnickoIme = trim($_POST["korisnicko_ime"]);
    $lozinka = $_POST["lozinka"];

    if ($korisnickoIme == "" || $lozinka == "") {

        $poruka = "Morate popuniti sva polja.";

    } else {

        $korisnik = new Korisnik();

        $upit = "SELECT * FROM korisnik WHERE korisnicko_ime = ?";

        $stmt = $korisnik->getKonekcija()->prepare($upit);

        $stmt->bind_param("s", $korisnickoIme);

        $stmt->execute();

        $rezultat = $stmt->get_result();

        if ($rezultat->num_rows == 1) {

            $podaci = $rezultat->fetch_assoc();

            if ($lozinka === $podaci["lozinka"]) {

                $_SESSION["korisnik"] = $podaci["korisnicko_ime"];

                header("Location: stranice/pocetna.php");
                exit;

            } else {

                $poruka = "Pogrešna lozinka.";
            }

        } else {

            $poruka = "Korisnik ne postoji.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Prijava - Zaduživanje opreme</title>

    <link rel="stylesheet" href="css/stil.css">

</head>

<body>

    <div class="login-okvir">

        <h1>Zaduživanje službene opreme</h1>

        <h2>Prijava korisnika</h2>

        <?php if ($poruka != ""): ?>

            <p class="greska">
                <?php echo htmlspecialchars($poruka); ?>
            </p>

        <?php endif; ?>

        <form method="POST" action="">

            <label for="korisnicko_ime">
                Korisničko ime:
            </label>

            <input
                type="text"
                id="korisnicko_ime"
                name="korisnicko_ime"
                maxlength="50"
                required
            >

            <label for="lozinka">
                Lozinka:
            </label>

            <input
                type="password"
                id="lozinka"
                name="lozinka"
                required
            >

            <button type="submit">
                Prijavi se
            </button>

        </form>

    </div>

</body>

</html>