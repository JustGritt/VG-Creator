
<?php  use App\Core\Security;?>
<form action= '' method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
    <input type="hidden" id="csrf_token"  name="csrf_token" value="<?php echo Security::generateCsfrToken() ?>">
</form>


