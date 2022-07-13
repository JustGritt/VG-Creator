<link rel="stylesheet" href="../dist/css/settings.css">

<section id="parametres">

    <h1 class="title">Settings</h1>

    <form method="POST">
        <label for="firstname">Firstname</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $user->getFirstname(); ?>">
        
        <label for="lastname">Lastname</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $user->getLastname(); ?>">
        
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" value="<?php echo $user->getPseudo(); ?>">
        
        <label for="email">Email</label>
        <input type="mail" id="email" name="email" value="<?php echo $user->getEmail(); ?>">
        
        <!-- Display more filters -->
        <label for="more-options">
            Change password
                <svg fill="#000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z" />
                    <path d="M0-.75h24v24H0z" fill="none" />
                </svg>
            </label>
            <input type="checkbox" name="more-options" id="more-options">
            
            <div class="toggle-filters">
                <div class="filter-parameter">
                    <div class="filter-checkboxes">
                        
                        <label for="oldpwd">Current Password</label>
                        <input type="password" name="oldpwd" id="old-password">

                        <label for="oldpwd">New password</label>
                        <input type="password" name="newpwd" id="new-password">

                        <label for="oldpwd">Confirm new password</label>
                        <input type="password" name="newpwdconfirm" id="new-password-confirm">

                    </div>
                </div>
                
            </div>
            <input type="submit" value="confirm">
        </div>

    </form>

    
    
</section>
