<section class="articles-container">
    <style>
        <?php

        include "dist/css/articles.css";
        use \App\Core\Routing\Router;
        include "dist/css/post.css";

        ?>
    </style>
    <div class="articles-content">
        <h3 class="title-page">Edition/Publication d’articles</h3>
        <p>Ici éditez vos différents articles, sans pression. Cliquez sur la section Ajouter un article pour en ajouter. </p>
        <br>
        <div class="bar-menu">
            <div class="bar-menu-head">
             <?php if(isset($id_post)){ ?> <span class="<?php echo ("active")  ?>" onclick="changeMenu('')">Modifier un article</span><?php } ?>
                <span class="<?php if(!isset($id_post)) echo ("active")  ?>" onclick="changeMenu('createPost')"><i class="fa-solid fa-plus"></i> Ajouter un article</span>
            </div>
            <hr>
        </div>

        <section class="form-post-container">
            <form id="form-post" class="form-post" method="POST" >
                <aside>
                    <div class="input-post">
                        <label for="title">Titre de l’article</label>
                        <input value="<?php if (isset($fields['title'])) echo  $fields['title']; else if(isset($post)) echo $post->getTitle() ?>" type="text" name="title" id="title" placeholder="Titre de l'article">
                        <span>Le titre ne doit pas excéder 30 charactères.</span>
                        <?php if (isset($errors['title'])) echo '<span class="err-txt">' . $errors['title'] . '</span>'; ?>
                    </div>
                    <br />
                    <div class="row-section">
                        <div class="input-post">
                            <label for="category">Categorie</label>
                            <select id="category"  name="category">
                                <?php
                                foreach ($categories as $key => $value) {
                                    $selected = isset($post) ? $post->getCategory() == $value->id : isset($fields['category']) && $fields['category'] == $value->id ;
                                    //$categorie_get = isset($post)? $categories[$post->getIdCategorie()]:null;
                                    if ($selected) echo  '<option value='.'"'.$value->id.'"'.'selected >'.$value->name.'</option>';
                                    else echo  '<option value='.'"'.$value->id.'"'.'>'.$value->name.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="space-bar2"></div>
                        <div class="input-post flex-05">
                            <label for="auteur">Auteur</label>
                            <input readonly type="text" name="auteur" id="auteur" placeholder="Auteur" value="<?php if(isset($fields['auteur']) && !empty($fields['auteur'])) echo $fields['auteur'] ;  else if(isset($post)) echo $post->getAuthor()->getFirstname().' '.$post->getAuthor()->getLastname(); else echo $_SESSION['firstname'].' '.($_SESSION['lastname']?? "")  ?>">
                        </div>

                        <input type="hidden" name="author" id="author" value="<?php if(isset($fields['auteur']) && !empty($fields['auteur'])) echo $fields['author'] ; elseif(isset($post)) echo $post->getAuthor()->getId() ; else echo $_SESSION['id'] ?>">
                    </div>
                    <br />
                    <div class="input-post">
                        <label for="editor">Description</label>
                        <textarea id="editor" name="body"></textarea>
                        <span>Merci de faire attention à ce que vous rediger.</span>
                        <?php if (isset($errors['body'])) echo '<span class="err-txt">' . $errors['body'] . '</span>'; ?>
                    </div>

                    <br />
                    <span style="color: black; margin-bottom: 10px; display: block; font-weight: bolder; text-decoration: underline">METAS</span>

                    <div class="row-section justify-space-between">

                    <div class="input-post w-300">
                        <label for="metatitle">Titre</label>
                        <input value="<?php
                        if (isset($fields['metatitle'])) {
                            echo $fields['metatitle'];
                        } else if(isset($post)) echo $post->getMetatitle() ?>" type="text" name="metatitle" id="metatitle" >
                        <span>Saisissez le titre de la page.</span>
                        <?php if (isset($errors['metadata'])) echo '<span class="err-txt">' . $errors['metadata'] . '</span>'; ?>
                    </div>
                        <div class="input-post w-300">
                            <label for="metadescription">Description</label>
                            <input value="<?php if (isset($fields['metadescription'])) echo  $fields['metadescription']; else if(isset($post)) echo $post->getMetadescription() ?>" type="text" name="metadescription" id="metadescription" >
                            <span>Saisissez la description de la page.</span>
                            <?php if (isset($errors['metadata'])) echo '<span class="err-txt">' . $errors['metadata'] . '</span>'; ?>
                        </div>

                        <div class="input-post w-300">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="0" <?php if (isset($post) && $post->getStatus() == 0 ) echo 'selected' ; ?> >Brouillon</option>
                                <option value="1" <?php if (isset($post) && $post->getStatus() == 1 ) echo 'selected' ; ?>>Publié</option>
                            </select>
                        </div>
                    </div>


                </aside>
                <!-- 
                <aside>

                </aside> -->

                <button style="position: relative" class="btm-btn">Enregistrer</button>
            </form>
        </section>

    </div>
</section>
<!-- <span> 23%</span> -->

<script defer>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {
            <?php
                if (isset($fields['body'])){
                echo " editor.setData("."'".  $fields['body']."'" . ")";}
                else if(isset($post)) echo " editor.setData("."'". $post->getBody()  ."'" . ")";
            ?>
        })
        .catch(error => {
            console.error(error);
        });

    // document.getElementById('output').innerHTML = location.search;
    // document.getElementsById('chosen-select').chosen();
    //console.log($)
</script>

<script async>
    function changeMenu(type) {
        if(type === "createPost"){
            window.location.href = "/<?php echo Router::getInstance()->url("post.createPost") ?>";
            return;
        }
        window.location.href = 'http://localhost/dashboard/articles' + type
    }
    var getUrl = window.location;
    var baseUrl = window.location.href;
    document.querySelector('#form-post').setAttribute('action', baseUrl)
</script>