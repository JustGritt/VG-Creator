<section class="articles-container">
    <style>
        <?php

        use App\Helpers\Utils;

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

                echo '<a href=/'. \App\Core\Routing\Router::getInstance()->url("post.editPost",["id_post"=> $value['id_post'] ]).'>'.
                    '<article class="card-article" onClick="navigate(' . $value['id_post'] . ')"}" >
                <div class="card-article__image">
                    <img src="https://via.placeholder.com/300.png/09f/fff" alt="" />
                 <!--   <a href="#"></a>  -->
                </div>
                <div class="card-article-body">
                    <h4>' . Utils::truncate($value['title']) . '</h4>
                    <p>' . Utils::truncate($value['body'], 40) . '</p>

                    <div class="info-card-article-body">
                        <span class="' . $status . '">'.$status_name.'</span>
                    </div>
                </div>
            </article></a>';
            }
            ?>
        </div>
    </div>
</section>
<!-- <span> 23%</span> -->
<script>
    function changeMenu(type) {
        window.location.href = 'http://localhost/dashboard/articles' + type
    }

    function navigate(route) {
        var getUrl = window.location;
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
        location.href = baseUrl+ "/articles/"+route
    }
</script>