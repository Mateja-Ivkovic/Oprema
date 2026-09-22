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

$konekcija = $zaduzenje->getKonekcija();

$upit = "DELETE FROM zaduzenje WHERE id_zaduzenja = ?";

$stmt = $konekcija->prepare($upit);

$stmt->bind_param("i", $idZaduzenja);

$stmt->execute();

$stmt->close();

header("Location: pregledZaduzenja.php");
exit;

?>