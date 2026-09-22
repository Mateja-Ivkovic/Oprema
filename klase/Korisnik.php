<?php

require_once "BaznaTabela.php";

class Korisnik extends BaznaTabela
{
    private $idKorisnika;
    private $korisnickoIme;
    private $lozinka;

   public function __construct($konekcija = null)
{
    parent::__construct(
        "korisnik",
        $konekcija
    );
}

    public function getIdKorisnika()
    {
        return $this->idKorisnika;
    }

    public function setIdKorisnika($idKorisnika)
    {
        $this->idKorisnika = $idKorisnika;
    }

    public function getKorisnickoIme()
    {
        return $this->korisnickoIme;
    }

    public function setKorisnickoIme($korisnickoIme)
    {
        $this->korisnickoIme = $korisnickoIme;
    }

    public function getLozinka()
    {
        return $this->lozinka;
    }

    public function setLozinka($lozinka)
    {
        $this->lozinka = $lozinka;
    }
}

?>