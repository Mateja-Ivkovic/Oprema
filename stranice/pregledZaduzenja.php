<?php

session_start();

if (!isset($_SESSION["korisnik"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../klase/Zaduzenje.php";

$zaduzenje = new Zaduzenje();

$brojZapisnika = "";
$zaposleni = "";
$odeljenje = "";

if (isset($_GET["broj_zapisnika"])) {
    $brojZapisnika = trim($_GET["broj_zapisnika"]);
}

if (isset($_GET["zaposleni"])) {
    $zaposleni = trim($_GET["zaposleni"]);
}

if (isset($_GET["odeljenje"])) {
    $odeljenje = trim($_GET["odeljenje"]);
}

if ($brojZapisnika == "" && $zaposleni == "" && $odeljenje == "") {
    $rezultat = $zaduzenje->prikaziSvaZaduzenja();
}
else {

    $konekcija = $zaduzenje->getKonekcija();

    $upit = "SELECT
                id_zaduzenja,
                broj_zapisnika,
                datum,
                zaposleni,
                odeljenje,
                napomena
             FROM zaduzenje
             WHERE broj_zapisnika LIKE ?
             AND zaposleni LIKE ?
             AND odeljenje LIKE ?
             ORDER BY datum DESC";

    $stmt = $konekcija->prepare($upit);

    $brojFilter = "%" . $brojZapisnika . "%";
    $zaposleniFilter = "%" . $zaposleni . "%";
    $odeljenjeFilter = "%" . $odeljenje . "%";

    $stmt->bind_param(
        "sss",
        $brojFilter,
        $zaposleniFilter,
        $odeljenjeFilter
    );

    $stmt->execute();

    $rezultat = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="sr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pregled zaduženja</title>

    <link rel="stylesheet" href="../css/stil.css">

</head>

<body>

    <?php require_once "zaglavlje.php"; ?>

    <main class="sadrzaj">

        <h2>Pregled zaduženja</h2>


        <form method="GET" class="filter-form">

            <h3>Filter</h3>

            <label for="broj_zapisnika">
                Broj zapisnika:
            </label>

            <input
                type="text"
                id="broj_zapisnika"
                name="broj_zapisnika"
                value="<?php echo htmlspecialchars($brojZapisnika); ?>"
                maxlength="20"
            >


            <label for="zaposleni">
                Zaposleni:
            </label>

            <input
                type="text"
                id="zaposleni"
                name="zaposleni"
                value="<?php echo htmlspecialchars($zaposleni); ?>"
                maxlength="100"
            >


            <label for="odeljenje">
                Odeljenje:
            </label>

            <input
                type="text"
                id="odeljenje"
                name="odeljenje"
                value="<?php echo htmlspecialchars($odeljenje); ?>"
                maxlength="100"
            >


            <br><br>

            <button type="submit">
                Filtriraj
            </button>

            <a href="pregledZaduzenja.php" class="dugme">
                Poništi filter
            </a>

            <a
    class="dugme"
    target="_blank"
    href="../stampa/filtriranaZaduzenja.php?broj_zapisnika=<?php echo urlencode($brojZapisnika); ?>&zaposleni=<?php echo urlencode($zaposleni); ?>&odeljenje=<?php echo urlencode($odeljenje); ?>"
>
    Štampaj filtrirano
</a>
        </form>


        <table class="tabela">

            <thead>

                <tr>

                    <th>Broj zapisnika</th>
                    <th>Datum</th>
                    <th>Zaposleni</th>
                    <th>Odeljenje</th>
                    <th>Napomena</th>
                    <th>Akcije</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($rezultat->num_rows > 0): ?>

                    <?php while ($red = $rezultat->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $red["broj_zapisnika"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $red["datum"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $red["zaposleni"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $red["odeljenje"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $red["napomena"]
                                );
                                ?>
                            </td>

                            <td>

                                <a href="detaljiZaduzenja.php?id=<?php echo $red["id_zaduzenja"]; ?>">
                                    Detalji
                                </a>

                                |

                                <a href="izmeniZaduzenje.php?id=<?php echo $red["id_zaduzenja"]; ?>">
                                    Izmeni
                                </a>

                                |

                                <a
                                    href="obrisiZaduzenje.php?id=<?php echo $red["id_zaduzenja"]; ?>"
                                    onclick="return confirm('Da li ste sigurni da želite da obrišete ovo zaduženje?');"
                                >
                                    Obriši
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6">
                            Nema rezultata za zadati filter.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </main>

</body>

</html>