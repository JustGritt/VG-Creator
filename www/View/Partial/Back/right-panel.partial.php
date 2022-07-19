<section class="right-panel-dasboard">
    <header>
        <img src="../../dist/assets/logo.svg" />
    </header>
    <?php

    use App\Core\Security;
    use App\Helpers\DynamicSvg; ?>

    <nav class="right-panel-menu">
        <ul>
            <?php
            if (!function_exists('str_contains')) {
                function str_contains(string $haystack, string $needle): bool
                {
                    return '' === $needle || false !== strpos($haystack, $needle);
                }
            }
            ?>
        
            <?php if (Security::isEditor()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "articles" ) ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "media" ? "active" : "") : null ?>><a href="/dashboard/media"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Media</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "categories" ? "active" : "") : null ?>><a href="/dashboard/categories"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Categories</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
            <?php elseif (Security::isModerator()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "comments" ) ? "active" : "") : null ?>><a href="/dashboard/comments"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
            <?php else : ?>

                <button type="button" class="collapsible">Actions site</button>
                <div class="content">
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "media" ? "active" : "") : null ?>><a href="/dashboard/media"><?php echo DynamicSvg::getIcon("media", "dark"); ?> <span>Media</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "categories" ? "active" : "") : null ?>><a href="/dashboard/categories"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Categories</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "comments" ? "active" : "") : null ?>><a href="/dashboard/comments"><?php echo DynamicSvg::getIcon("comment", "dark"); ?> <span>Commentaires</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "clients" ? "active" : "") : null ?>><a href="/dashboard/clients"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Clients</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "articles" ) ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
                </div>

                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "changeAccount" ? "active" : "") : null ?>><a href="/dashboard/changeAccount"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Changer de compte</span> </a></li>
            <?php endif; ?>

            <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "sites" ) ? "active" : "") : null ?>><a href="/dashboard/sites"><?php echo DynamicSvg::getIcon("sites", "dark"); ?> <span>Mes Sites</span> </a></li>
                

            <!-- <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
            <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "articles" ) ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
            <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "subscribe" ? "active" : "") : null ?>><a href="/dashboard/subscribe"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Abonnements</span> </a></li>
            <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
            <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li> -->
        </ul>
    </nav>

    <div class="right-panel-menu-bottom">
        <img src="https://images.unsplash.com/photo-1645908698645-3513721e2ac7?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=800&q=60" />
        <p><?php echo $_SESSION['firstname']?></p>
        <a class="button--secondary" href="/logout">Logout</a>
    </div>
</section>

<style>
    /* Style the button that is used to open and close the collapsible content */
.collapsible {
    background: var(--color-link);
    color: var(--color-white);
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    border-radius: 0;
    transition: all .2s ease-in-out;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
.active,
.collapsible:hover {
    background: var(--color-link);
    color: var(--color-white);
}

/* Style the collapsible content. Note: hidden by default */
.content {
    padding: 0 ;
    display: block;
    height: 0;
    overflow: hidden;
    background-color: var(--color-dashboard-background);
}
</style>

<script>
    const col = document.querySelectorAll('.collapsible');
    var i;

    for (i = 0; i < col.length; i++) {
        col[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.height === "100%") {
                content.style.height = "0px";
            } else {
                content.style.height = "100%"
            }
        });
        
    }
</script>