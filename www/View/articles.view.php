<section class="articles-container">
    <style>
        <?php
        use App\Helpers\Utils;
        use \App\Core\Routing\Router;
        include "dist/css/articles.css";
        ?>
    </style>
    <div class="articles-content">
        <h3 class="title-page">Articles</h3>
        <p>Retrouvez ici, vos différents articles publiés ou en brouillons.
            Cliquez sur le boutton pencil, pour accéder à la page d’édition.</p>
        <br>
        <div class="bar-menu">
            <div class="bar-menu-head">
                <span class="<?php if (!isset($_GET['published']) && !isset($_GET['drafts'])) echo ("active")  ?>" onclick="changeMenu('')">Vue d’ensemble</span>
                <span class="<?php if (isset($_GET['published'])) echo ("active")  ?>" onclick="changeMenu('?published')">Publiés</span>
                <span class="<?php if (isset($_GET['drafts'])) echo ("active")  ?>" onclick="changeMenu('?drafts')">Brouillons</span>
            </div>
            <hr>
        </div>

        <div class="grid grid--flex grid--flex-4 grid--flex-3--t grid--flex-2--ms grid--flex-1--s articles-cards">
            <?php foreach ($result as $key => $value) {
                $oDate = new DateTime($value['created_at']);
                $sDate = $oDate->format("Y-m-d");
                $status = $value['status'] == 0 ?  'tag-status-draft' : 'tag-status-publish';
                $status_name = $value['status'] == 0 ?  'Brouillon' : 'Publiée';

                echo '<article class="card-article"  >
                <div class="card-article__image">
                    <img src="https://via.placeholder.com/300.png/09f/fff" alt=""  onClick="navigate(' . $value['id'] . ')"}"/>
                    <button onClick="confirmDelete('.$value['id'].')" class="btm-delete"><i class="fa-solid fa-xmark"></i></button>
                <!--   <a href="#"></a>  -->
                </div>
                <div class="card-article-body" onClick="navigate(' . $value['id'] . ')"}">
                    <h4>' . Utils::truncate($value['title']) . '</h4>
                    <p>' . Utils::truncate($value['body'], 40) . '</p>

                    <div class="info-card-article-body">
                        <span class="' . $status . '">'.$status_name.'</span>
                    </div>
                </div>
            </article>';
            }?>
        </div>

        <a href="/<?php echo Router::getInstance()->url("post.createPost",[]) ?>" class="btm-btn">Créer un article</a>
    </div>
</section>
<script>
    function changeMenu(type) {
        window.location.href = 'https://vgcreator.fr/dashboard/articles' + type
    }

    function confirmDelete(id){
        console.log('Thing was saved to the database.');
        if (window.confirm('Êtes-vous sur de vouloir supprimer cet article?')) {
            fetch('/<?php echo Router::getInstance()->url("post.deletePost") ?>'.replace(':id', '') +'/'+id, {
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
        var getUrl = window.location;
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
        location.href = "/<?php echo Router::getInstance()->url('post.editShowPost') ?>".replace(':id_post', '') + route
    }
</script>