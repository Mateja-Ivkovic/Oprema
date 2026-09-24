<?php

require_once "../klase/Sesija.php";
require_once "../klase/ZaduzenjeKontroler.php";


$sesija = new Sesija();

$sesija->proveriPrijavu(
    "../index.php"
);


// ---------------------------------------------------------
// PROVERA ID-A
// ---------------------------------------------------------

if (
    !isset($_GET["id"]) ||
    !is_numeric($_GET["id"])
) {

    header(
        "Location: pregledZaduzenja.php"
    );

    exit;
}


$id =
    (int) $_GET["id"];


try {

    $kontroler =
        new ZaduzenjeKontroler();


    $kontroler->obrisi(
        $id
    );


} catch (Exception $e) {

    die(
        "Greška pri brisanju: "
        .
        htmlspecialchars(
            $e->getMessage()
        )
    );
}


header(
    "Location: pregledZaduzenja.php"
);

exit;