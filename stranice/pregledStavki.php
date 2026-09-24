<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";

$sesija =
    new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);

$kontroler =
    new ZaduzenjeKontroler();

$zaduzenja =
    $kontroler->prikaziIzPogleda();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Pregled stavki</title>

    <link rel="stylesheet"
          href="../css/stil.css">

</head>

<body>

<?php require_once "zaglavlje.php"; ?>

<main class="sadrzaj">

    <h2>
        Pregled stavki
    </h2>

    <table class="tabela">

        <thead>

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

        </thead>

        <tbody>

        <?php foreach ($zaduzenja as $zaduzenje): ?>

            <?php

            $stavke =
                $zaduzenje->getStavke();

            foreach (
                $stavke as $stavka
            ):

                $oprema =
                    $stavka->getOprema();

            ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars(
                            $zaduzenje->getBrojZapisnika()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $zaduzenje->getDatum()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $zaduzenje->getZaposleni()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $zaduzenje->getOdeljenje()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $oprema->getNaziv()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $oprema->getProizvodjac()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $stavka->getKolicina()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $stavka->getStanje()
                        ); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars(
                            $stavka->getNapomena()
                        ); ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endforeach; ?>

        </tbody>

    </table>

</main>

</body>

</html>