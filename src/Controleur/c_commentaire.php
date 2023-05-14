<?php
if (isset($_POST['valider'])) {

    include('../Modele/Commentaire.php');
    include('../Modele/GestionBDD.php');
    include('../Modele/GestionCommentaire.php');


    $insertion = true;

    if (isset($_REQUEST['txtCommentaire']) && !empty($_REQUEST['txtCommentaire'])) {
        $txtComment = strip_tags($_REQUEST['txtCommentaire']);
        $txtComment = nl2br(htmlentities($txtComment));
    //    $txtComment = ($_REQUEST['txtCommentaire']);
      
    } else {
        $insertion = false;
    }

    if (isset($_REQUEST['commentaireIdArticle']) && !empty($_REQUEST['commentaireIdArticle'])) {
        $idArticle = $_REQUEST['commentaireIdArticle'];
    } else {
        $insertion = false;
    }

    if ($insertion) {
        echo $idArticle;
        $commentaire = new Commentaire(0,$txtComment, (int) $idArticle, $utilisateurSession->getId_utilisateur(), "");

        $BDD = new GestionBDD('Ligue_1');
        $cnx = $BDD->connect();

        $GC = new GestionCommentaire($cnx);
        $GC->insertCommentaire($commentaire);

        header("Location: index.php?page=commentaire&idArticle=" . $idArticle);


    } else {
        echo 'nop';
    }
} else {
    include('../Modele/GestionBDD.php');

    include('../Modele/GestionArticle.php');
    include('../Modele/Article.php');

    include('../Modele/GestionCommentaire.php');
    include('../Modele/Commentaire.php');

    $idArticle = $_REQUEST['idArticle'];

    $BDD = new GestionBDD('Ligue_1');
    $cnx = $BDD->connect();

    $GA = new GestionArticle($cnx);
    $article = $GA->getArticleById($idArticle);

   $GC = new GestionCommentaire($cnx);
   $tableResultCommentaire  = $GC->getTenLastCommentaire($idArticle);

    include('../Vue/v_commentaire.php');
}


