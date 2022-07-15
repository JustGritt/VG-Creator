<section id="comments" class="articles-container">
    <style>
        <?php
        use App\Helpers\Utils;
        use \App\Core\Routing\Router;
        include "dist/css/articles.css";
        ?>
    </style>
    <div class="articles-content">
        <h3 class="title-page">Commentaires</h3>
        <p>Retrouvez ici, vos différents commentaires publiés ou non vérifiés.</p>
        <br>
        <div class="bar-menu">
            <div class="bar-menu-head">
                <span class="<?php if (!isset($_GET['published']) && !isset($_GET['banned'])) echo ("active")  ?>" onclick="changeMenu('')">Nouveaux commentaires</span>
                <span class="<?php if (isset($_GET['published'])) echo ("active")  ?>" onclick="changeMenu('?published')">Publiés</span>
                <span class="<?php if (isset($_GET['banned'])) echo ("active")  ?>" onclick="changeMenu('?banned')">Bannis</span>
            </div>
            <hr>
        </div>

        <div class="grid grid--flex grid--flex-4 grid--flex-3--t grid--flex-2--ms grid--flex-1--s articles-cards">

            <?php foreach ($results as $key => $value) {
                $oDate = new DateTime($value->getCreatedAt());
                $sDate = $oDate->format("Y-m-d");
                // $status = $value->getStatus() == 0 ?  'tag-status-draft' : 'tag-status-publish';
                // $status_name = $value->getStatus() == 0 ?  'Brouillon' : 'Publiée';

                if( $value->getStatus() == 0 ) {
                    $status_name = 'Brouillon';
                    $status = 'tag-status-draft';
                } else if( $value->getStatus() == 1 ) {
                    $status_name = 'Publiée';
                    $status = 'tag-status-publish';
                } else if( $value->getStatus() == 2 ) {
                    $status_name = 'Banni';
                    $status = 'tag-status-banned';
                }
            ?>

            <?php
                $display_buttons = $status_name == "Brouillon" ?  '<input type="submit" value="Bannir"> <input type="submit" value="Confirmer">' : '';
                
                echo '<form method="POST" class="comment-card">'.
                        '<article class="comment-container">

                            <div class="comment-body">
                                <div class="card-badge">
                                    <span class="' . $status . '">'.$status_name.'</span>
                                </div>
                                
                                <h4 class="card-title">' . Utils::truncate($value->getTitle()) . '</h4>
                                <p class="card-body">' . $value->getBody() . '</p>

                            </div>' 
                            . $display_buttons .
                        '</article>
                    </form>';
                
            }?>
        </div>
    </div>
</section>
<!-- <span> 23%</span> -->
<script>
    function changeMenu(type) {
        window.location.href = 'http://localhost/dashboard/comments' + type
    }

    function navigate(route) {
        location.href = "/<?php echo Router::getInstance()->url('comment.showComments') ?>".replace(':id', '')+route
    }

    const comments = document.querySelectorAll('.comment-card');
    //Check if a comment card is clicked
    comments.forEach(comment => {
        comment.addEventListener('click', function() {
            //Get the id of the comment
            const id = this.getAttribute('data-id');
            // Add a class to the comment card
            this.classList.toggle('selected-comment');
        })
    })

</script>

