
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
    //TODO: Add Success message styles
    if (isset($data['success']) && !empty($data['success'])) {
        foreach($data['success'] as $message) {
            if(isset($message) && !empty($message)) {
                echo '
                    <div class="success-card flex">
                        <span class="success-close">X</span>
                        <p class="success-message">'.$message.'</p>
                    </div>
                ';
            }
        }
    }
?>