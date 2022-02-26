<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Template FRONT</title>
    <meta name="description" content="Description de ma page">
    <link rel="stylesheet" href="../dist/css/main.css">
</head>
<body>

    <?php include "View/".$this->view.".view.php"; ?>

    
    <header class="flex">
        <a href="#" class="left-items">VG-Creator</a>
        <nav class="flex">
            <ul>
                <li><a href="#" class="link">Accueil</a></li>
                <li><a href="#" class="link">Nos projets</a></li>
                <li><a href="#" class="link">Qui sommes-nous ?</a></li>
                <li><a href="#" class="link">Nous contacter</a></li>
            </ul>
        </nav>

        <div class="flex right-items">
            <label class="switch">
                <input type="checkbox">
                <span class="slider round"></span>
            </label>
            <a href="#" class="button--secondary">S'inscrire</a>
            <a href="#" class="button--secondary">Se connecter</a>
        </div>
    </header>


</body>
</html>