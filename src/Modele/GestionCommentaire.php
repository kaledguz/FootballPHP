<?php
class GestionCommentaire
{
    private PDO $cnx;

    public function __construct(PDO $cnx)
    {
        $this->cnx = $cnx;
    }

    function getAllCommentaire(int $idArticle): array
    {
        $res = $this->cnx->query("select commentaire.*, utilisateur.prenom_uti from Commentaire 
                                inner join utilisateur on utilisateur.id_uti = Commentaire.id_uti
                                where id_article=" . $idArticle);
        $tableResultCommentaire = [];
        while ($ligne = $res->fetch()) {
        $tableResultCommentaire[] = new Commentaire($ligne["id_commentaire"], $ligne["str_commentaire"], $ligne["id_article"], $ligne["id_uti"], $ligne["prenom_uti"]);        
        }
        
        return $tableResultCommentaire;
        
    } 

    function getTenLastCommentaire(int $idArticle): array
    {
        $res = $this->cnx->query("select commentaire.*, utilisateur.prenom_uti from Commentaire 
                                inner join utilisateur on utilisateur.id_uti = Commentaire.id_uti
                                where id_article=" . $idArticle . 
                                " ORDER BY id_commentaire DESC limit (10)");
                                $tableResultCommentaire = [];
        while ($ligne = $res->fetch()) {
        $tableResultCommentaire[] = new Commentaire($ligne["id_commentaire"], $ligne["str_commentaire"], $ligne["id_article"], $ligne["id_uti"], $ligne["prenom_uti"]);        
        }
        
        return $tableResultCommentaire;
        
    } 

    function insertCommentaire(Commentaire $commentaire)
    {
        $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       //echo $commentaire->getId_article();

       $statement = "insert into commentaire values (default, ?, ?, ?)";
       
       $req_prepare = $this->cnx->prepare($statement);

       //on associe le paramètre 1 à la variable $id
       $req_prepare->bindParam(1, $commentaire->getDesc_commentaire());
       //on associe le paramètre 2 à la variable $pass
       $req_prepare->bindParam(2, $commentaire->getId_article());

       $req_prepare->bindParam(3, $commentaire->getId_uti());

       $req_prepare->execute();
    }
}