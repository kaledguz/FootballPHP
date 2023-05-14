<?php
include('../Modele/GestionBDD.php');

$ligue = $_GET['ligue'];

$BDD = new GestionBDD('Ligue_1');
$cnx = $BDD->connect();

$resSaison = $cnx->query("select distinct annee from saison order by annee desc");
$resJournee = $cnx->query("select distinct journee from rencontre order by journee");


if (isset($_POST['filtre'])) {
    $saison = $_POST['saison'];
    $journee = $_POST['journee'];

    $query = "select * from get_matchs_journee(" . $saison . ", " . $ligue . ", " . $journee . ")";
    $resMatch = $cnx->query($query);

    //Formatage de la date
    $resMatch = $resMatch->fetchAll();
    $date = $resMatch[0]["date_match"];
    $timestamp = strtotime($date);

    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    $formatter->setPattern('EEEE d MMMM Y');

    $dateFormatee = $formatter->format($timestamp);
    $dateFormatee = ucfirst($dateFormatee);
}
include("../Vue/v_calendrier.php");
