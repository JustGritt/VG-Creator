<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Template Blank</title>
    <meta name="description" content="Description de ma page">
    <link rel="stylesheet" href="/dist/css/main.css">
</head>

<body>
    <main>
        <div class="body-container-dashboard">
            <?php include "View/" . $this->view . ".view.php"; ?>
            <?php
            use App\Core\FlashMessage;
            $flash = new FlashMessage();
            $this->includePartial("flash_messages", ['errors' => [$flash->getFlash('errors')]]);
            //$this->includePartial("flash_messages", ['success' => [$flash->getFlash('errors')]]);
            ?>
        </div>
    </main>
</body>

</html>