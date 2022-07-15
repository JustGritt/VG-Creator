<link href="/dist/css/forget.css" rel="stylesheet">

<main id="password">
    <section class="flex">
        <h1 class="title">Mot de passe oublié ?</h1>
        <?php $this->includePartial("form", $user->getPasswordResetForm()) ?>
    </section>
</main>