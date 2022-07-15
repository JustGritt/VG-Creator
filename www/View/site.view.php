<section class="articles-container">
    <link rel="stylesheet" href="/dist/css/sites.css">
    <style>
        <?php
        use App\Helpers\Utils;
        use \App\Core\Routing\Router;
        include "dist/css/articles.css";
        $mysites = $_GET['my-sites'];
        ?>
    </style>
    <div class="articles-content">
        <div class="articles-content-head">
            <h3 class="title-page"><?php echo $view_name ?></h3>
            <button>Créer un site <i class="fa-solid fa-plus"></i></button>
        </div>
        <p><?php echo $description ?></p>
        <br>
        <div class="bar-menu">
            <div class="bar-menu-head">
                <span class="<?php if (isset($mysites)) echo ("active")  ?>" onclick="changeMenu('?my-sites')">Mes sites</span>
                <span class="<?php if (!isset($mysites)) echo ("active")  ?>" onclick="changeMenu('')">Tous nos templates </span>
            </div>
            <hr>
        </div>

      <div class="grid grid--flex grid--flex-4 grid--flex-3--t grid--flex-2--ms grid--flex-1--s articles-cards">
        <article class="site-card">
            <div class="card-header">
                <h3 class="title-site-card">Linear</h3>
                <span>By: Alex D.</span>

                <img class="card-ico-site" src="https://logo-marque.com/wp-content/uploads/2020/10/Monster-Energy-Logo-Histoire-650x487.jpg"/>
                <br/>
                <?php
                    if(!isset($mysites)) echo '<br/> <p class="description-card-site">Un template créer avec coeur par Alex dieudonne. Pour le site VGCréator en balllle.</p>'
                ?>
            </div>
            <div class="card-site-footer">
                <button><?php echo isset($mysites) ? "Modifier" :"Choisir ce template"  ?></button>
                <label class="switcher">
                    <input type="checkbox">
                    <span class="slider-switcher round"></span>
                </label>
            </div>
        </article>
      </div>
    </div>
</section>
<!-- <span> 23%</span> -->
<script>

    function changeMenu(type) {
        window.location.href = "/<?php echo Router::getInstance()->url('post.showAll') ?>" + type
    }


    function navigate(route) {
        location.href = "/<?php echo Router::getInstance()->url('post.editShowPost') ?>".replace(':id_post', '')+route
    }
</script>