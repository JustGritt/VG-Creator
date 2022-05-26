<h1>Tableau de bord</h1>



<?php $this->includePartial("form", $user->getLogoutForm()) ?>

<h1> <?php echo $user->getIdRole() == 2 ? 'Admin' : 'Poulet'  ?> 'Role' </h1>

<a class='button--squared ' href="/client_website">TEST</a>

<?php var_dump($result[1]['id']) ?>

<form action= '' method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
</form>


<div id="wrapper"></div>
<script>
    new gridjs.Grid({
    columns: ["firstname", "lastname" , "Email "],
    data: [
        <?php foreach ($result as $key => $value) { ?>
            [   
                <?php echo "'".$value['firstname']."'";?>,
                <?php  echo "'".$value['lastname']."'"; ?>
            ],
        <?php }  ?>
       
        
    ],
    search: {
        enabled: true
    },
    pagination: {
        enabled: true,
        limit: 2,
        summary: false
    }
    }).render(document.getElementById("wrapper"));
</script>

