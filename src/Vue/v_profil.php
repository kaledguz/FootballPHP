<div class="flex item-center w-full h-screen">
    <div class="w-[300px] h-full p-5 bg-regal-blue">
        <div class="flex flex-col item-center">
            <img class="rounded-full border-2 border-black m-5" src="../../img/<?php echo $img ?>">
            <button onclick="afficheDiv()" class="button is-rounded m-5" >Changer de photo de profil</button>
        </div>
    </div>

    <div class="w-[100%] h-full p-5">
    <h1 class="title is-1 is-spaced text-center">Profil</h1>
        <p><?php echo $utilisateurSession->getNom_utilisateur() ?></p>
        <p><?php echo $utilisateurSession->getPrenom_utilisateur() ?></p>
    </div>
</div>

<script>
    function afficheDiv() {
        alert ("ok");
    }
</script>