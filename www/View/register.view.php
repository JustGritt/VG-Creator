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
            <?php foreach ($errors as $error):?>
                <p class="error"><?= $error ?></p>
            <?php endforeach;?>

            <?php var_dump($_GET) ?>
    
        </div>
    </section>
</main>
