<article class='shadow-md mx-40 my-5 rounded-xl border-solid border-2'>
    <div class="p-5">
        <h2 class='subtitle is-3 text-center'><?php echo $article->getTitre_article() ?></h2>

        <p><?php echo $article->getDesc_article() ?></p>

        <p class='text-right my-3'>De <?php echo $article->getAuteur_article() ?></p>

    </div>

</article>

<?php

$i = 0;

while ($i < count($tableResultCommentaire)) {
    echo "<article class='shadow-md mx-40 my-5 p-5 rounded-xl border-solid border-2 bg-stone-100'>";

    echo "<p>" . $tableResultCommentaire[$i]->getDesc_commentaire() . " </p>";
    //   echo "<p class='text-right my-3'>De " . $tableResultCommentaire[$i]->getAuteur_commentaire() . "</p>";
    echo "<p class='text-right my-3'>De " . $tableResultCommentaire[$i]->getPrenom_uti() . "</p>";
    echo "</article>";
    $i++;
}

?>


<div class=' w-[40%] mx-auto my-5 rounded-xl border-solid border-2 bg-stone-100' id="idformComment1">
    <form class="p-5" method="POST" action="index.php?page=commentaire" name="formComment">

        <label class="label">Cette article vous a plus ? Commentez le</label>

        <?php
        if (isset($_SESSION['sessionUti'])) {
            // Description
            echo "<div class='field'>";
            echo "<div class='control'>";
            echo "<textarea class='h-36 input' id='idTxtCommentaire' placeholder='Entrez votre commentaire' name='txtCommentaire' required></textarea>";
            echo "</div>";
            echo "</div>";

            // Id Article
            echo "<input class='invisible' type='number' name='commentaireIdArticle' value='" . $_REQUEST['idArticle'] . "' />";

            // Bouton submit
            echo "<div class='control'>";
            echo "<input class='button' type='submit' name='valider' value='Valider' />";
            echo "</input>";
            echo "</div>";
        } else {

            echo "<p>Connecter vous pour commenter l'article.</p>";
            echo "<a href='index.php?page=connexion' class='button' id='idConnexion'>
                    <strong>Connexion</strong>
                </a>";
        }
        ?>
    </form>
</div>