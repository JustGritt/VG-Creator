<?php if (!\App\Core\Security::isVGdmin() && !\App\Core\Security::isAdmin()) { ?>
    <h2>Creer votre site et revenez voir cette page !</h2>
<?php } else { ?>

    <div>
        <h3>Utilisateurs</h3>
        <table>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Pseudo</th>
                <th>Role</th>
                <th>Ban</th>
                <th>Action</th>
            </tr>
            <?php foreach ($result as $key => $value) { ?>
            <tr>
                <form class="test" method="POST">
                    <td><input type="text" id="firstname"disabled name="firstname" value="<?php echo $value['firstname']; ?>"</td>
                    <td><input type="text" id="lastname"disabled name="lastname" value="<?php echo $value['lastname']; ?>"</td>
                    <td><input type="email" id="email"  disabled name="email" value="<?php echo $value['email']; ?>"</td>
                    <td><input type="text" id="pseudo" disabled name="pseudo" value="<?php echo $value['pseudo']; ?>"</td>
                    <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                    <?php  if ($_SESSION['VGCREATOR'] == 1) { ?>
                        <td>
                            <select name="roles" id="roles">
                                <option value="Admin" <?php if (((isset($_POST['id'] ) &&($value['id'] == $_POST['id'] )? $_POST['roles'] : $value['name']) == 'Admin')) { ?> selected <?php } ?>>Admin</option>
                                <option value="Member" <?php if (((isset($_POST['id'] ) &&($value['id'] == $_POST['id']) ? $_POST['roles'] : $value['name']) == 'Member')) { ?> selected <?php } ?>>Member</option>
                            </select>
                        </td>
                    <?php } else { ?>
                        <td>
                            <select name="roles" id="roles">
                                <option value="Admin">Admin</option>
                                <option value="Member">Admin</option>
                                <option value="Editor">Admin</option>
                            </select>
                        </td>
                    <?php }  ?>

                    <td>
                        <select name="ban" id="ban">
                            <option value="Inactive">False</option>
                            <option value="Active">True</option>
                        </select>
                    </td>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="csrf_token_update" id="csrf_token_update" value="<?php echo \App\Core\Security::generateCsfrToken()?>">

                    <td><input type="submit" name="submit" value="Update"></td>
                </form>

            </tr>
            <?php }  ?>

        </table>
        <h3>Blacklist</h3>
        <table>
            <tr>
                <th>Firstname</th>
                <th>Pseudo</th>
                <th>Role</th>
            </tr>
            <?php foreach ($backlist as $key => $value) { ?>
            <tr>
                <td><input type="text" id="firstname"disabled name="firstname" value="<?php echo $value['firstname']; ?>"</td>
                <td><input type="text" id="pseudo" disabled name="pseudo" value="<?php echo $value['pseudo']; ?>"</td>
                <td><input type="text" id="role" disabled name="role" value="<?php echo $value['name']; ?>"</td>
            </tr>
            <?php }  ?>

    </div>

    <section>
        <button onclick="console.log('add user')">Add more</button>

    </section>

<?php } ?>
