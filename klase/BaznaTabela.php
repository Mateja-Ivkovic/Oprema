```php
<?php

require_once "BaznaKonekcija.php";


// =====================================================
// BAZNA KLASA ZA RAD SA TABELAMA
// =====================================================

class BaznaTabela extends BaznaKonekcija
{
    protected $nazivTabele;


    // =================================================
    // KONSTRUKTOR
    // =================================================

    public function __construct(
        $nazivTabele,
        $konekcija = null
    ) {

        parent::__construct($konekcija);

        $this->nazivTabele = $nazivTabele;
    }


    // =================================================
    // PRONALAŽENJE SVIH ZAPISA IZ TABELE
    // =================================================

    public function pronadjiSve()
    {
        $upit = "SELECT * FROM " . $this->nazivTabele;

        $rezultat = $this->konekcija->query($upit);

        if (!$rezultat) {

            die(
                "Greška pri izvršavanju upita: "
                . $this->konekcija->error
            );
        }

        return $rezultat;
    }


    // =================================================
    // IZVRŠAVANJE STORED PROCEDURE
    // =================================================

    public function izvrsiProceduru($nazivProcedure)
    {
        $rezultat = $this->konekcija->query(
            "CALL " . $nazivProcedure . "()"
        );

        if (!$rezultat) {

            die(
                "Greška pri izvršavanju procedure: "
                . $this->konekcija->error
            );
        }

        return $rezultat;
    }


    // =================================================
    // ČITANJE PODATAKA IZ VIEW-A
    // =================================================

    public function pronadjiIzPogleda($nazivPogleda)
    {
        $upit = "SELECT * FROM " . $nazivPogleda;

        $rezultat = $this->konekcija->query($upit);

        if (!$rezultat) {

            die(
                "Greška pri čitanju pogleda: "
                . $this->konekcija->error
            );
        }

        return $rezultat;
    }
}

?>
```
