

<link rel="stylesheet" href="/dist/css/login.css">

<main>
    <section id="login">
        <div class="wrapper flex">

            <h1 class="title">Se connecter</h1>
            <p class="sub-title">Vous n'avez pas encore de compte ? <a class="link" href="/register">S'inscrire</a></p>
            <?php $this->includePartial("form", $user->getLoginForm()) ?>
            <a href="/forget" class="link">Mot de passe oublié ?</a>

            <?php  $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri='.DOMAIN.'/login&response_type=code&state=VG-CREATOR-GOOGLE&client_id=' . GOOGLE_ID . '&access_type=online' ?>
            <?php  $login_url_facebook = 'https://www.facebook.com/v13.0/dialog/oauth?&client_id=343703544163557&redirect_uri='.DOMAIN.'/login-fb&state=st={state123abc,ds=123456789}' ?>
            <a class='button--squared ' href="<?= $login_url ?>">GOOGLE CONNEXION</a>
            
        </div>
    </section>
</main>
