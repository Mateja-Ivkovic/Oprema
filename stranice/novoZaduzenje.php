<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


$kontroler =
    new ZaduzenjeKontroler();


$greska = "";


$podaci = array(

    "broj_zapisnika" => "",
    "datum" => "",
    "zaposleni" => "",
    "odeljenje" => "",
    "napomena" => ""

);


$stavke = array();


// ---------------------------------------------------------
// OBRADA FORME
// ---------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $podaci = array(

        "broj_zapisnika" =>
            trim(
                $_POST["broj_zapisnika"] ?? ""
            ),

        "datum" =>
            trim(
                $_POST["datum"] ?? ""
            ),

        "zaposleni" =>
            trim(
                $_POST["zaposleni"] ?? ""
            ),

        "odeljenje" =>
            trim(
                $_POST["odeljenje"] ?? ""
            ),

        "napomena" =>
            trim(
                $_POST["napomena"] ?? ""
            )

    );


    $stavke =
        isset($_POST["stavke"]) &&
        is_array($_POST["stavke"])
            ? $_POST["stavke"]
            : array();


    try {

        $id =
            $kontroler->sacuvaj(
                $podaci,
                $stavke
            );


        header(
            "Location: detaljiZaduzenja.php?id="
            . $id
        );

        exit;

    } catch (Exception $e) {

        $greska =
            $e->getMessage();
    }
}


// ---------------------------------------------------------
// OPREMA
// ---------------------------------------------------------

$svaOprema =
    $kontroler->pronadjiSvuOpremu();

?>

<!DOCTYPE html>
<html lang="sr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Novo zaduženje
</title>

<link rel="stylesheet"
      href="../css/stil.css">

</head>


<body>

<?php require_once "zaglavlje.php"; ?>


<main class="sadrzaj">


<h2>
    Novo zaduženje
</h2>


<?php if ($greska !== ""): ?>

<div class="greska">

    <?php
    echo htmlspecialchars(
        $greska
    );
    ?>

</div>

<?php endif; ?>


<form method="post">


<label>
    Broj zapisnika
</label>


<input
    type="text"
    name="broj_zapisnika"
    maxlength="20"
    value="<?php
        echo htmlspecialchars(
            $podaci["broj_zapisnika"]
        );
    ?>"
    required
>


<label>
    Datum
</label>


<input
    type="date"
    name="datum"
    value="<?php
        echo htmlspecialchars(
            $podaci["datum"]
        );
    ?>"
    required
>


<label>
    Zaposleni
</label>


<input
    type="text"
    name="zaposleni"
    maxlength="100"
    value="<?php
        echo htmlspecialchars(
            $podaci["zaposleni"]
        );
    ?>"
    required
>


<label>
    Odeljenje
</label>


<input
    type="text"
    name="odeljenje"
    maxlength="100"
    value="<?php
        echo htmlspecialchars(
            $podaci["odeljenje"]
        );
    ?>"
    required
>


<label>
    Napomena
</label>


<textarea
    name="napomena"
    maxlength="500"
><?php

echo htmlspecialchars(
    $podaci["napomena"]
);

?></textarea>


<h3>
    Oprema
</h3>


<div id="stavke">


<?php foreach (
    $stavke as $indeks => $stavka
): ?>


<div class="stavka">


<label>
    Oprema
</label>


<select
    name="stavke[<?php
        echo $indeks;
    ?>][id_opreme]"
    required
>


<option value="">
    -- Izaberite opremu --
</option>


<?php foreach (
    $svaOprema as $jednaOprema
): ?>


<option
    value="<?php
        echo $jednaOprema
            ->getIdOpreme();
    ?>"
>


<?php
echo htmlspecialchars(
    $jednaOprema
        ->getNaziv()
);
?>


</option>


<?php endforeach; ?>


</select>


<label>
    Količina
</label>


<input
    type="number"
    min="1"
    name="stavke[<?php
        echo $indeks;
    ?>][kolicina]"
    value="<?php
        echo htmlspecialchars(
            $stavka["kolicina"] ?? 1
        );
    ?>"
    required
>


<label>
    Stanje
</label>


<select
    name="stavke[<?php
        echo $indeks;
    ?>][stanje]"
    required
>

<option value="Novo">
    Novo
</option>

<option value="Polovno">
    Polovno
</option>

<option value="Oštećeno">
    Oštećeno
</option>

</select>


<label>
    Napomena
</label>


<textarea
    name="stavke[<?php
        echo $indeks;
    ?>][napomena]"
    maxlength="500"
></textarea>


</div>


<?php endforeach; ?>


</div>


<button
    type="button"
    class="dugme"
    id="dodaj-stavku"
>
    + Dodaj opremu
</button>


<button
    type="submit"
    class="dugme"
>
    Sačuvaj zaduženje
</button>


</form>


</main>


<script src="../js/zaduzenje.js"></script>

</body>

</html>