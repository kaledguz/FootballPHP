<h1 class="title is-1 is-spaced text-center mt-[20px]"><?php echo $ligne["nom_club"] ?></h1>


<p>Ville : <?php echo $ligne["ville"] ?></p>
<p>Fondé en : <?php echo $ligne["annee_fondation"] ?></p>
<p>Président : <?php echo $ligne["president"] ?></p>
<p>Entraineur : <?php echo $ligne["entraineur"] ?></p>

 
<img src='<?php echo $ligne["logo"]?>' class="w-[200px]"/>

<p>Ligue actuelle : Ligue <?php echo $ligne["id_championnat"] ?></p>
<p>Nombre de buts marqué : <?php echo $ligne["nb_buts_marques"] ?></p>
<p>Nombre de buts encaissé : <?php echo $ligne["nb_buts_encaisse"] ?></p>
<p>Nombre de matchs gagné : <?php echo $ligne["nb_matchs_gagne"] ?></p>
<p>Nombre de matchs nul : <?php echo $ligne["nb_matchs_nul"] ?></p>
<p>Nombre de matchs perdu : <?php echo $ligne["nb_matchs_perdu"] ?></p>

<?php


?>