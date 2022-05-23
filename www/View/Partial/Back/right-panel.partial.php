<section class="right-panel-dasboard">
    <header>
        <img src="../../dist/assets/logo.svg" />
    </header>
    <?php use App\Utils\DynamicSvg; ?>

    <nav class="right-panel-menu">
        <ul>
            <li><a href="#"><?php echo DynamicSvg::getIcon("home", "dark"); ?> <span>Accueil</span></a></li>
            <li><a href="#"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Abonnements</span> </a></li>
            <li><a href="#"><?php echo DynamicSvg::getIcon("settings", "dark"); ?> <span>Paramètres</span> </a></li>
            <li><a href="#"><?php echo DynamicSvg::getIcon("payment", "dark"); ?> <span>Historique</span> </a></li>
        </ul>
    </nav>

    <div class="right-panel-menu-bottom">
        <img src="https://images.unsplash.com/photo-1645908698645-3513721e2ac7?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=800&q=60"/>
        <p>Arthur Besson</p>
    </div>
</section>