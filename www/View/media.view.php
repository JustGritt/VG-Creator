
<?php  use App\Core\Security;?>


<?php if (!Security::isVGdmin() || Security::isMember()) { ?>
    <h2>Creer votre site et revenez voire cette page !</h2>
<?php } else { ?>

<form action= '' method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
    <input type="hidden" id="csrf_token"  name="csrf_token" value="<?php echo Security::generateCsfrToken() ?>">
</form>

<div class="img-ctn">
    <?php if(empty($documents)): ?>
        <p>Aucun document</p>
    <?php endif ?>
    <?php foreach ($documents as $key => $value) { ?>
        <?php var_dump($value->getPath()) ?>
    
        <img src="<?php echo DOMAIN . '/' . $value->getPath() ?>" alt="Image" width="300" height="200">
    <?php } ?>
</div>
<?php } ?>

