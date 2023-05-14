<div class="flex item-center w-full h-screen">
    <div class="w-[20%] min-w-[250px] h-full p-5 bg-regal-blue">
        <h1 class="title is-1 is-spaced text-center text-white">Tableau de bord</h1>

        <a class="title is-3 text-white" href="index.php?page=administration&action=add_article">Article</a>
    </div>

    <div class="w-[80%] h-full p-5">

        <?php
        if (isset($_GET['action']) && !empty($_GET['action'])) {
            $action = $_GET['action'];
            switch ($action) {
                case 'add_article':
                    include('c_add_article.php');
                    break;
            }
        }
        ?>

    </div>
</div>