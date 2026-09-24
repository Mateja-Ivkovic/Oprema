<?php

require_once "BaznaTabela.php";
require_once "StavkaZaduzenja.php";
require_once "Oprema.php";

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
        parent::__construct(
            "zaduzenje",
            $konekcija
        );
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


    public function dodajStavku(
        StavkaZaduzenja $stavka
    ) {
        $this->stavke[] = $stavka;
    }


    public function getStavke()
    {
        return $this->stavke;
    }


    public function sacuvaj()
    {
        $upit = "
            INSERT INTO zaduzenje
            (
                broj_zapisnika,
                datum,
                zaposleni,
                odeljenje,
                napomena
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt =
            $this->konekcija->prepare($upit);

        $stmt->bind_param(
            "sssss",
            $this->brojZapisnika,
            $this->datum,
            $this->zaposleni,
            $this->odeljenje,
            $this->napomena
        );

        if (!$stmt->execute()) {
            throw new Exception(
                "Greška pri čuvanju zaduženja: "
                . $stmt->error
            );
        }

        $this->idZaduzenja =
            $stmt->insert_id;


        foreach ($this->stavke as $stavka) {

            $stavka->setIdZaduzenja(
                $this->idZaduzenja
            );

            $stavka->sacuvaj();
        }


        return $this->idZaduzenja;
    }


    public function pronadjiPoId($id)
    {
        $upit = "
            SELECT *
            FROM zaduzenje
            WHERE id_zaduzenja = ?
        ";

        $stmt =
            $this->konekcija->prepare($upit);

        $stmt->bind_param(
            "i",
            $id
        );

        $stmt->execute();

        $rezultat =
            $stmt->get_result();

        $red =
            $rezultat->fetch_assoc();


        if (!$red) {
            return null;
        }


        $zaduzenje =
            new Zaduzenje(
                $this->konekcija
            );


        $zaduzenje->setIdZaduzenja(
            $red["id_zaduzenja"]
        );

        $zaduzenje->setBrojZapisnika(
            $red["broj_zapisnika"]
        );

        $zaduzenje->setDatum(
            $red["datum"]
        );

        $zaduzenje->setZaposleni(
            $red["zaposleni"]
        );

        $zaduzenje->setOdeljenje(
            $red["odeljenje"]
        );

        $zaduzenje->setNapomena(
            $red["napomena"]
        );


        return $zaduzenje;
    }


    public function pronadjiStavke($id)
    {
        $upit = "
            SELECT
                s.*,
                o.naziv,
                o.proizvodjac
            FROM stavka_zaduzenja s
            INNER JOIN oprema o
                ON s.id_opreme = o.id_opreme
            WHERE s.id_zaduzenja = ?
        ";

        $stmt =
            $this->konekcija->prepare($upit);

        $stmt->bind_param(
            "i",
            $id
        );

        $stmt->execute();

        $rezultat =
            $stmt->get_result();

        $stavke =
            array();


        while (
            $red =
            $rezultat->fetch_assoc()
        ) {

            $oprema =
                new Oprema(
                    $this->konekcija
                );


            $oprema->setIdOpreme(
                $red["id_opreme"]
            );

            $oprema->setNaziv(
                $red["naziv"]
            );

            $oprema->setProizvodjac(
                $red["proizvodjac"]
            );


            $stavka =
                new StavkaZaduzenja(
                    $this->konekcija
                );


            $stavka->setIdStavke(
                $red["id_stavke"]
            );

            $stavka->setIdZaduzenja(
                $red["id_zaduzenja"]
            );

            $stavka->setKolicina(
                $red["kolicina"]
            );

            $stavka->setStanje(
                $red["stanje"]
            );

            $stavka->setNapomena(
                $red["napomena"]
            );

            $stavka->setOprema(
                $oprema
            );


            $stavke[] =
                $stavka;
        }


        return $stavke;
    }


    public function prikaziSvaZaduzenja()
    {
        $upit = "
            SELECT *
            FROM zaduzenje
            ORDER BY id_zaduzenja DESC
        ";

        $rezultat =
            $this->konekcija->query($upit);


        if (!$rezultat) {
            throw new Exception(
                "Greška pri učitavanju zaduženja."
            );
        }


        $zaduzenja =
            array();


        while (
            $red =
            $rezultat->fetch_assoc()
        ) {

            $zaduzenje =
                new Zaduzenje(
                    $this->konekcija
                );


            $zaduzenje->setIdZaduzenja(
                $red["id_zaduzenja"]
            );

            $zaduzenje->setBrojZapisnika(
                $red["broj_zapisnika"]
            );

            $zaduzenje->setDatum(
                $red["datum"]
            );

            $zaduzenje->setZaposleni(
                $red["zaposleni"]
            );

            $zaduzenje->setOdeljenje(
                $red["odeljenje"]
            );

            $zaduzenje->setNapomena(
                $red["napomena"]
            );


            $zaduzenja[] =
                $zaduzenje;
        }


        return $zaduzenja;
    }


    public function pronadjiPoFilterima(
        $brojZapisnika,
        $zaposleni,
        $odeljenje
    ) {
        $upit = "
            SELECT *
            FROM zaduzenje
            WHERE broj_zapisnika LIKE ?
            AND zaposleni LIKE ?
            AND odeljenje LIKE ?
            ORDER BY id_zaduzenja DESC
        ";


        $stmt =
            $this->konekcija->prepare($upit);


        $broj =
            "%" . $brojZapisnika . "%";


        $zaposleniFilter =
            "%" . $zaposleni . "%";


        $odeljenjeFilter =
            "%" . $odeljenje . "%";


        $stmt->bind_param(
            "sss",
            $broj,
            $zaposleniFilter,
            $odeljenjeFilter
        );


        $stmt->execute();


        $rezultat =
            $stmt->get_result();


        $zaduzenja =
            array();


        while (
            $red =
            $rezultat->fetch_assoc()
        ) {

            $zaduzenje =
                new Zaduzenje(
                    $this->konekcija
                );


            $zaduzenje->setIdZaduzenja(
                $red["id_zaduzenja"]
            );

            $zaduzenje->setBrojZapisnika(
                $red["broj_zapisnika"]
            );

            $zaduzenje->setDatum(
                $red["datum"]
            );

            $zaduzenje->setZaposleni(
                $red["zaposleni"]
            );

            $zaduzenje->setOdeljenje(
                $red["odeljenje"]
            );

            $zaduzenje->setNapomena(
                $red["napomena"]
            );


            $zaduzenja[] =
                $zaduzenje;
        }


        return $zaduzenja;
    }


    public function izmeni()
    {
        $upit = "
            UPDATE zaduzenje
            SET
                broj_zapisnika = ?,
                datum = ?,
                zaposleni = ?,
                odeljenje = ?,
                napomena = ?
            WHERE id_zaduzenja = ?
        ";


        $stmt =
            $this->konekcija->prepare($upit);


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
            throw new Exception(
                "Greška pri izmeni zaduženja: "
                . $stmt->error
            );
        }


        $stavkaModel =
            new StavkaZaduzenja(
                $this->konekcija
            );


        $stavkaModel->obrisiSveZaZaduzenje(
            $this->idZaduzenja
        );


        foreach ($this->stavke as $stavka) {

            $stavka->setIdZaduzenja(
                $this->idZaduzenja
            );

            $stavka->sacuvaj();
        }
    }


    public function obrisi($id)
    {
        $stavkaModel =
            new StavkaZaduzenja(
                $this->konekcija
            );


        $stavkaModel->obrisiSveZaZaduzenje(
            $id
        );


        $upit = "
            DELETE FROM zaduzenje
            WHERE id_zaduzenja = ?
        ";


        $stmt =
            $this->konekcija->prepare($upit);


        $stmt->bind_param(
            "i",
            $id
        );


        if (!$stmt->execute()) {
            throw new Exception(
                "Greška pri brisanju zaduženja: "
                . $stmt->error
            );
        }
    }


    public function prikaziIzPogleda()
    {
        $rezultat =
            $this->pronadjiIzPogleda(
                "pregled_zaduzenja"
            );


        $zaduzenja =
            array();


        while (
            $red =
            $rezultat->fetch_assoc()
        ) {

            $zaduzenje =
                new Zaduzenje(
                    $this->konekcija
                );


            $zaduzenje->setIdZaduzenja(
                $red["id_zaduzenja"]
            );

            $zaduzenje->setBrojZapisnika(
                $red["broj_zapisnika"]
            );

            $zaduzenje->setDatum(
                $red["datum"]
            );

            $zaduzenje->setZaposleni(
                $red["zaposleni"]
            );

            $zaduzenje->setOdeljenje(
                $red["odeljenje"]
            );


            $oprema =
                new Oprema(
                    $this->konekcija
                );


            $oprema->setNaziv(
                $red["oprema"]
            );

            $oprema->setProizvodjac(
                $red["proizvodjac"]
            );


            $stavka =
                new StavkaZaduzenja(
                    $this->konekcija
                );


            $stavka->setKolicina(
                $red["kolicina"]
            );

            $stavka->setStanje(
                $red["stanje"]
            );

            $stavka->setNapomena(
                $red["napomena_stavke"]
            );

            $stavka->setOprema(
                $oprema
            );


            $zaduzenje->dodajStavku(
                $stavka
            );


            $zaduzenja[] =
                $zaduzenje;
        }


        return $zaduzenja;
    }
}