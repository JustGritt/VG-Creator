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

        </div>
    </section>
</main>

<script>
    const form = document.querySelector('.form-container form');
    const inputs = form.querySelectorAll('input.input__field');
    let index = 0;

    inputs.forEach(input => {
        // console.log('input', input);

        input.id === 'pseudoForm' ? input.setAttribute('autocomplete', 'username') : null;
        input.type === 'password' ? input.setAttribute('autocomplete', 'new-password') : null;

        const label = document.createElement('label');
        label.classList.add('input');
        label.setAttribute('for', input.id);

        const span = document.createElement('span');
        span.classList.add('input__label');
        span.innerHTML = input.name[0].toUpperCase() + input.name.slice(1);;

        label.appendChild(input);
        label.appendChild(span);
        form.insertBefore(label, form.firstChild);
        index++;
    });

    form.querySelectorAll("br").forEach(br => {
        br.remove();
    });
</script>

