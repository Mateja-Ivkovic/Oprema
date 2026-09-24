<?php

require_once "BaznaKonekcija.php";

class BaznaTabela extends BaznaKonekcija
{
    protected $nazivTabele;

    public function __construct(
        $nazivTabele,
        $konekcija = null
    ) {
        parent::__construct($konekcija);

        $this->nazivTabele = $nazivTabele;
    }

    public function pronadjiSve()
    {
        $upit =
            "SELECT * FROM "
            . $this->nazivTabele;

        $rezultat =
            $this->konekcija->query($upit);

        if (!$rezultat) {
            throw new Exception(
                "Greška pri izvršavanju upita: "
                . $this->konekcija->error
            );
        }

        return $rezultat;
    }

    public function izvrsiProceduru($nazivProcedure)
    {
        $rezultat =
            $this->konekcija->query(
                "CALL " . $nazivProcedure . "()"
            );

        if (!$rezultat) {
            throw new Exception(
                "Greška pri izvršavanju procedure: "
                . $this->konekcija->error
            );
        }

        return $rezultat;
    }

    public function pronadjiIzPogleda($nazivPogleda)
    {
        $upit =
            "SELECT * FROM "
            . $nazivPogleda;

        $rezultat =
            $this->konekcija->query($upit);

        if (!$rezultat) {
            throw new Exception(
                "Greška pri čitanju pogleda: "
                . $this->konekcija->error
            );
        }

        return $rezultat;
    }
}