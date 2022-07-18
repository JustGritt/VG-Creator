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
                <span class="<?php echo ("active")  ?>" onclick="changeMenu('?my-sites')">Mes sites</span>
<!--                <span class="--><?php //if (!isset($mysites)) echo ("active")  ?><!--" onclick="changeMenu('')">Tous nos templates </span>-->
            </div>
            <hr>
        </div>
      <div class="grid grid--flex grid--flex-4 grid--flex-3--t grid--flex-2--ms grid--flex-1--s articles-cards">
          <?php
            foreach ($all_sites_filtered as $key => $value) {
            $kk = isset($mysites) ?  '<br/> <p class="description-card-site">Un template créer avec coeur par Alex dieudonne. Pour le site VGCréator en balllle.</p>': '';
            $choose = "Modifier";
            $checked =  $value->getStatus() ? "checked": "";

            $show_toogle = $value->getName() != "vg-creator"? '<label class="switcher"><input '. $checked.' id="'."card-".$value->getId(). '." class="check-box-card" type="checkbox">  <span class="slider-switcher round"></span>
                </label>':'';


              echo '  <article class="site-card">
            <div class="card-header">
                <h3 class="title-site-card">' . $value->getName() . '</h3>
                <span>By: Alex D.</span>
                <img class="card-ico-site" src="https://logo-marque.com/wp-content/uploads/2020/10/Monster-Energy-Logo-Histoire-650x487.jpg"/>
                <br/>
                '.  $kk.' </div>
            <div class="card-site-footer">
                <button onclick="navigateSiteClient('.$value->getId().',\''."homepage".'\''.')">'.  $choose  .'</button>
                   '.$show_toogle.'
            </div>
        </article>';
          }
        ?>
      </div>
    </div>

    <?php if (isset($mysites)) echo '<div class="info-card">
        <p>Vous êtes connecté en tant que <strong>Member</strong> du site <strong>Toto</strong></p>
        <span>Les sites affichés dans cette section appartiennent à votre <i>Admin</i></span>
        <button><i class="fa-solid fa-xmark-large"></i></button>
    </div>' ?>
</section>
<!-- <span> 23%</span> -->
<script>

    function changeMenu(type) {
        window.location.href = "/<?php echo Router::getInstance()->url('post.showAll') ?>" + type
    }

    function navigateSiteClient(id_site, slug){
        window.location.href ="/<?php echo Router::getInstance()->url('site.editClient') ?>".replace(":id_site",id_site).replace(":slug",slug)
    }

    function changedStatus(){
        $.post( "ajax/test.html", function( data ) {
            $( ".result" ).html( data );
        });
    }

    $(".switcher").on('change',function() {
        const checked = $(this).find(".check-box-card").is(':checked');
        const id_site = $(this).find(".check-box-card").attr('id').replace('.','').split('-')[1];
        $.ajax({
            url: "/dashboard/sites/"+ id_site,
            type: 'PUT',
            data: {"status":checked ? 1 : 0},
        }).done(async function(data){
            showSnackBar("Le status a est désormais: "+(checked? "Publié":"Non publié"));
        }).fail(function (msg) {
            console.log('FAIL');
        })
    });

//

    function navigate(route) {
        location.href = "/<?php echo Router::getInstance()->url('post.editShowPost') ?>".replace(':id_post', '')+route
    }

</script>