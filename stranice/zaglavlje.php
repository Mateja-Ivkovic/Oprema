<?php

require_once "../klase/Sesija.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);

?>

<header class="zaglavlje">

    <h1>
        Zaduživanje službene opreme
    </h1>


    <nav>

        <a href="pocetna.php">
            Početna
        </a>

        <a href="novoZaduzenje.php">
            Novo zaduženje
        </a>

        <a href="pregledZaduzenja.php">
            Pregled zaduženja
        </a>

        <a href="pregledStavki.php">
            Pregled stavki
        </a>

        <a href="../stampa/index.php">
            Štampanje
        </a>

        <a href="../sesija/odjava.php">
            Odjava
        </a>

    </nav>

</header>