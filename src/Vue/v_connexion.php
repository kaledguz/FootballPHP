<div class="w-[410px] min-w-[400px] max-w-[700px] mx-auto my-[100px] p-[20px] rounded
shadow-[0px_0px_12px_4px_rgba(0,0,0,0.3)]">
    <h1 class="title is-1 is-spaced text-center">Connexion</h1>

    <form method="POST" action="index.php?page=connexion" name="formConnection" id="idformConnection" class="inscription" enctype="multipart/form-data">
        <div class="field">
            <p class="control">
                <input name="mailConnexion" class="input" type="email" placeholder="Email">
            </p>
        </div>
        <div class="field">
            <p class="control">
                <input name="passwordConnexion" class="input" type="password" placeholder="Password">
            </p>
        </div>
        <div class="field">
            <p class="control text-center">
                <input class="button is-rounded m-5" type="submit" name="valider" value="Valider" />
        </div>
    </form>
</div>