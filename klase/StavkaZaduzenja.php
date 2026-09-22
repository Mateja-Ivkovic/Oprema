<?php

require_once "BaznaTabela.php";
require_once "Oprema.php";

class StavkaZaduzenja extends BaznaTabela
{
    private $idStavke;
    private $idZaduzenja;
    private $kolicina;
    private $stanje;
    private $napomena;

    private $oprema;

   public function __construct($konekcija = null)
{
    parent::__construct(
        "stavka_zaduzenja",
        $konekcija
    );
}
    public function getIdStavke()
    {
        return $this->idStavke;
    }

    public function setIdStavke($idStavke)
    {
        $this->idStavke = $idStavke;
    }

    public function getIdZaduzenja()
    {
        return $this->idZaduzenja;
    }

    public function setIdZaduzenja($idZaduzenja)
    {
        $this->idZaduzenja = $idZaduzenja;
    }

    public function getKolicina()
    {
        return $this->kolicina;
    }

    public function setKolicina($kolicina)
    {
        $this->kolicina = $kolicina;
    }

    public function getStanje()
    {
        return $this->stanje;
    }

    public function setStanje($stanje)
    {
        $this->stanje = $stanje;
    }

    public function getNapomena()
    {
        return $this->napomena;
    }

    public function setNapomena($napomena)
    {
        $this->napomena = $napomena;
    }

    public function getOprema()
    {
        return $this->oprema;
    }

    public function setOprema(Oprema $oprema)
    {
        $this->oprema = $oprema;
    }
    public function sacuvaj()
{
    $upit = "INSERT INTO stavka_zaduzenja
             (id_zaduzenja, id_opreme, kolicina, stanje, napomena)
             VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->konekcija->prepare($upit);

    $idZaduzenja = $this->idZaduzenja;
    $idOpreme = $this->oprema->getIdOpreme();
    $kolicina = $this->kolicina;
    $stanje = $this->stanje;
    $napomena = $this->napomena;

    $stmt->bind_param(
        "iiiss",
        $idZaduzenja,
        $idOpreme,
        $kolicina,
        $stanje,
        $napomena
    );

    if (!$stmt->execute()) {
        die("Greška pri čuvanju stavke: " . $stmt->error);
    }
}
public function izmeni()
{
    $upit = "UPDATE stavka_zaduzenja
             SET id_opreme = ?,
                 kolicina = ?,
                 stanje = ?,
                 napomena = ?
             WHERE id_stavke = ?";

    $stmt = $this->konekcija->prepare($upit);

    $idOpreme = $this->oprema->getIdOpreme();

    $stmt->bind_param(
        "iissi",
        $idOpreme,
        $this->kolicina,
        $this->stanje,
        $this->napomena,
        $this->idStavke
    );

    if (!$stmt->execute()) {
        die("Greška pri izmeni stavke: " . $stmt->error);
    }
}
}

?>