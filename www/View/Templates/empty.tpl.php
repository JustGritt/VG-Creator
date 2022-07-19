<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.19.5/grapes.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.19.5/css/grapes.min.css">
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
    <script src="https://unpkg.com/grapesjs"></script>
    <meta charset="utf-8">
    <title>Vg-Creator Demo - Homepager</title>
    <meta content="Best VG creator Responsive Website" name="description">
    <link href="https://unpkg.com/grapick/dist/grapick.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/dist/js/editor-custom.js">
    </script>
    <script src="https://unpkg.com/grapesjs-template-manager@1.0.7/dist/grapesjs-template-manager.min.js"></script>
    <link href="https://unpkg.com/grapesjs-project-manager/dist/grapesjs-project-manager.min.css" rel="stylesheet">
    <script defer src="/dist/js/global.js" ></script>
    <link rel="stylesheet" href="/dist/css/template.css">
</head>

<body class="boo">
    <main>
        <div class="body-container-dashboard" id="gjs">
            <?php  include "View/" . $this->view . ".view.php"; ?>
        </div>
    </main>
    <div id="snackbar">Some text some message..</div>
</body>



</html>


