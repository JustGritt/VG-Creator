
<div class="mainbox">
    <?php $err = isset($error)? $error->getMessage() : "Maybe this page moved? Got deleted? Is hiding out in quarantine? Never existed in the first place?" ?>
    <div class="err">4</div>
    <i class="far fa-question-circle fa-spin"></i>
    <div class="err2">4</div>
    <div class="msg"><?php if(isset($error))  echo $error->getMessage() . "Maybe this page moved? Got deleted? Is hiding out in quarantine? Never existed in the first place?" ?><p>Let's go <a href="/">home</a> and try from there.</p></div>
</div>