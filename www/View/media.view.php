<?php  use App\Core\Security;?>

<link rel="stylesheet" href="../../dist/css/media.css">

<section id="media">
    <?php if (!Security::isVGdmin() && !Security::isAdmin()) { ?>
        <h2>Creer votre site et revenez voire cette page !</h2>
    <?php } else { ?>
    
        <form  method="post" enctype="multipart/form-data" class="flex">
            <h3>Selectionner une image</h3>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Confirmer" name="submit">
            <input type="hidden" id="csrf_token"  name="csrf_token" value="<?php echo Security::generateCsfrToken()?>">
        </form>
    
        <div class="image-container flex">
            <?php if(empty($documents)): ?>
                <p>Aucun document</p>
            <?php endif ?>
            <?php foreach ($documents as $key => $value) { ?>
                <div class="image-content flex">
                    <img src="<?php echo DOMAIN . '/' . $value->getPath() ?>" alt="Image" width="300" height="200">
                    <button class="button--secondary" id="<?php echo $value->getId() ?>">Supprimmer</button>
                </div>
            <?php } ?>

            <div class="navigation">
                <?php echo $previous ?>
                <?php echo $next ?>
            </div>
        </div>
        
    <?php } ?>
</section>

<script>
    $(".button--secondary").on("click", function(e){;

        $.ajax({
            url: "/dashboard/media/delete/" + e.target.id,
            type: 'DELETE',
            data: {"src": e.target.previousElementSibling.src},
        }).done(async function(){
            window.location.href = "/dashboard/media";
        }).fail(function (msg) {
            console.log('FAIL');
        })

    });
</script>