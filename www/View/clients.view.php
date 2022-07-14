<link rel="stylesheet" href="/dist/css/clients.css">

<section id="clients">
    <?php if (!\App\Core\Security::isVGdmin() && !\App\Core\Security::isAdmin()) { ?>
    <h2>Creer votre site et revenez voir cette page !</h2>
    <?php } else { ?>

    <div class="table-container">
        <div class="title-container">
            <h3>Utilisateurs</h3>
            <a href="clients/add" class="button--primary">+ New user</a>
        </div>
    

        <table>
            <thead>
                <tr>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Email</th>
                    <th scope="col">Pseudo</th>
                    <th scope="col">Role</th>
                    <th scope="col">Ban</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $key => $value) { ?>
                <tr>
                    <form method="POST">
                        <td data-label="firstname"> <input type="text" id="firstname" disabled name="firstname" value="<?php  echo $value['firstname']; ?>" </td> 
                        <td data-label="lastname"> <input type="text" id="lastname" disabled name="lastname" value="<?php echo $value['lastname']; ?>" </td> 
                        <td data-label="email"> <input type="email" id="email" disabled name="email" value="<?php echo $value['email']; ?>" </td>
                        <td data-label="pseudo"> <input type="text" id="pseudo" disabled name="pseudo" value="<?php echo $value['pseudo']; ?>" </td> 
                        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                        <?php  if ($_SESSION['VGCREATOR'] == 1) { ?>
                        <td>
                            <select name="roles" id="roles">
                                <option value="Admin"
                                    <?php if (((isset($_POST['id'] ) &&($value['id'] == $_POST['id'] )? $_POST['roles'] : $value['name']) == 'Admin')) { ?>
                                    selected <?php } ?>>Admin</option>
                                <option value="Member"
                                    <?php if (((isset($_POST['id'] ) &&($value['id'] == $_POST['id']) ? $_POST['roles'] : $value['name']) == 'Member')) { ?>
                                    selected <?php } ?>>Member</option>
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
            </tbody>
            <?php  echo $previous ?>
            <?php echo $next ?>
        </table>
    </div>

    <div class="table-container">
        <h3>Blacklist</h3>
        <table>
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Pseudo</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backlist as $key => $value) { ?>
                <tr>
                    <td><input type="text" id="firstname" disabled name="firstname" value="<?php echo $value['firstname']; ?>" </td> 
                    <td><input type="text" id="pseudo" disabled name="pseudo" value="<?php echo $value['pseudo']; ?>" </td> 
                    <td><input type="text" id="role" disabled name="role" value="<?php echo $value['name']; ?>" </td> 
                </tr> 
                <?php }  ?> 
            </tbody>
        </table>
    </div> 
    <?php } ?>
<section>