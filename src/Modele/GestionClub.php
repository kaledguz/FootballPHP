<?php
class GestionClub
{
    private PDO $cnx;

    public function __construct(PDO $cnx)
    {
        $this->cnx = $cnx;
    }

    function getListClub(): array
    {
        $res = $this->cnx->query("select * from Club");
        $tableResult = [];
        while ($ligne = $res->fetch()) {
        $tableResult[] = new Club($ligne[0], $ligne[1], $ligne[2]);        
        }        
        return $tableResult;
    }

    function getListClubByName(): array
    {
        $res = $this->cnx->query("select distinct club.id_club, club.nom_club, championnat.nom        
                                from Club
                                inner join saison ON saison.id_club = Club.id_club
                                inner join championnat ON championnat.id_championnat = saison.id_championnat
                                where saison.annee = 2023
                                order by nom_club;");
        $tableResult = [];
        while ($ligne = $res->fetch()) {            
            $tableResult[] = new Club($ligne[0], $ligne[1], $ligne[2]);        
        }
        
        return $tableResult;
    }
}
