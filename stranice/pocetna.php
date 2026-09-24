<?php

require_once "../klase/Sesija.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


$korisnik =
    $sesija->getKorisnik();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Početna stranica
</title>

<link rel="stylesheet"
      href="../css/stil.css">

</head>

<body>

<?php require_once "zaglavlje.php"; ?>


<main class="sadrzaj">


    <h2>
        Početna stranica
    </h2>


    <p>
        Dobrodošli u sistem za
        zaduživanje službene opreme.
    </p>


    <p>

        Prijavljeni korisnik:

        <strong>

            <?php
            echo htmlspecialchars(
                $korisnik
            );
            ?>

        </strong>

    </p>


    <div class="meni-kartice">


        <div class="kartica">

            <h3>
                Novo zaduženje
            </h3>

            <p>
                Kreirajte novo zaduženje
                službene opreme.
            </p>

            <a
                href="novoZaduzenje.php"
                class="dugme"
            >
                Novo zaduženje
            </a>

        </div>


        <div class="kartica">

            <h3>
                Pregled zaduženja
            </h3>

            <p>
                Pregledajte postojeća
                zaduženja službene opreme.
            </p>

            <a
                href="pregledZaduzenja.php"
                class="dugme"
            >
                Pregled zaduženja
            </a>

        </div>


    </div>


</main>

</body>

</html>