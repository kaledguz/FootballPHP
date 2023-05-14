<?php
include('../Modele/GestionBDD.php');
include('../Modele/GestionArticle.php');
include('../Modele/Article.php');

$BDD = new GestionBDD('Ligue_1');
$cnx = $BDD->connect();

$GA = new GestionArticle($cnx);
$tableResultArticle = $GA->getAllArticleByInsertion();

include("../Vue/v_article.php");

$cnx = null;


