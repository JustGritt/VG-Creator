<h1>Se connecter</h1>

<?php $this->includePartial("form", $user->getLoginForm()) ?>
<?php  $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=http://localhost/login&response_type=code&client_id=' . GOOGLE_ID . '&access_type=online' ?>


<p>
   <a class='button' href="<?= $login_url ?>">GOOGLE CONNEXION</a>
</p>