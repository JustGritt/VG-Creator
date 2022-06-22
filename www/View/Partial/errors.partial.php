
<link rel="stylesheet" href="/dist/css/login.css">
<?php 
    foreach($data['errors'] as $error) {
        if(isset($error)) {
            echo '
                <div class="error-card flex">
                    <span class="error-close">X</span>
                    <p class="error-message">'.$error.'</p>
                </div>
            ';
        }
    }

    echo '
        <script>
            let errorCard = document.querySelectorAll(".error-card");
            
            errorCard.forEach(function(card) {
                card.querySelector(".error-close").addEventListener("click", function() {
                    card.remove();
                });
            });

        </script>
    '
?>