<link rel="stylesheet" href="../dist/css/categories.css">

<div id="categories" class="">
    <h3 class="title">Categories d'articles</h3>
    <?php if (!isset($categories)) { ?>
        <p>No categories found</p>
    <?php } else {?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) { ?>
                <tr>
                    <td><?php echo $category->getName() ?></td>
                    <td><button class="button--secondary" id="<?php echo $category->getId(); ?>">Supprimer</button></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <?php $this->includePartial("form", $category->getAddCategorieFrom()) ?>

</div>

<script>
    $(".button--secondary").on("click", function(e){

        $.ajax({
            url: "/dashboard/categories/" + e.target.id,
            type: 'DELETE',
            data: { id: e.target.id },
        }).done(async function(){
            // console.log("success");
            window.location.href = "/dashboard/categories";
        }).fail(function (msg) {
            console.log('Error : ' + msg);
        })
    });
</script>