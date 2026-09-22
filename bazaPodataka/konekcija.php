<?php

$server = "localhost";
$korisnik = "root";
$lozinka = "";
$baza = "zaduzivanje_opreme";

$konekcija = new mysqli($server, $korisnik, $lozinka, $baza);

if ($konekcija->connect_error) {
    die("Greška pri povezivanju sa bazom: " . $konekcija->connect_error);
}

$konekcija->set_charset("utf8mb4");

?>