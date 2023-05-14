<?php
if (isset($_SESSION['sessionUti'])) {

    $admin = $utilisateurSession->getAdmin_utilisateur();

    if ($admin) {

        if (isset($_POST['ajouter'])) {
            traitmentArticle();
        } else {
            include('../Vue/v_add_article.php');
        }
    } else {
        header("Location: index.php?page=erreur");
    }
} else {
    header("Location: index.php?page=erreur");
}

function traitmentArticle()
{
    include('../Modele/Article.php');
    include('../Modele/GestionBDD.php');
    include('../Modele/GestionArticle.php');

    $insertion = true;

    /* Vérification du titre */
    if (isset($_REQUEST['titreArticle']) && !(empty($_REQUEST['titreArticle']))) {
        $titre_Article = $_REQUEST['titreArticle'];
    } else {
        $insertion = false;
    }

    /* Vérification de la description */
    if (isset($_REQUEST['descArticle']) && !(empty($_REQUEST['descArticle']))) {
        $desc_Article = $_REQUEST['descArticle'];
    } else {
        $insertion = false;
    }

    /* Vérification de l'auteur */
    if (isset($_REQUEST['auteurArticle']) && !(empty($_REQUEST['auteurArticle']))) {
        $auteur_Article = $_REQUEST['auteurArticle'];
    } else {
        $insertion = false;
    }


    /* Vérification de l'image */
    if (isset($_FILES["imgArticle"]["type"]) && !(empty($_FILES["imgArticle"]["type"]))) {
        if (($_FILES["imgArticle"]["type"] == "image/jpeg")
            || ($_FILES["imgArticle"]["type"] == "image/pjpeg")
            || ($_FILES["imgArticle"]["type"] == "image/png")
        ) {
            if (!($_FILES["imgArticle"]["error"] > 0)) {
                $imgData = file_get_contents($_FILES["imgArticle"]["tmp_name"]);
                $imgBase64 = 'data:' . $_FILES["imgArticle"]["type"] . ';base64,' . base64_encode($imgData);               
            } else {
                $insertion = false;
            }
        } else {
            echo "Chemin invalide ! <br/>";
            $insertion = false;
        }
    } else {
        $insertion = false;
    }

    if ($insertion) {

        $article = new Article(0, $titre_Article, $desc_Article, $auteur_Article, $imgBase64);

        $BDD = new GestionBDD('Ligue_1');
        $cnx = $BDD->connect();

        $GA = new GestionArticle($cnx);

        $GA->insertArticle($article);

        header("Location: index.php?page=administration&action=add_article");
    }
}
