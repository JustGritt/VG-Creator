<form method="<?= $data["config"]["method"]??"POST" ?>" action="<?= $data["config"]["action"]??"" ?>" enctype="<?= $data["config"]["enctype"]??"" ?> ">
    <?php foreach ($data["inputs"] as $name=>$input) :?>

    <input
        type="<?= $input["type"]??"hidden" ?>"
        name="<?= $name?>"
        placeholder="<?= $input["placeholder"]??"" ?>"
        id="<?= $input["id"]??"" ?>"
        class="<?= $input["class"]??"" ?>"
        value="<?= $input["value"]??"" ?>"
        <?= empty($input["required"])?"":'required="required"' ?>
    />
    <br>

    <?php endforeach;?>
    <input type="submit" value="<?= $data["config"]["submit"]??"Valider" ?>">
</form>
