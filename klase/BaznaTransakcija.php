<?php

require_once "BaznaKonekcija.php";

class BaznaTransakcija extends BaznaKonekcija
{
    public function zapocniTransakciju()
    {
        return $this->konekcija->begin_transaction();
    }

    public function potvrdiTransakciju()
    {
        return $this->konekcija->commit();
    }

    public function ponistiTransakciju()
    {
        return $this->konekcija->rollback();
    }
}