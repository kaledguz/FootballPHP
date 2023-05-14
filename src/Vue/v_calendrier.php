<h1 class="title is-1 is-spaced text-center mt-[20px]">Calendrier de ligue <?php echo $ligue ?></h1>

<div class="w-[80%] h-[100px] m-auto flex bg-regal-blue">
    <form class="container" method="POST" action="index.php?page=calendrier&ligue=<?php echo $ligue ?>" name="formCalendrier">

        <div class="saison flex">
            <label class="text-white mr-[5px]" for="saison">SAISON : </label>
            <select id="saison" name="saison">
                <?php
                while ($ligne = $resSaison->fetch()) {
                    echo "<option value=" . $ligne["annee"] . ">" . ($ligne["annee"] - 1) . "/" . $ligne["annee"] .  "</option>";
                }
                ?>
            </select>
        </div>

        <div class="journee flex">
            <label class="text-white mr-[5px]" for="journee">JOURNÉE :</label>
            <select id="journee" name="journee" class="w-[50px]">
                <?php
                while ($ligneJ = $resJournee->fetch()) {
                    echo "<option value=" . $ligneJ["journee"] . ">" . $ligneJ["journee"] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="field bouton flex">
            <input class="button is-light" type="submit" name="filtre" value="Appliquer le filtre" />
        </div>
    </form>
</div>


<?php
if (isset($_POST['filtre'])) {
    include("../Vue/t_match_journee.php");
}
?>


<style>
    .container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr;
        gap: 0px 0px;
        grid-template-areas:
            "saison journee bouton";
    }

    .saison {
        grid-area: saison;
    }

    .journee {
        grid-area: journee;
    }

    .bouton {
        grid-area: bouton;
    }

    .flex {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        align-content: stretch;
    }
</style>