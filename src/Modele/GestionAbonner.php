<?php
class GestionAbonner
{
    private PDO $cnx;

    public function __construct(PDO $cnx)
    {
        $this->cnx = $cnx;
    }

    function insertAbonner(Utilisateur $userInscription, array $listAbonnementClub)
    {
        $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        foreach ($listAbonnementClub as $club) {
            $this->cnx->exec(
                "insert into s_abonner values (" . $userInscription->getId_utilisateur() . ", " . $club . ")"
            );
        }
        
    } 
}