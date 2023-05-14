<?php

class Club {    
    private int $id_club;
    private string $nom_club;
    private string $ligue_club;  

    public function __construct(int $id_club, string $nom_club, string $ligue_club)
    {
        $this->id_club = $id_club;
        $this->nom_club = $nom_club;
        $this->ligue_club = $ligue_club;
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

    /**
     * Get the value of nom_club
     */ 
    public function getNom_club()
    {
        return $this->nom_club;
    }

    /**
     * Set the value of nom_club
     *
     * @return  self
     */ 
    public function setNom_club($nom_club)
    {
        $this->nom_club = $nom_club;

        return $this;
    }

    /**
     * Get the value of ligue_club
     */ 
    public function getLigue_club()
    {
        return $this->ligue_club;
    }

    /**
     * Set the value of ligue_club
     *
     * @return  self
     */ 
    public function setLigue_club($ligue_club)
    {
        $this->ligue_club = $ligue_club;

        return $this;
    }
}
?>