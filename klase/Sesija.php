<?php

class Sesija
{
    public function __construct()
    {
        $this->pokreni();
    }


    public function pokreni()
    {
        if (
            session_status() ===
            PHP_SESSION_NONE
        ) {
            session_start();
        }
    }


    public function prijavi($korisnickoIme)
    {
        $_SESSION["korisnik"] =
            $korisnickoIme;
    }


    public function odjavi()
    {
        $_SESSION = array();

        if (
            ini_get("session.use_cookies")
        ) {

            $parametri =
                session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $parametri["path"],
                $parametri["domain"],
                $parametri["secure"],
                $parametri["httponly"]
            );
        }

        session_destroy();
    }


    public function postoji($naziv)
    {
        return isset(
            $_SESSION[$naziv]
        );
    }


    public function uzmi($naziv)
    {
        return $_SESSION[$naziv] ?? null;
    }


    public function postavi(
        $naziv,
        $vrednost
    ) {
        $_SESSION[$naziv] =
            $vrednost;
    }


    public function obrisi($naziv)
    {
        unset(
            $_SESSION[$naziv]
        );
    }


    public function getKorisnik()
    {
        return $this->uzmi(
            "korisnik"
        );
    }


    public function proveriDaLiJePrijavljen()
    {
        return $this->postoji(
            "korisnik"
        );
    }


    public function proveriPrijavu(
        $stranicaZaPreusmeravanje
    ) {

        if (
            !$this->postoji("korisnik")
        ) {

            header(
                "Location: "
                . $stranicaZaPreusmeravanje
            );

            exit;
        }

        return true;
    }


    public function preusmeriAkoJePrijavljen(
        $stranicaZaPreusmeravanje
    ) {

        if (
            $this->postoji("korisnik")
        ) {

            header(
                "Location: "
                . $stranicaZaPreusmeravanje
            );

            exit;
        }

        return false;
    }
}