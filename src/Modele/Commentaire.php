<?php

class Commentaire {

    private int $id_commentaire;
    private string $desc_commentaire;
    private int $id_article;
    private int $id_uti;
    private string $prenom_uti;

    public function __construct(int $id_commentaire, string $desc_commentaire, int $id_article, int $id_uti, string $prenom_uti)
    {
        $this->id_commentaire = $id_commentaire;
        $this->desc_commentaire = $desc_commentaire;
        $this->id_article = $id_article;
        $this->id_uti = $id_uti;
        $this->prenom_uti = $prenom_uti;
    }




    /**
     * Get the value of id_commentaire
     */ 
    public function getId_commentaire()
    {
        return $this->id_commentaire;
    }

    /**
     * Set the value of id_commentaire
     *
     * @return  self
     */ 
    public function setId_commentaire($id_commentaire)
    {
        $this->id_commentaire = $id_commentaire;

        return $this;
    }

    /**
     * Get the value of id_article
     */ 
    public function getId_article()
    {
        return $this->id_article;
    }

    /**
     * Set the value of id_article
     *
     * @return  self
     */ 
    public function setId_article($id_article)
    {
        $this->id_article = $id_article;

        return $this;
    }

    /**
     * Get the value of desc_commentaire
     */ 
    public function getDesc_commentaire()
    {
        return $this->desc_commentaire;
    }

    /**
     * Set the value of desc_commentaire
     *
     * @return  self
     */ 
    public function setDesc_commentaire($desc_commentaire)
    {
        $this->desc_commentaire = $desc_commentaire;

        return $this;
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
     * Get the value of prenom_uti
     */ 
    public function getPrenom_uti()
    {
        return $this->prenom_uti;
    }

    /**
     * Set the value of prenom_uti
     *
     * @return  self
     */ 
    public function setPrenom_uti($prenom_uti)
    {
        $this->prenom_uti = $prenom_uti;

        return $this;
    }
}
