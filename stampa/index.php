
<?php

session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Štampanje - Zaduživanje opreme</title>

    <link rel="stylesheet" href="../css/stil.css">
</head>

<body>

    <header class="zaglavlje">

        <h1>Zaduživanje službene opreme</h1>

        <nav>
            <a href="../stranice/pocetna.php">Početna</a>
            <a href="../stranice/novoZaduzenje.php">Novo zaduženje</a>
            <a href="../stranice/pregledZaduzenja.php">Pregled zaduženja</a>
            <a href="index.php">Štampanje</a>
            <a href="../sesija/odjava.php">Odjava</a>
        </nav>

    </header>

    <main class="sadrzaj">

        <h2>Štampanje</h2>

        <p>Izaberite šta želite da štampate:</p>

        <div class="meni-kartice">

            <div class="kartica">

                <h3>Sva zaduženja</h3>

                <p>
                    Štampanje spiska svih evidentiranih zaduženja.
                </p>

                <a href="svaZaduzenja.php" target="_blank">
                    Štampaj sva zaduženja
                </a>

            </div>

            <div class="kartica">

                <h3>Pregled zaduženja</h3>

                <p>
                    Izaberite konkretno zaduženje iz pregleda
                    i odštampajte zapisnik.
                </p>

                <a href="../stranice/pregledZaduzenja.php">
                    Izaberi zaduženje
                </a>

            </div>

        </div>

    </main>

</body>
</html>

