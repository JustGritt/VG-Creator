

<link rel="stylesheet" href="/dist/css/login.css">

<main>
    <section id="login">
        <div class="wrapper flex">

            <h1 class="title">Se connecter</h1>
            <p class="sub-title">Vous n'avez pas encore de compte ? <a class="link" href="/register">S'inscrire</a></p>
            <?php $this->includePartial("form", $user->getLoginForm()) ?>
            <a href="/forget" class="link">Mot de passe oublié ?</a>
            <?php
                foreach($providers as $provider){
                    echo "<a class='button--squared ' href=\" {$provider->getAuthorizationUrl()} \">Login with " . $provider->getName() . "</a><br>";
                }
            ?>
        </div>
    </section>


</main>
