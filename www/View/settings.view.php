<h1>sdd</h1>

<div id="wrapper" style='margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);'></div>
<script>
    new gridjs.Grid({
    columns: ["firstname", "lastname" , "Email "],
    data: [
        <?php foreach ($result as $key => $value) { ?>
            [   
                <?php echo "'".$value['firstname']."'";?>,
                <?php  echo "'".$value['lastname']."'"; ?>,
                <?php  echo "'".$value['email']."'"; ?>
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

