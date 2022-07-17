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
            <!-- <?php if (Security::isVGdmin() || Security::isAdmin()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "clients" ? "active" : "") : null ?>><a href="/dashboard/clients"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Clients</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "sites" ? "active" : "") : null ?>><a href="/dashboard/sites"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Sites</span> </a></li>
                <button class="primary-button" href="/logout">Logout</button>
                <?php endif; ?>
                <?php if (Security::isMember()) : ?>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "subscribe" ? "active" : "") : null ?>><a href="/dashboard/subscribe"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Abonnements</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
                    <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "articles" ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Articles</span> </a></li>
            <?php endif; ?>
            <?php if (Security::isEditor()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "articles" ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Articles</span> </a></li>
            <?php endif; ?>
            <?php if (Security::isModerator()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "comments" ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Commentaires</span> </a></li>
            <?php endif; ?> -->
            
            <?php if (Security::isEditor()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "articles" ) ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
            <?php elseif (Security::isModerator()) : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "comments" ) ? "active" : "") : null ?>><a href="/dashboard/comments"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
            <?php else : ?>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? "" : "active" ?>><a href="/dashboard"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (str_contains(explode("/", $_SERVER["REQUEST_URI"])[2], "articles" ) ? "active" : "") : null ?>><a href="/dashboard/articles"><?php echo DynamicSvg::getIcon("articles", "dark"); ?> <span>Articles</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "subscribe" ? "active" : "") : null ?>><a href="/dashboard/subscribe"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Abonnements</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "settings" ? "active" : "") : null ?>><a href="/dashboard/settings"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
                <li class=<?= isset(explode("/", $_SERVER["REQUEST_URI"])[2]) ? (explode("/", $_SERVER["REQUEST_URI"])[2] === "history" ? "active" : "") : null ?>><a href="/dashboard/history"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
            <?php endif; ?>
                

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
        <a class="button" href="/logout">Logout</a>
    </div>
</section>