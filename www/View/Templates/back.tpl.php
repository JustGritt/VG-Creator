<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta charset="UTF-8">
    <title>Template BACK</title>
    <meta name="description" content="Description de ma page"> -->
    <link rel="stylesheet" href="../../dist/css/main.css">

    <!---<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">--->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/dist/css/main.css">
    <link rel="stylesheet" href="/dist/css/utils/grid.css">
    <link rel="stylesheet" href="/dist/css/back-right-panel.css">
    <link rel="stylesheet" href="/dist/css/back-home.css">
    <link rel="stylesheet" href="/dist/fontawesome-pro-master/css/all.css">
    <link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js" ></script>
    <script defer src="/dist/js/global.js" ></script>
    <script  src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

</head>

<body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <main>
        <div class="body-container-dashboard">
            <?php include "View/Partial/Back/right-panel.partial.php" ?>

            <?php  include "View/" . $this->view . ".view.php"; ?>

            <?php
                use App\Core\FlashMessage;
                $flash = new FlashMessage();
                $this->includePartial("flash_messages", ['errors' => [$flash->getFlash('errors')]]);
                $this->includePartial("flash_messages", ['success' => [$flash->getFlash('success')]]);
            ?>
        </div>
    </main>
</body>

</html>

<script>
    let errorCards = document.querySelectorAll(".error-card");
    errorCards.forEach(function(card) {
        card.querySelector(".error-close").addEventListener("click", function() {
            card.remove();
        });
    });
    
    let successCards = document.querySelectorAll(".success-card");
    successCards.forEach(function(card) {
        card.querySelector(".success-close").addEventListener("click", function() {
            card.remove();
        });
    });
</script>