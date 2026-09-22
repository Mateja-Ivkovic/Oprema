<?php

require_once "BaznaTabela.php";
require_once "StavkaZaduzenja.php";

class Zaduzenje extends BaznaTabela
{
    private $idZaduzenja;
    private $brojZapisnika;
    private $datum;
    private $zaposleni;
    private $odeljenje;
    private $napomena;

    private $stavke = array();

    public function __construct($konekcija = null)
{
    parent::__construct("zaduzenje", $konekcija);
}

    public function getIdZaduzenja()
    {
        return $this->idZaduzenja;
    }

    public function setIdZaduzenja($idZaduzenja)
    {
        $this->idZaduzenja = $idZaduzenja;
    }

    public function getBrojZapisnika()
    {
        return $this->brojZapisnika;
    }

    public function setBrojZapisnika($brojZapisnika)
    {
        $this->brojZapisnika = $brojZapisnika;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum($datum)
    {
        $this->datum = $datum;
    }

    public function getZaposleni()
    {
        return $this->zaposleni;
    }

    public function setZaposleni($zaposleni)
    {
        $this->zaposleni = $zaposleni;
    }

    public function getOdeljenje()
    {
        return $this->odeljenje;
    }

    public function setOdeljenje($odeljenje)
    {
        $this->odeljenje = $odeljenje;
    }

    public function getNapomena()
    {
        return $this->napomena;
    }

    public function setNapomena($napomena)
    {
        $this->napomena = $napomena;
    }

    public function dodajStavku(StavkaZaduzenja $stavka)
    {
        $this->stavke[] = $stavka;
    }

    public function getStavke()
    {
        return $this->stavke;
    }
    public function sacuvaj()
{
    $upit = "INSERT INTO zaduzenje
             (broj_zapisnika, datum, zaposleni, odeljenje, napomena)
             VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->konekcija->prepare($upit);

    $stmt->bind_param(
        "sssss",
        $this->brojZapisnika,
        $this->datum,
        $this->zaposleni,
        $this->odeljenje,
        $this->napomena
    );

    if (!$stmt->execute()) {
        die("Greška pri čuvanju zaduženja: " . $stmt->error);
    }

    return $this->konekcija->insert_id;
}
public function prikaziSvaZaduzenja()
{
    return $this->izvrsiProceduru("prikazi_sva_zaduzenja");
}
public function pronadjiPoId($id)
{
    $upit = "SELECT * FROM zaduzenje WHERE id_zaduzenja = ?";

    $stmt = $this->konekcija->prepare($upit);

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $rezultat = $stmt->get_result();

    return $rezultat->fetch_assoc();
}

public function pronadjiStavke($id)
{
    $upit = "SELECT
                s.id_stavke,
                s.id_zaduzenja,
                s.kolicina,
                s.stanje,
                s.napomena,
                o.id_opreme,
                o.naziv,
                o.proizvodjac
             FROM stavka_zaduzenja s
             INNER JOIN oprema o
                ON s.id_opreme = o.id_opreme
             WHERE s.id_zaduzenja = ?";

    $stmt = $this->konekcija->prepare($upit);

    $stmt->bind_param("i", $id);

    $stmt->execute();

    return $stmt->get_result();
}
public function izmeni()
{
    $upit = "UPDATE zaduzenje
             SET broj_zapisnika = ?,
                 datum = ?,
                 zaposleni = ?,
                 odeljenje = ?,
                 napomena = ?
             WHERE id_zaduzenja = ?";

    $stmt = $this->konekcija->prepare($upit);

    $stmt->bind_param(
        "sssssi",
        $this->brojZapisnika,
        $this->datum,
        $this->zaposleni,
        $this->odeljenje,
        $this->napomena,
        $this->idZaduzenja
    );

    if (!$stmt->execute()) {
        die("Greška pri izmeni zaduženja: " . $stmt->error);
    }
}

public function prikaziIzPogleda()
{
    return $this->pronadjiIzPogleda("pregled_zaduzenja");
}



}

?>