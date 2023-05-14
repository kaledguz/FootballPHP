<?php

class Utilisateur
{
    private int $id_utilisateur;
    private int $id_club_utilisateur;
    private string $nom_utilisateur;
    private string $prenom_utilisateur;
    private string $sexe_utilisateur;
    private string $password_utilisateur;
    private string $date_inscription_utilisateur;
    private string $img_utilisateur;
    private string $mail_utilisateur;
    private bool $admin_utilisateur;

    public function __construct(
        int $id_utilisateur,
        int $id_club_utilisateur,
        string $nom_utilisateur,
        string $prenom_utilisateur,
        string $sexe_utilisateur,
        string $password_utilisateur,
        string $date_inscription_utilisateur,
        string $img_utilisateur,
        string $mail_utilisateur,
        bool $admin_utilisateur
    ) {
        $this->id_utilisateur = $id_utilisateur;
        $this->id_club_utilisateur = $id_club_utilisateur;
        $this->nom_utilisateur = $nom_utilisateur;
        $this->prenom_utilisateur = $prenom_utilisateur;
        $this->sexe_utilisateur = $sexe_utilisateur;
        $this->password_utilisateur = $password_utilisateur;
        $this->date_inscription_utilisateur = $date_inscription_utilisateur;
        $this->img_utilisateur = $img_utilisateur;
        $this->mail_utilisateur = $mail_utilisateur;
        $this->admin_utilisateur = $admin_utilisateur;
    }




    /**
     * Get the value of id_utilisateur
     */
    public function getId_utilisateur()
    {
        return $this->id_utilisateur;
    }

    /**
     * Set the value of id_utilisateur
     *
     * @return  self
     */
    public function setId_utilisateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    /**
     * Get the value of id_club_utilisateur
     */
    public function getId_club_utilisateur()
    {
        return $this->id_club_utilisateur;
    }

    /**
     * Set the value of id_club_utilisateur
     *
     * @return  self
     */
    public function setId_club_utilisateur($id_club_utilisateur)
    {
        $this->id_club_utilisateur = $id_club_utilisateur;

        return $this;
    }

    /**
     * Get the value of nom_utilisateur
     */
    public function getNom_utilisateur()
    {
        return $this->nom_utilisateur;
    }

    /**
     * Set the value of nom_utilisateur
     *
     * @return  self
     */
    public function setNom_utilisateur($nom_utilisateur)
    {
        $this->nom_utilisateur = $nom_utilisateur;

        return $this;
    }

    /**
     * Get the value of prenom_utilisateur
     */
    public function getPrenom_utilisateur()
    {
        return $this->prenom_utilisateur;
    }

    /**
     * Set the value of prenom_utilisateur
     *
     * @return  self
     */
    public function setPrenom_utilisateur($prenom_utilisateur)
    {
        $this->prenom_utilisateur = $prenom_utilisateur;

        return $this;
    }

    /**
     * Get the value of sexe_utilisateur
     */
    public function getSexe_utilisateur()
    {
        return $this->sexe_utilisateur;
    }

    /**
     * Set the value of sexe_utilisateur
     *
     * @return  self
     */
    public function setSexe_utilisateur($sexe_utilisateur)
    {
        $this->sexe_utilisateur = $sexe_utilisateur;

        return $this;
    }

    /**
     * Get the value of password_utilisateur
     */
    public function getPassword_utilisateur()
    {
        return $this->password_utilisateur;
    }

    /**
     * Set the value of password_utilisateur
     *
     * @return  self
     */
    public function setPassword_utilisateur($password_utilisateur)
    {
        $this->password_utilisateur = $password_utilisateur;

        return $this;
    }

    /**
     * Get the value of date_inscription_utilisateur
     */
    public function getDate_inscription_utilisateur()
    {
        return $this->date_inscription_utilisateur;
    }

    /**
     * Set the value of date_inscription_utilisateur
     *
     * @return  self
     */
    public function setDate_inscription_utilisateur($date_inscription_utilisateur)
    {
        $this->date_inscription_utilisateur = $date_inscription_utilisateur;

        return $this;
    }

    /**
     * Get the value of mail_utilisateur
     */
    public function getMail_utilisateur()
    {
        return $this->mail_utilisateur;
    }

    /**
     * Set the value of mail_utilisateur
     *
     * @return  self
     */
    public function setMail_utilisateur($mail_utilisateur)
    {
        $this->mail_utilisateur = $mail_utilisateur;

        return $this;
    }

    /**
     * Get the value of img_utilisateur
     */
    public function getImg_utilisateur()
    {
        return $this->img_utilisateur;
    }

    /**
     * Set the value of img_utilisateur
     *
     * @return  self
     */
    public function setImg_utilisateur($img_utilisateur)
    {
        $this->img_utilisateur = $img_utilisateur;

        return $this;
    }    

    /**
     * Get the value of amdin_utilisateur
     */ 
    public function getAdmin_utilisateur()
    {
        return $this->admin_utilisateur;
    }

    /**
     * Set the value of amdin_utilisateur
     *
     * @return  self
     */ 
    public function setAdmin_utilisateur($admin_utilisateur)
    {
        $this->admin_utilisateur = $admin_utilisateur;

        return $this;
    }
}
