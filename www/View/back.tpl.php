<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Template BACK</title>
    <meta name="description" content="Description de ma page">

    <link rel="stylesheet" href="../dist/css/main.css">
    <link rel="stylesheet" href="../dist/css/back-right-panel.css">
    <link rel="stylesheet" href="../dist/css/back-home.css">

    <link rel="stylesheet" href="../dist/fontawesome/css/all.css">
    <link rel="stylesheet" href="../dist/fontawesome/css/fontawesome.css">
</head>

<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <main>
        <div class="body-container-dashboard">
            <?php include "View/Partial/Back/right-panel.partial.php" ?>
            <?php include "View/" . $this->view . ".view.php"; ?>
        </div>
    </main>
</body>

</html>