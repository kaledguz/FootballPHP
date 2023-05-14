<?php

class Article
{
    private int $id_article;
    private string $titre_article;
    private string $desc_article;
    private string $auteur_article;
    private string $image_article;

    public function __construct(int $id_article, string $titre_article, string $desc_article, string $auteur_article, string $image_article)
    {
        $this->id_article = $id_article;
        $this->titre_article = $titre_article;
        $this->desc_article = $desc_article;
        $this->auteur_article = $auteur_article;
        $this->image_article = $image_article;
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
     * Get the value of titre_article
     */ 
    public function getTitre_article()
    {
        return $this->titre_article;
    }

    /**
     * Set the value of titre_article
     *
     * @return  self
     */ 
    public function setTitre_article($titre_article)
    {
        $this->titre_article = $titre_article;

        return $this;
    }

    /**
     * Get the value of auteur_article
     */ 
    public function getAuteur_article()
    {
        return $this->auteur_article;
    }

    /**
     * Set the value of auteur_article
     *
     * @return  self
     */ 
    public function setAuteur_article($auteur_article)
    {
        $this->auteur_article = $auteur_article;

        return $this;
    }

    /**
     * Get the value of desc_article
     */ 
    public function getDesc_article()
    {
        return $this->desc_article;
    }

    /**
     * Set the value of desc_article
     *
     * @return  self
     */ 
    public function setDesc_article($desc_article)
    {
        $this->desc_article = $desc_article;

        return $this;
    }

    /**
     * Get the value of image_article
     */ 
    public function getImage_article()
    {
        return $this->image_article;
    }

    /**
     * Set the value of image_article
     *
     * @return  self
     */ 
    public function setImage_article($image_article)
    {
        $this->image_article = $image_article;

        return $this;
    }
}