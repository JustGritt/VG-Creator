<!DOCTYPE html>
<html>
<head>
  <script src="https://cdn.tiny.cloud/1/3pubgqv5ez2b8dk7wyf0ylaplz4xvtcz3llx5ie6ls5w1ezv/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
<input type="submit" id="btnEmail" value="submit" />

  <textarea>
    Welcome to TinyMCE!
  </textarea>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'a11ychecker advcode casechange export formatpainter image editimage linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tableofcontents tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter image editimage pageembed permanentpen table tableofcontents',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    });
    $("#btnEmail").click(function () {
        tinyMCE.triggerSave(true, true);
        $.ajax({
            url: 'http://localhost/blog/3',
            type: "POST",
            data: $('#emailform').serialize(),
            dataType: "json",
            traditional: true
        });
    });
  </script>

</body>
</html>
