<?php

class GestionUtilisateur
{
    private PDO $cnx;

    public function __construct(PDO $cnx)
    {
        $this->cnx = $cnx;
    }

    function insertUser(Utilisateur $userInscription): Utilisateur
    {
        $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into utilisateur values (default, " . $userInscription->getId_club_utilisateur() . ", '" .
            $userInscription->getNom_utilisateur() . "', '" . $userInscription->getPrenom_utilisateur() . "', '" .
            $userInscription->getSexe_utilisateur() . "', '" . $userInscription->getPassword_utilisateur() . "', 'now', '" .
            $userInscription->getImg_utilisateur() . "', '" . $userInscription->getMail_utilisateur() . "')";

        $this->cnx->exec($sql);
        $id = $this->cnx->lastInsertId();
        $userInscription->setId_utilisateur($id);
        return $userInscription;
    }

    function existUser(String $mail, &$utilisateur)
    {
        $res = $this->cnx->query("select * from utilisateur where mail_uti = '" . $mail . "'");
        if ($res->rowCount() == 1) {
            $tab = $res->fetch();
            $utilisateur = new Utilisateur($tab[0], $tab[1], $tab[2], $tab[3], $tab[4], $tab[5], $tab[6], $tab[7], $tab[8], $tab[9]);
            return true;
        } else {
            $utilisateur = null;
            return false;
        }
    }

    function getUserById(int $id): Utilisateur
    {
        $res = $this->cnx->query("select * from utilisateur where id_uti = " . $id);
        $tab = $res->fetch();
        $utilisateur = new Utilisateur($tab[0], $tab[1], $tab[2], $tab[3], $tab[4], $tab[5], $tab[6], $tab[7], $tab[8], $tab[9]);
        return $utilisateur;
    }

    function getUserByMail(String $mail): Utilisateur
    {
        $res = $this->cnx->query("select * from utilisateur where mail_uti = '" . $mail . "'");
        $tab = $res->fetch();
        $utilisateur = new Utilisateur($tab[0], $tab[1], $tab[2], $tab[3], $tab[4], $tab[5], $tab[6], $tab[7], $tab[8], $tab[9]);
        return $utilisateur;
    }
}
