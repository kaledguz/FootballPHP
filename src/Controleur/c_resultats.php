<?php
include('../Modele/GestionBDD.php');

$ligue = $_GET['ligue'];

$BDD = new GestionBDD('Ligue_1');
$cnx = $BDD->connect();


$resJournee = $cnx->query("select distinct journee from rencontre order by journee");

// tableau de mots
$mots = array();
while ($ligneJournee = $resJournee->fetch()) {
    array_push($mots, intval($ligneJournee[0]));
}

$indexMot = 0; // index du mot actuel
if (isset($_GET['journee'])) {
    $indexMot = intval($_GET['journee'] - 1);
}

if (!(in_array(($indexMot + 1), $mots, true))) {
    header("Location: index.php?page=resultats&ligue=1");
}

$query = "select * from get_matchs_journee(2023, " . $ligue . ", " . ($indexMot + 1) . ")";
//echo $query;
$resMatch = $cnx->query($query);

//Formatage de la date
$resMatch = $resMatch->fetchAll();
$date = $resMatch[0]["date_match"];
$timestamp = strtotime($date);

$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
$formatter->setPattern('EEEE d MMMM Y');

$dateFormatee = $formatter->format($timestamp);
$dateFormatee = ucfirst($dateFormatee);

include("../Vue/v_resultats.php");

$cnx = null;
