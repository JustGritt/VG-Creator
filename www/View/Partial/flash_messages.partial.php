
<!-- <link rel="stylesheet" href="/dist/css/login.css"> -->

<?php
    if (isset($data['errors']) && !empty($data['errors'])) {
        foreach($data['errors'] as $message) {
            if(isset($message) && !empty($message)) {
                echo '
                <div class="error-card flex">
                    <span class="error-close">X</span>
                    <p class="error-message">'.$message.'</p>
                </div>
            ';
            }
        }
    }
    //TODO: add success message
    if (isset($data['success']) && !empty($data['success'])) {
        foreach($data['success'] as $message) {
            if(isset($message) && !empty($message)) {
                echo '
                    <div class="error-card flex">
                        <span class="error-close">X</span>
                        <p class="error-message">'.$message.'</p>
                    </div>
                ';
            }
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