<link href="/dist/css/forget.css" rel="stylesheet">

<main id="reset">
    <section class="flex">
        <h1 class="title">Choisir un nouveau mot de passe</h1>
        <?php $this->includePartial("form", $user_recovery->getResetForm()) ?>
        <?php
            use App\Core\FlashMessage;
            $flash = new FlashMessage();
            $this->includePartial("flash_messages", ['errors' => [$flash->getFlash('errors')]]);
        ?>
    </section>
</main>




