<section class="articles-container">
    <link rel="stylesheet" href="/dist/css/sites.css">
    <style>
        <?php
        use App\Helpers\Utils;
        use \App\Core\Routing\Router;
        include "dist/css/articles.css";
        $mysites = $_GET['my-sites'];
        $manage_pages = $_GET['manage-pages'];
        function truncate($string, $length, $dots = "...") {
            return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
        }

        ?>
    </style>
    <div class="articles-content">
        <div class="articles-content-head">
            <h3 class="title-page"><?php echo $view_name ?></h3>
            <button class="button--primary create-website">Créer un site <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <p><?php echo $description ?></p>
        <br>
        <div class="bar-menu">
            <div class="bar-menu-head">
                <span class="<?php if (!isset($manage_pages)) echo ("active")  ?>" onclick="changeMenu('')">Mes sites</span>
            <?php if (isset($manage_pages)) { ?>  <span class="<?php echo ("active")  ?>" >Pages site </span><?php } ?>
            </div>
            <hr>
        </div>
        <?php if (!isset($manage_pages)) { ?>
      <div class="grid grid--flex grid--flex-4 grid--flex-3--t grid--flex-2--ms grid--flex-1--s articles-cards">
          <?php
            foreach ($all_sites_filtered as $key => $value) {
            $kk = isset($mysites) ?  '<br/> <p class="description-card-site">Un template créer avec coeur par Alex dieudonne. Pour le site VGCréator en balllle.</p>': '';
            $choose = "Modifier";
            $checked =  $value->getStatus() ? "checked": "";

            $show_toogle = $value->getName() != "vg-creator"? '<label class="switcher"><input '. $checked.' id="'."card-".$value->getId(). '." class="check-box-card" type="checkbox">  <span class="slider-switcher round"></span>
                </label>':'';

            //onclick="navigateSiteClient('.$value->getId().',\''."homepage".'\''.')

            echo '  <article class="site-card">
            <div class="card-header">
                <h3 class="title-site-card">' . $value->getName() . '</h3>
                <span>By: Alex D.</span>
                <img class="card-ico-site" src="https://logo-marque.com/wp-content/uploads/2020/10/Monster-Energy-Logo-Histoire-650x487.jpg"/>
                <br/>
                '.  $kk.' </div>
            <div class="card-site-footer">
                <button
                 onclick="changeMenu(\'?manage-pages='.$value->getId().'\')"
                 >'.  $choose  .'</button>
                   '.$show_toogle.'
            </div>
        </article>';
          }
        ?>
          <?php };  ?>
      </div>
        <?php if(isset($manage_pages) && isset($pages)){ ?>

        <div class="page-container">
            <table>
            <caption>Pages</caption>
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Slug</th>
                <th scope="col">Actions <div style="width:20px"></div> </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages as $key => $value){ ?>
            <tr>
                <td data-label="Name"><?php echo truncate($value->getName(), 23)  ?></td>
                <td data-label="Url">/<?php echo truncate($value->getSlug(), 24, "...") ?></td>
                <td data-label="Actions">
                    <button onclick="navigateSiteClient(<?php echo $manage_pages .",". "'".$value->getSlug()."'" ?>)" class="btn--alex btn--normal">View <i class="fa-regular fa-eye"></i></button>
                    <button id="edit-btn-<?php echo $value->getId()?>" onclick="updateSitePage(this, <?php echo $value->getId()  ?>)" class="btn--alex btn--blue">
                        Edit <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                    <button onclick="confirmDeletePage(<?php echo $value->getId()  ?>)" class="btn--alex btn--alert">
                        Remove <i class="fa-light fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php } ?>
            </tbody>

        </table>
            <br/>
            <button id="create-page" class="btn--alex btn--blue">
                Add new page <i class="fa-regular fa-pen-to-square"></i>
            </button>
        </div>
        <?php } ?>
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

    function locationMove(){
        location.href = "/<?php echo Router::getInstance()->url('post.editShowPost') ?>".replace(':id_post', '') + route
    }

    function updateSitePage(e, id_post){
        const td = e.parentNode.previousElementSibling;

        const td_name = td.previousElementSibling.innerHTML;
        const td_slug = td.innerHTML;

        shoModal(true,id_post)
        const input_name = document.getElementById('input-name-page');
        const input_slug = document.getElementById('input-slug-page');
        input_slug.value = td_slug.replaceAll('/','');
        input_name.value = td_name.replaceAll('/','');
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

    function shoModal(update=false,  id_post=null) {
        const what = update?'Modifier':'Créer';

        <?php
            $action_create = Router::getInstance()->url( 'site.handleSite.Create', ['id_site' => $manage_pages]);
            $action_update = Router::getInstance()->url( 'site.handleSite.Update', ['id_site' => $manage_pages]);
        ?>

        $().simpleModal({
            name: 'example',
            title: what+' une page',
            content: '<form method="post" action="/'+ (!update?"<?php echo $action_create?>":("<?php echo $action_update?>".replace(":id_page", id_post))) + '/" >' +
                '<div class="input-post">' +
                '<label> Nom</label>' +
                ' <input name="name" required type="text" id="input-name-page" placeholder="Nom" />' +
                '</div>' +
                '<div class="input-post">' +
                '<label> Slug </label>' +
                '<input name="slug" id="input-slug-page" id="url-id" type="text" pattern="[a-zA-Z0-9]+"  placeholder="Slug" />' +
                '</div>' +
                '<br/>' +
                '<button type="submit" class="btn-mod1" style="width: 10em; ">'+what+ ' ma page </button>' +
                '</form>',
            size: 'large', // or integer for custom width
            freeze: true,
            callback: function () {
                $('.simple-modal--example .btn--alex').click(function () {
                    console.log('Click from modal');
                });
            }
        });
    }

    $( "#create-page" ).click(function() {
       shoModal();
        var input = document.getElementById('url-id');
        input.oninvalid = function(event) {
            event.target.setCustomValidity('Slug should be like that. poulet');
        }
    });


    function confirmDeletePage(id){
        if (window.confirm('Êtes-vous sur de vouloir supprimer cette page?')) {
            fetch('/<?php echo Router::getInstance()->url("site.handleSite.Delete") ?>'.replace(":id_page", id).replace(":id_site", "<?php echo $manage_pages ?>"), {
                method: 'DELETE',
            }).then(async res => {
                if(res.status === 200){
                    window.location.reload();
                }
                console.log("Request complete! response:", await res.text());
            });
        }
    }

    function navigate(route) {
        location.href = "/<?php echo Router::getInstance()->url('post.editShowPost') ?>".replace(':id_post', '')+route
    }

    $(".create-website").on('click',function() {
        window.location.href = "/<?php echo Router::getInstance()->url('site.createSite') ?>"
    });

</script>