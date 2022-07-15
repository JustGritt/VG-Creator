<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta charset="UTF-8">
    <title>Template BACK</title>
    <meta name="description" content="Description de ma page"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.19.5/grapes.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.19.5/css/grapes.min.css">
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
    <script src="https://unpkg.com/grapesjs"></script>


    <script src="../../dist/js/editor-custom.js"></script>
    <!-- Load Editor.js's Core -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
</head>

<body class="boo">

    <main>
        <div class="body-container-dashboard">
            <?php  include "View/" . $this->view . ".view.php"; ?>
        </div>
    </main>

</body>


</html>