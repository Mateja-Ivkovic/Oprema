```php
<?php

// =====================================================
// BAZNA KLASA ZA POVEZIVANJE SA BAZOM
// =====================================================

class BaznaKonekcija
{
    protected $konekcija;


    // =================================================
    // KONSTRUKTOR
    // Ako je konekcija prosleđena, koristi nju.
    // Ako nije, pravi novu konekciju.
    // =================================================

    public function __construct($konekcija = null)
    {

        // ---------------------------------------------
        // KORIŠĆENJE POSTOJEĆE KONEKCIJE
        // ---------------------------------------------

        if ($konekcija !== null) {

            $this->konekcija = $konekcija;

            return;
        }


        // ---------------------------------------------
        // PODACI ZA POVEZIVANJE SA BAZOM
        // ---------------------------------------------

        $server = "localhost";
        $korisnik = "root";
        $lozinka = "";
        $baza = "zaduzivanje_opreme";


        // ---------------------------------------------
        // KREIRANJE KONEKCIJE
        // ---------------------------------------------

        $this->konekcija = new mysqli(
            $server,
            $korisnik,
            $lozinka,
            $baza
        );


        // ---------------------------------------------
        // PROVERA KONEKCIJE
        // ---------------------------------------------

        if ($this->konekcija->connect_error) {

            die(
                "Greška pri povezivanju sa bazom: "
                . $this->konekcija->connect_error
            );
        }


        // ---------------------------------------------
        // UTF-8 PODRŠKA
        // ---------------------------------------------

        $this->konekcija->set_charset("utf8mb4");
    }


    // =================================================
    // VRAĆANJE KONEKCIJE
    // =================================================

    public function getKonekcija()
    {
        return $this->konekcija;
    }
}

?>
```
