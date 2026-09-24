<?php

require_once "../klase/Sesija.php";

$sesija =
    new Sesija();

$sesija->odjavi();

header(
    "Location: ../index.php"
);

exit;