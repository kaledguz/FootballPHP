<h1 class="title is-1 is-spaced text-center mt-[20px]">Ajout d'article</h1>

<form method="POST" action="index.php?page=administration&action=add_article" name="formArticle" id="idformArticle" enctype="multipart/form-data">

    <!-- Titre -->
    <div class="field">
        <label class="label">Titre</label>
        <div class="control">
            <input class="input" type="text" placeholder="Entrez le titre de l'article" name="titreArticle" required>
        </div>
    </div>

    <!-- Description -->
    <div class="field">
        <label class="label">Description</label>
        <div class="control">
            <textarea class="textarea" type="textarea" placeholder="Entrez la description de l'article" name="descArticle" required></textarea>
        </div>
    </div>

    <!-- Auteur -->
    <div class="field">
        <label class="label">Auteur</label>
        <div class="control">
            <input class="input" type="text" placeholder="Entrez l'auteur de l'article" name="auteurArticle" required>
        </div>
    </div>

   <!-- Image -->
   <div class="field">
      <label class="label">Image</label>
      <input type="file" name="imgArticle" size="50" required>
    </div>


    <div class="field is-grouped">
        <div class="control">
            <input type="reset" name="annuler" value="Annuler" />
        </div>
        <div class="control">
            <input type="submit" name="ajouter" value="Ajouter" />
        </div>
    </div>
</form>