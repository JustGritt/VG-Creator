
<?php if (!isset($result) || empty($result)) { ?>
    <h2>Creer votre site et revenez voire cette page !</h2>
<?php } else { ?>

    <div>
        //create table of user
        <table>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Pseudo</th>
                <th>Role</th>
                <th>Active</th>
                <th>id</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($result as $key => $value) { ?>
            <tr>
                <form class="test" action="" method="POST">
                    <td><input type="text" disabled name="firstname" value="<?php echo $value['firstname']; ?>"</td>
                    <td><input type="text" disabled name="lastname" value="<?php echo $value['lastname']; ?>"</td>
                    <td><input type="email" disabled name="email" value="<?php echo $value['email']; ?>"</td>
                    <td><input type="text" disabled name="pseudo" value="<?php echo $value['pseudo']; ?>"</td>
                    <?php if ($_SESSION['VGCREATOR'] == 1) { ?>
                        <td>
                            <select name="roles" id="roles">
                                <option value="admin">Admin</option>
                            </select>
                        </td>
                    <?php } else { ?>
                        <td>
                            <select name="roles" id="roles">
                                <option value="admin">Admin</option>
                                <option value="admin">Admin</option>
                                <option value="admin">Admin</option>
                            </select>
                        </td>
                    <?php }  ?>

                    <td>
                        <select name="status" id="status">
                            <option value="admin">Active</option>
                            <option value="admin">Non-Active</option>
                        </select>
                    </td>
                    <input type="hidden" name="id" value="<?php echo $value['id']; ?>"</td>
                    <td><input type="submit" name="submit" value="Update"></td>
                    <td><a class='button--squared 'href='/dashboard/clients/edit'>Edit</a></td>
                    <td><a class='button--squared ' href='/dashboard/clients/delete'>Delete</a></td>
                </form>

            </tr>
            <?php }  ?>

        </table>


    </div>

    <section>
        <?php $this->includePartial("form", $user->getInvitationForm()) ?>
    </section>

<?php } ?>