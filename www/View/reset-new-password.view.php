<h1>Reset password </h1>


<?php $this->includePartial("form", $user_recovery->getResetForm()) ?>

<?php
    use App\Core\FlashMessage;
    $flash = new FlashMessage();
    $this->includePartial("flash_messages", ['errors' => [$flash->getFlash('errors')]]);
?>
