<link rel="stylesheet" href="/dist/css/register.css">

<div class="wrapper">

    <h1 class="title">S'inscrire</h1>
    <p class="sub-title">Vous avez déjà un compte ? <a class="link" href="#">S'inscrire</a></p>
    <?php $this->includePartial("form", $user->getRegisterForm()) ?>
</div>




