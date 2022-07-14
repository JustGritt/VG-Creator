<link rel="stylesheet" href="/dist/css/register.css">

<main>
    <section id="register">

        <div class="wrapper flex">

            <h1 class="title">S'inscrire</h1>
            <p class="sub-title">
                Vous avez déjà un compte ? 
                <a class="link" href="/login">Se connecter</a>
            </p>

            <?php $this->includePartial("form", $user->getRegisterForm()) ?>

            <?php
            use App\Core\FlashMessage;
            $flash = new FlashMessage();
            $this->includePartial("flash_messages", ['errors' => [$flash->getFlash('errors')]]);
            //$this->includePartial("flash_messages", ['success' => [$flash->getFlash('errors')]]);
            ?>

            <!-- <div class="form-group flex">
                <label class='input' for="newpwd"> 
                    <input type="password" id="newpwd" name="newpwd" class="input__field" placeholder=" ">
                    <span class='input__label'>Nouveau Mot de passe</span>
                </label>
                
                <label class='input' for="newpwdconfirm"> 
                    <input type="password" id="newpwdconfirm" name="newpwdconfirm" class="input__field" placeholder=" ">
                    <span class='input__label'>Confirmer Mot de passe</span>
                </label>
            </div>         -->


        </div>
    </section>
</main>


<!-- <label class='input' for="newpwdconfirm"> 
    <input type="password" id="newpwdconfirm" name="newpwdconfirm" class="input__field" placeholder=" ">
    <span class='input__label'>Confirmer Mot de passe</span>
</label> -->

<script>
    const form = document.querySelector('.form-container form');
    const inputs = form.querySelectorAll('input.input__field');

    inputs.forEach(input => {
        // console.log('input', input);

        input.id === 'pseudoForm' ? input.setAttribute('autocomplete', 'username') : null;
        input.type === 'password' ? input.setAttribute('autocomplete', 'new-password') : null;

        const label = document.createElement('label');
        label.classList.add('input');
        label.setAttribute('for', input.id);

        const span = document.createElement('span');
        span.classList.add('input__label');
        span.innerHTML = input.placeholder;

        label.appendChild(input);
        label.appendChild(span);
        form.insertBefore(label, input);
        
    });

    
</script>

