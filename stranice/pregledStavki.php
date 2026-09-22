
<?php
session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../klase/Zaduzenje.php";

$zaduzenje = new Zaduzenje();
$rezultat = $zaduzenje->prikaziIzPogleda();
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Pregled stavki</title>
    <link rel="stylesheet" href="../css/stil.css">
</head>

<body>

<?php require_once "zaglavlje.php"; ?>

<div class="sadrzaj">
    <h2>Pregled stavki zaduženja</h2>

    <table class="tabela">
        <tr>
            <th>Broj zapisnika</th>
            <th>Datum</th>
            <th>Zaposleni</th>
            <th>Odeljenje</th>
            <th>Oprema</th>
            <th>Proizvođač</th>
            <th>Količina</th>
            <th>Stanje</th>
            <th>Napomena</th>
        </tr>

        <?php while ($red = $rezultat->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($red["broj_zapisnika"]); ?></td>
                <td><?php echo htmlspecialchars($red["datum"]); ?></td>
                <td><?php echo htmlspecialchars($red["zaposleni"]); ?></td>
                <td><?php echo htmlspecialchars($red["odeljenje"]); ?></td>
                <td><?php echo htmlspecialchars($red["oprema"]); ?></td>
                <td><?php echo htmlspecialchars($red["proizvodjac"]); ?></td>
                <td><?php echo htmlspecialchars($red["kolicina"]); ?></td>
                <td><?php echo htmlspecialchars($red["stanje"]); ?></td>
                <td><?php echo htmlspecialchars($red["napomena_stavke"]); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>

