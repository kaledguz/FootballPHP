<?php
class GestionArticle
{
    private PDO $cnx;

    public function __construct(PDO $cnx)
    {
        $this->cnx = $cnx;
    }

    function getAllArticle(): array
    {
        $res = $this->cnx->query("select * from Article");
        $tableResult = [];
        while ($ligne = $res->fetch()) {
            $tableResult[] = new Article($ligne[0], $ligne[1], $ligne[2], $ligne[3], $ligne[4]);
        }

        return $tableResult;
    }

    function getAllArticleByInsertion(): array
    {
        $res = $this->cnx->query("select * from Article order by id_article DESC");
        $tableResult = [];
        while ($ligne = $res->fetch()) {
            $tableResult[] = new Article($ligne[0], $ligne[1], $ligne[2], $ligne[3], $ligne[4]);
        }

        return $tableResult;
    }


    function getArticleById(int $idArticle): Article
    {
        $res = $this->cnx->query("select * from Article where id_Article =" . $idArticle);
        $ligne = $res->fetch();
        $article = new Article($ligne[0], $ligne[1], $ligne[2], $ligne[3], $ligne[4]);

        return $article;
    }



    function getAllArticleByTitle(): array
    {
        $res = $this->cnx->query("select * from Article order by titre_article");
        $tableResult = [];
        while ($ligne = $res->fetch()) {
            $tableResult[] = new Article($ligne[0], $ligne[1], $ligne[2], $ligne[3], $ligne[4]);
        }

        return $tableResult;
    }

    function insertArticle(Article $article)
    {
        $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = "insert into article values (default, ?, ?, ?, ?)";

        $req_prepare = $this->cnx->prepare($statement);

        $titre = $article->getTitre_article();
        $desc = $article->getDesc_article();
        $auteur = $article->getAuteur_article();
        $image = $article->getImage_article();

        $req_prepare->bindParam(1, $titre);
        $req_prepare->bindParam(2, $desc);
        $req_prepare->bindParam(3, $auteur);
        $req_prepare->bindParam(4, $image);

        $req_prepare->execute();
    }
}
