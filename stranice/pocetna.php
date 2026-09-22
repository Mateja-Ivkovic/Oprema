<?php

session_start();



?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Početna - Zaduživanje opreme</title>

    <link rel="stylesheet" href="../css/stil.css">

</head>

<body>

    <?php require_once "zaglavlje.php"; ?>

    <main class="sadrzaj">

        <h2>Početna stranica</h2>

        <p>
            Dobrodošli u sistem za evidentiranje zaduživanja
            službene opreme zaposlenima.
        </p>

        <p>
            Prijavljeni korisnik:
            <strong><?php echo htmlspecialchars($_SESSION["korisnik"]); ?></strong>
        </p>

        <div class="meni-kartice">

            <div class="kartica">

                <h3>Novo zaduženje</h3>

                <p>
                    Unos novog zapisnika o zaduživanju službene opreme.
                </p>

                <a href="novoZaduzenje.php">
                    Unesi zaduženje
                </a>

            </div>

            <div class="kartica">

                <h3>Pregled zaduženja</h3>

                <p>
                    Pregled svih evidentiranih zaduženja.
                </p>

                <a href="pregledZaduzenja.php">
                    Pregledaj zaduženja
                </a>

            </div>

        </div>

    </main>

</body>

</html>