<?php

require_once "../klase/Sesija.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Štampanje</title>

<link rel="stylesheet"
      href="../css/stil.css">

</head>

<body>

<header class="zaglavlje">

    <h1>
        Zaduživanje službene opreme
    </h1>

    <nav>

        <a href="../stranice/pocetna.php">
            Početna
        </a>

        <a href="../stranice/novoZaduzenje.php">
            Novo zaduženje
        </a>

        <a href="../stranice/pregledZaduzenja.php">
            Pregled zaduženja
        </a>

        <a href="index.php">
            Štampanje
        </a>

        <a href="../sesija/odjava.php">
            Odjava
        </a>

    </nav>

</header>


<main class="sadrzaj">

    <h2>
        Štampanje
    </h2>


    <div class="meni-kartice">

        <div class="kartica">

            <h3>
                Sva zaduženja
            </h3>

            <p>
                Prikažite i odštampajte sva zaduženja.
            </p>

            <a
                href="svaZaduzenja.php"
                class="dugme"
                target="_blank"
            >
                Sva zaduženja
            </a>

        </div>


        <div class="kartica">

            <h3>
                Pregled zaduženja
            </h3>

            <p>
                Filtrirajte zaduženja pre štampanja.
            </p>

            <a
                href="../stranice/pregledZaduzenja.php"
                class="dugme"
            >
                Pregled zaduženja
            </a>

        </div>

    </div>

</main>

</body>

</html>