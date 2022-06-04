<h1>sdd</h1>

<div id="wrapper" style='margin: 0;
  position: absolute;
  top: 50%;
  left: 60%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);'></div>
<script>
    new gridjs.Grid({
    columns: ["firstname", "lastname" , "email", "pseudo", "role"],
    data: [
        <?php foreach ($result as $key => $value) { ?>
            [   
                <?php echo "'".$value['firstname']."'";?>,
                <?php  echo "'".$value['lastname']."'"; ?>,
                <?php  echo "'".$value['email']."'"; ?>,
                <?php  echo "'".$value['pseudo']."'"; ?>,
                <?php  echo "'".$value['name']."'"; ?>

            ],
        <?php }  ?>
       
        
    ],
    search: {
        enabled: true
    },
    pagination: {
        enabled: true,
        limit: 5,
        summary: false
    }
    }).render(document.getElementById("wrapper"));
</script>

