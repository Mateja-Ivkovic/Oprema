<?php

class BaznaKonekcija
{
    protected $konekcija;

    public function __construct($konekcija = null)
    {
        if ($konekcija !== null) {
            $this->konekcija = $konekcija;
            return;
        }

        $server = "localhost";
        $korisnik = "root";
        $lozinka = "";
        $baza = "zaduzivanje_opreme";

        $this->konekcija = new mysqli(
            $server,
            $korisnik,
            $lozinka,
            $baza
        );

        if ($this->konekcija->connect_error) {
            throw new Exception(
                "Greška pri povezivanju sa bazom: "
                . $this->konekcija->connect_error
            );
        }

        $this->konekcija->set_charset("utf8mb4");
    }

    public function getKonekcija()
    {
        return $this->konekcija;
    }
}