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

    public function pronadjiPoKorisnickomImenu(
        $korisnickoIme
    ) {
        $upit = "
            SELECT *
            FROM korisnik
            WHERE korisnicko_ime = ?
        ";

        $stmt =
            $this->konekcija->prepare($upit);

        $stmt->bind_param(
            "s",
            $korisnickoIme
        );

        $stmt->execute();

        $rezultat =
            $stmt->get_result();

        return $rezultat->fetch_assoc();
    }

    public function proveriPrijavu(
        $korisnickoIme,
        $lozinka
    ) {
        $korisnik =
            $this->pronadjiPoKorisnickomImenu(
                $korisnickoIme
            );

        if (!$korisnik) {
            return false;
        }

        if ($lozinka === $korisnik["lozinka"]) {
            return $korisnik;
        }

        return false;
    }
}