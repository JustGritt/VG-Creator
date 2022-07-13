<link rel="stylesheet" href="../dist/css/settings.css">

<section id="parametres">

    <h1 class="title">Settings</h1>

    <form method="POST" class="form-group flex">
        <label class='input'>
            <input type="text" id="firstname" name="firstname" class="input__field" placeholder=" " value="<?php echo $user->getFirstname(); ?>">
            <span class='input__label'>Prénom</span>
        </label>
    
        <label class='input'> 
            <input type="text" id="lastname" name="lastname" class="input__field" placeholder=" " value="<?php echo $user->getLastname(); ?>">
            <span class='input__label'>Nom</span>
        </label>

        <label class='input'> 
            <input type="text" id="pseudo" name="pseudo" class="input__field" placeholder=" " value="<?php echo $user->getPseudo(); ?>">
            <span class='input__label'>Pseudo</span>
        </label>

        <label class='input'> 
            <input type="mail" id="email" name="email" class="input__field" placeholder=" " value="<?php echo $user->getEmail(); ?>">
            <span class='input__label'>Adresse mail</span>
        </label>
        <!-- Display more filters -->
        <label for="more-options"> Modifier Mot de passe
            <svg fill="#000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z" />
                <path d="M0-.75h24v24H0z" fill="none" />
            </svg>
        </label>
        <input type="checkbox" name="more-options" id="more-options">
            
        <div class="toggle-filters">
            <div class="filter-checkboxes flex">
                <?php if(($user->getPassword())) : ?>
                <label class='input' for="oldpwd"> 
                    <input type="password" id="oldpwd" name="oldpwd" class="input__field" placeholder=" " value="">
                    <span class='input__label'>Mot de passe actuel</span>
                </label>
                <?php endif ?>

                <label class='input' for="newpwd"> 
                    <input type="password" id="newpwd" name="newpwd" class="input__field" placeholder=" ">
                    <span class='input__label'>Nouveau Mot de passe</span>
                </label>
                
                <label class='input' for="newpwdconfirm"> 
                    <input type="password" id="newpwdconfirm" name="newpwdconfirm" class="input__field" placeholder=" ">
                    <span class='input__label'>Confirmer Mot de passe</span>
                </label>
            </div>
        </div>

        <input type="submit" value="Confirmer">
    </form>


    </form>

    
    
</section>
