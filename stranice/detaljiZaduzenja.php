<?php

session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../klase/Zaduzenje.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: pregledZaduzenja.php");
    exit;
}

$idZaduzenja = (int) $_GET["id"];

$zaduzenje = new Zaduzenje();

$podaci = $zaduzenje->pronadjiPoId($idZaduzenja);

if (!$podaci) {
    die("Zaduženje ne postoji.");
}

$stavke = $zaduzenje->pronadjiStavke($idZaduzenja);

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalji zaduženja</title>

    <link rel="stylesheet" href="../css/stil.css">

</head>

<body>

    <?php require_once "zaglavlje.php"; ?>

    <main class="sadrzaj">

        <h2>Detalji zaduženja</h2>

        <div class="detalji">

            <p>
                <strong>Broj zapisnika:</strong>
                <?php echo htmlspecialchars($podaci["broj_zapisnika"]); ?>
            </p>

            <p>
                <strong>Datum:</strong>
                <?php echo htmlspecialchars($podaci["datum"]); ?>
            </p>

            <p>
                <strong>Zaposleni:</strong>
                <?php echo htmlspecialchars($podaci["zaposleni"]); ?>
            </p>

            <p>
                <strong>Odeljenje:</strong>
                <?php echo htmlspecialchars($podaci["odeljenje"]); ?>
            </p>

            <p>
                <strong>Napomena:</strong>
                <?php echo htmlspecialchars($podaci["napomena"]); ?>
            </p>

        </div>


        <h3>Zadužena oprema</h3>

        <table class="tabela">

            <thead>

                <tr>

                    <th>Oprema</th>
                    <th>Proizvođač</th>
                    <th>Količina</th>
                    <th>Stanje</th>
                    <th>Napomena</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($stavka = $stavke->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($stavka["naziv"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["proizvodjac"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["kolicina"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["stanje"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($stavka["napomena"]); ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>


        <br>

        <a href="pregledZaduzenja.php">
            ← Nazad na pregled
        </a>

        <a href="../stampa/zapisnik.php?id=<?php echo $idZaduzenja; ?>"
   class="dugme"
   target="_blank">
    Štampaj zapisnik
</a>

    </main>

</body>

</html>