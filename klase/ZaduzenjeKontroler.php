<?php

require_once "Zaduzenje.php";
require_once "Oprema.php";
require_once "BaznaKonekcija.php";
require_once "BaznaTransakcija.php";

class ZaduzenjeKontroler
{
    private $konekcija;
    private $zaduzenje;
    private $oprema;

    public function __construct()
    {
        $baznaKonekcija =
            new BaznaKonekcija();

        $this->konekcija =
            $baznaKonekcija->getKonekcija();

        $this->zaduzenje =
            new Zaduzenje(
                $this->konekcija
            );

        $this->oprema =
            new Oprema(
                $this->konekcija
            );
    }

    public function prikaziSvaZaduzenja()
    {
        return $this->zaduzenje
            ->prikaziSvaZaduzenja();
    }

    public function pronadjiPoId($id)
    {
        return $this->zaduzenje
            ->pronadjiPoId($id);
    }

    public function pronadjiStavke($id)
    {
        return $this->zaduzenje
            ->pronadjiStavke($id);
    }

    public function pronadjiPoFilterima(
        $brojZapisnika,
        $zaposleni,
        $odeljenje
    ) {
        return $this->zaduzenje
            ->pronadjiPoFilterima(
                $brojZapisnika,
                $zaposleni,
                $odeljenje
            );
    }

    public function pronadjiSvuOpremu()
    {
        $rezultat =
            $this->oprema->pronadjiSve();

        $oprema = array();

        while ($red = $rezultat->fetch_assoc()) {

            $jednaOprema =
                new Oprema(
                    $this->konekcija
                );

            $jednaOprema->setIdOpreme(
                $red["id_opreme"]
            );

            $jednaOprema->setNaziv(
                $red["naziv"]
            );

            $jednaOprema->setProizvodjac(
                $red["proizvodjac"]
            );

            $oprema[] =
                $jednaOprema;
        }

        return $oprema;
    }

    public function sacuvaj(
        $podaci,
        $stavke
    ) {
        $transakcija =
            new BaznaTransakcija(
                $this->konekcija
            );

        try {

            $transakcija
                ->zapocniTransakciju();

            $zaduzenje =
                new Zaduzenje(
                    $this->konekcija
                );

            $this->popuniZaduzenje(
                $zaduzenje,
                $podaci
            );

            $this->dodajStavke(
                $zaduzenje,
                $stavke
            );

            $id =
                $zaduzenje->sacuvaj();

            $transakcija
                ->potvrdiTransakciju();

            return $id;

        } catch (Exception $e) {

            $transakcija
                ->ponistiTransakciju();

            throw $e;
        }
    }

    public function izmeni(
        $id,
        $podaci,
        $stavke
    ) {
        $transakcija =
            new BaznaTransakcija(
                $this->konekcija
            );

        try {

            $transakcija
                ->zapocniTransakciju();

            $zaduzenje =
                $this->zaduzenje
                    ->pronadjiPoId($id);

            if (!$zaduzenje) {
                throw new Exception(
                    "Zaduženje nije pronađeno."
                );
            }

            $this->popuniZaduzenje(
                $zaduzenje,
                $podaci
            );

            $this->dodajStavke(
                $zaduzenje,
                $stavke
            );

            $zaduzenje->izmeni();

            $transakcija
                ->potvrdiTransakciju();

        } catch (Exception $e) {

            $transakcija
                ->ponistiTransakciju();

            throw $e;
        }
    }

    private function popuniZaduzenje(
        Zaduzenje $zaduzenje,
        $podaci
    ) {
        $zaduzenje->setBrojZapisnika(
            $podaci["broj_zapisnika"] ?? ""
        );

        $zaduzenje->setDatum(
            $podaci["datum"] ?? ""
        );

        $zaduzenje->setZaposleni(
            $podaci["zaposleni"] ?? ""
        );

        $zaduzenje->setOdeljenje(
            $podaci["odeljenje"] ?? ""
        );

        $zaduzenje->setNapomena(
            $podaci["napomena"] ?? ""
        );
    }

    private function dodajStavke(
        Zaduzenje $zaduzenje,
        $stavke
    ) {
        foreach ($stavke as $podaci) {

            if (
                !isset($podaci["id_opreme"]) ||
                $podaci["id_opreme"] === ""
            ) {
                continue;
            }

            $oprema =
                new Oprema(
                    $this->konekcija
                );

            $oprema->setIdOpreme(
                (int)$podaci["id_opreme"]
            );

            $stavka =
                new StavkaZaduzenja(
                    $this->konekcija
                );

            if (isset($podaci["id_stavke"])) {
                $stavka->setIdStavke(
                    (int)$podaci["id_stavke"]
                );
            }

            $stavka->setKolicina(
                (int)($podaci["kolicina"] ?? 1)
            );

            $stavka->setStanje(
                $podaci["stanje"] ?? "Novo"
            );

            $stavka->setNapomena(
                $podaci["napomena"] ?? ""
            );

            $stavka->setOprema($oprema);

            $zaduzenje
                ->dodajStavku($stavka);
        }
    }

    public function obrisi($id)
    {
        return $this->zaduzenje
            ->obrisi($id);
    }

    public function prikaziIzPogleda()
    {
        return $this->zaduzenje
            ->prikaziIzPogleda();
    }
}