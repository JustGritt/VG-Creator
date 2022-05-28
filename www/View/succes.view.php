<style>
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    grid-gap: 20px;
}

.card {
    display: grid;
    grid-template-rows: max-content 200px 1fr;
}

.card img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
</style>

<?php foreach ($result as $key => $value): ?>
    <div class="cards">
        <article class="card">
            <header>
                <h2>A short heading</h2>
            </header>    
            <div class="content">
                <p>Short content.</p>
                <p><?php echo "'".$value['firstname']."'";?></p>
                <p><?php  echo "'".$value['lastname']."'"; ?></p>
                <p><?php  echo "'".$value['email']."'"; ?></p>
            </div>
            <footer>I have a footer!</footer>
        </article>
            
    </div>
<?php endforeach; ?>
