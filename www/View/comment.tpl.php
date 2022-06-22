<?php

?>

<!DOCTYPE html>

<html>

<head>
    <title>Comments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../dist/css/comments.css">
</head>

<body>
    <div class="comments-container">
        <h2>Comments</h2>
        <form method="POST" id="comment_form">
            <div class="form-group">
                <label class="input">
                    <input class="input__field" type="text" name="comment_name" id="comment_name" placeholder=" " />
                    <span class="input__label">@Pseudo</span>
                </label>
            </div>

            <textarea name="comment_content" id="comment_content" placeholder="Commentaire" rows="5"></textarea>
        
            <div class="form-group">
                <input type="hidden" name="comment_id" id="comment_id" value="0" />
                <input type="submit" name="submit" id="submit" class="btn btn-info" value="Publier" />
                <input type="submit" name="submit" id="submit" class="btn btn-info" value="Annuler" />
            </div>
        </form>

        <span id="comment_message">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam, perferendis.</span>
        <div id="display_comment">Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi, velit!</div>
    </div>


</body>
</html>

<script>

    document.getElementById('comment_form').addEventListener('submit', loadComment);

    function loadComment(e) {
        e.preventDefault();
        var name = document.getElementById('comment_name').value;
        var content = document.getElementById('comment_content').value;
        var id = document.getElementById('comment_id').value;

        if (name != '' && content != '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('comment_form').reset();
                    loadComments();
                }
            };
            xhr.open('POST', 'comment.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('comment_name=' + name + '&comment_content=' + content + '&comment_id=' + id);
        } else {
            alert('Please enter your name and comment');
        }
    }
    
    function load_comment() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                $('#display_comment').html(this.responseText);
            }
        };
    }
    
    function deleteComment(id) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                load_comment();
            }
        };
        xhr.open('POST', 'delete_comment.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + id);
    }
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('reply')) {
            var comment_id = e.target.id;
            $('#comment_id').val(comment_id);
            $('#comment_name').focus();
        }
    });
    
    load_comment();

</script>