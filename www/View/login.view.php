<h1>Se connecter</h1>

<?php $this->includePartial("form", $user->getLoginForm()) ?>
<?php  $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=http://localhost/login&response_type=code&client_id=' . GOOGLE_ID . '&access_type=online' ?>
<?php  $login_url_facebook = 'https://www.facebook.com/v13.0/dialog/oauth?&client_id=343703544163557&redirect_uri=http://localhost/login-fb&state=st={state123abc,ds=123456789}' ?>


<p>
   <a class='button' href="<?= $login_url ?>">GOOGLE CONNEXION</a>
</p>

<p>
    <a class='button' name='facebook' href="<?= $login_url_facebook ?>">FACEBOOK CONNEXION</a>
</p>

<form action= "<?= $login_url ?>" method="post">
  <button type="submit">Google connexion</button>
  <button type="submit" formaction="<?= $login_url_facebook ?>">Submit to another page</button>
</form>