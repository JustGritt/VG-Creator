
<h1>Articles</h1>>

<?php foreach ($result as $key => $value): ?>
    <p><?php echo "'".$value['firstname']."'";?></p>
    <p><?php  echo "'".$value['lastname']."'"; ?></p>
    <p><?php  echo "'".$value['email']."'"; ?></p>
<?php endforeach; ?>
