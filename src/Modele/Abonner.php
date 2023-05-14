<?php

class Abonner {
    private int $id_uti;
    private int $id_club;

    public function __construct(int $id_uti, int $id_club)
    {
        $this->id_uti = $id_uti;
        $this->id_club = $id_club;
    }



    /**
     * Get the value of id_uti
     */ 
    public function getId_uti()
    {
        return $this->id_uti;
    }

    /**
     * Set the value of id_uti
     *
     * @return  self
     */ 
    public function setId_uti($id_uti)
    {
        $this->id_uti = $id_uti;

        return $this;
    }

    /**
     * Get the value of id_club
     */ 
    public function getId_club()
    {
        return $this->id_club;
    }

    /**
     * Set the value of id_club
     *
     * @return  self
     */ 
    public function setId_club($id_club)
    {
        $this->id_club = $id_club;

        return $this;
    }
}