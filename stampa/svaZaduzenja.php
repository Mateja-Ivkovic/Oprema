<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


$kontroler = new ZaduzenjeKontroler();


$zaduzenja =
    $kontroler->prikaziSvaZaduzenja();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<title>
    Štampanje svih zaduženja
</title>

<style>

body {
    font-family: Arial, sans-serif;
    margin: 30px;
}

h1 {
    text-align: center;
}

.dugmad {
    margin-bottom: 20px;
}

button,
a {
    display: inline-block;
    padding: 10px 15px;
    margin-right: 5px;
    background-color: #3f6fb6;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    border: 1px solid #333;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #eeeeee;
}

@media print {

    .dugmad {
        display: none;
    }

    body {
        margin: 10mm;
    }

}

</style>

</head>

<body>

<div class="dugmad">

    <button onclick="window.print()">
        Štampaj
    </button>

    <a href="../stranice/pregledZaduzenja.php">
        Nazad
    </a>

</div>


<h1>
    Pregled svih zaduženja
</h1>


<table>

<thead>

<tr>

<th>Broj zapisnika</th>
<th>Datum</th>
<th>Zaposleni</th>
<th>Odeljenje</th>
<th>Napomena</th>

</tr>

</thead>


<tbody>

<?php if (!empty($zaduzenja)): ?>

    <?php foreach ($zaduzenja as $zaduzenje): ?>

        <tr>

            <td>
                <?php
                echo htmlspecialchars(
                    $zaduzenje->getBrojZapisnika()
                );
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $zaduzenje->getDatum()
                );
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $zaduzenje->getZaposleni()
                );
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $zaduzenje->getOdeljenje()
                );
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $zaduzenje->getNapomena()
                );
                ?>
            </td>

        </tr>

    <?php endforeach; ?>

<?php else: ?>

    <tr>

        <td colspan="5">
            Nema zaduženja.
        </td>

    </tr>

<?php endif; ?>

</tbody>

</table>

</body>

</html>