<h1>Tableau de bord</h1>


<h1> <?php echo $_SESSION['VGCREATOR'] == 1 ? 'Admin' : 'Poulet'  ?> 'Role' </h1>

<a class='button--squared' target='_blank' href="/client_website">TEST</a>

<form action= '' method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
</form>


