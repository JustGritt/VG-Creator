<link rel="stylesheet" href="/dist/css/register.css">

<main>
    <section id="register">

        <div class="wrapper flex center-transform">

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
