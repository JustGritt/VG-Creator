
<section>
    
</section>
<h1>Confirmationd de votre email</h1>

<?php
    use App\Core\FlashMessage;
    $flash = new FlashMessage();
    $this->includePartial("flash_messages", ['errors' => [$flash->getFlash('errors')]]);
?>

