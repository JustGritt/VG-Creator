<div>
    <h3>Categories</h3>
    <?php if (!isset($categories)) { ?>
        <p>No categories found</p>
    <?php } else {?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) { ?>
                <tr>
                    <td><?php echo $category->getName() ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>


    <?php $this->includePartial("form", $category->getAddCategorieFrom()) ?>

</div>