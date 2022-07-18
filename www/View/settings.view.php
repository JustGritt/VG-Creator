<link rel="stylesheet" href="../../dist/css/settings.css">

<section id="parametres">

    <h1 class="title">Settings</h1>

    <div>
        <button class="delete-button">Delete account</button>
    </div>

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

                <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo \App\Core\Security::generateCsfrToken()?>">
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

    <div class="hidden-popup">
        <div class="popup-content">
            <h3 class="popup-title">Supprimer le compte</h3>
            <p class="popup-text">Voulez-vous vraiment supprimer ce compte ?</p>
            <p class="popup-text">Entrer "SUPPRIMER <?php echo strtoupper($_SESSION['firstname']);?>"</p>
            <input type="text" class="popup-input" value="SUPPRIMER JUST">
            <div class="popup-buttons">
                <input type="submit" value="Delete account" class="delete-button" disabled/>
            </div>
            <span class="close-popup">X</span>
        </div>
        <input type="hidden" name="csrf_token2" id="csrf_token2" value="<?php echo $_SESSION['csrf_token'] ?>">
    </div>


</section>

<script>

    const id_acc = <?php echo $_SESSION['id'] ?>;
    const csrf_token = $('#csrf_token2').val();

    $(document).ready(function(){
        $('.hidden-popup > *').on("cut copy paste",function(e) {
            e.preventDefault();
        });

        $('.hidden-popup input').on('keyup', function(){
            if($(this).val() == "SUPPRIMER <?php echo strtoupper($_SESSION['firstname']);?>"){
                $('.hidden-popup .delete-button').prop('disabled', false);
            } else {
                $('.hidden-popup .delete-button').prop('disabled', true);
            }
        });
    
        
        $(".close-popup").on("click", function(e){
            $('.hidden-popup').toggleClass('show');
        });
        
    });
    
    $(".delete-button").on("click", function(e){

        

        $('.hidden-popup').toggleClass('show');
        // Check if the popup-input is correct and if the delete button is enabled
        if($('.hidden-popup input').val() == "SUPPRIMER <?php echo strtoupper($_SESSION['firstname']);?>" && $('.hidden-popup .delete-button').prop('disabled') == false){

            $.ajax({
                url: "/dashboard/settings/" + id_acc,
                type: 'DELETE',
                data: {"csrf_token": csrf_token, "id_site": id_acc},
            }).done(async function(data){
                console.log("Success", data);
                window.location.href = "/";
            }).fail(function (msg) {
                console.log('FAIL');
            })

        }
    }); 
    


</script>