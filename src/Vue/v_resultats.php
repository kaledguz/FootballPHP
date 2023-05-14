<h1 class="title is-1 is-spaced text-center mt-[20px]">Résultats des matchs de Ligue <?php echo $ligue ?></h1>


<p style="text-align: center;" class="title is-4 text-regal-blue ">
    <a href=<?php echo "?page=resultats&ligue=" . $ligue . "&journee=" . ((($indexMot - 1 + count($mots)) % count($mots)) + 1); ?>><button>❮</button></a>
    <span>Journée</span>
    <span id="mot"><?php echo $mots[$indexMot]; ?></span>
    <a href=<?php echo "?page=resultats&ligue=" . $ligue . "&journee=" . ((($indexMot + 1) % count($mots)) + 1); ?>><button>❯</button></a>
</p>

<?php
include("../Vue/t_match_journee.php");
?>