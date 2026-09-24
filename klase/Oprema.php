<?php

require_once "BaznaTabela.php";

class Oprema extends BaznaTabela
{
    private $idOpreme;
    private $naziv;
    private $proizvodjac;

    public function __construct($konekcija = null)
    {
        parent::__construct(
            "oprema",
            $konekcija
        );
    }

    public function getIdOpreme()
    {
        return $this->idOpreme;
    }

    public function setIdOpreme($idOpreme)
    {
        $this->idOpreme = $idOpreme;
    }

    public function getNaziv()
    {
        return $this->naziv;
    }

    public function setNaziv($naziv)
    {
        $this->naziv = $naziv;
    }

    public function getProizvodjac()
    {
        return $this->proizvodjac;
    }

    public function setProizvodjac($proizvodjac)
    {
        $this->proizvodjac = $proizvodjac;
    }

    public function toArray()
    {
        return array(
            "id_opreme" => $this->idOpreme,
            "naziv" => $this->naziv,
            "proizvodjac" => $this->proizvodjac
        );
    }
}