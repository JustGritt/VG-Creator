<section id="chooseAccount" class="flex">
    <?php foreach ($site as $key => $value) { ?>
        <form class="test" method="POST">
            Se connecter en tant que <strong><label for="role"> <?php echo $value['role']; ?></strong> </label> pour le site <strong><label for="site"> <?php echo strtoupper($value['site']); ?> </label></strong>
            <td><input type="hidden" id="role" name="role" value="<?php echo $value['role']; ?>"</td>
            <td><input type="hidden" id="site" name="site" value="<?php echo $value['site']; ?>"</td>
            <td><input type="hidden" id="id_site" name="id_site" value="<?php echo $value['id']; ?>"</td>
            <input type="submit" value="Valider">
            
        </form>
    <?php } ?>


    <?php if (isset($previous))  echo $previous ?>
    <?php if (isset($next))  echo $next ?>

</section>