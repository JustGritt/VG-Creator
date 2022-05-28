

        <section class="left-panel-dasboard">
            <div class="search-bar">
                <i class="fal fa-search"></i>
                <input placeholder="Rechercher des stats" />
            </div>
            <div class="user-info-dashboard">
                <h1>Hello <?php echo $user->getFirstname()?></h1>
                <h2>Bienvenue !</h2>
            </div>

            <div class="all-card-info-dasboard">
                <div class="card-info-dasboard">
                    <div>
                        <div>
                            <div class="card-info-icons">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h4>Vues</h4>
                            <h5>5666</h5>
                            <h4>Par jour</h4>
                        </div>

                        <div>
                            <div class="card-info-icons">
                                <i class="far fa-compass"></i>
                            </div>
                            <h4>Vues</h4>
                            <h5>5666</h5>
                            <h4>Par jour</h4>
                        </div>

                        <div>
                            <div class="card-info-icons">
                                <i class="fal fa-users"></i>
                            </div>
                            <h4>Utilisateurs</h4>
                            <h5><?php echo ($user->getCountUser($_SESSION['id'])) ?></h5>
                            <h4>Par jour</h4>
                        </div>
                    </div>
                </div>

                <section class="other-cards-info-dasboard">
                    <div class="first-part-other-cards-info">
                        <div>
                            <div class="left-part-card">
                                <div>
                                    <h4>Score SEO</h4>
                                    <h5>Récap global</h5>
                                </div>
                                <h6>5666</h6>
                            </div>
                            <div class="right-part-card">
                                <canvas id="myChart" width="160" height="150"></canvas>
                            </div>
                        </div>

                        <div>
                            <div class="left-part-card">
                                <div>
                                    <h4>Édition</h4>
                                    <h5>Gestionnaire de blog</h5>
                                    <a href="http://localhost/blog/2">Editer</a>
                                </div>
                            </div>
                            <div class="right-part-card">
                                <img src="../dist/assets/icons/star.png" />
                            </div>
                        </div>

                        <div>
                            <div class="left-part-card">
                                <div>
                                    <h4>Abonnements</h4>
                                    <h5>Nombre total</h5>
                                </div>
                                <h6>66</h6>
                            </div>
                            <div class="right-part-card">
                                <div style="width:160px" />
                            </div>
                        </div>
                    </div>
            </div>
        </section>

        <section class="card-publications-dasboard">
            <div>
                <h4>Dernières publications</h4>
                <h5>Vos publications récentes</h5>
            </div>
            <div class="all-articles-publications-dasboard">
                <article>
                    <header>
                        <span>+243 likes</span>
                        <img src="https://images.unsplash.com/photo-1646762223107-aa30c58c48a2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1674&q=80" />
                    </header>
                    <h4>Comment etre le plus beaux chez soi</h4>
                </article>

                <article>
                    <header>
                        <span>+243 likes</span>
                        <img src="https://images.unsplash.com/photo-1646762223107-aa30c58c48a2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1674&q=80" />
                    </header>
                    <h4>Comment etre le plus beaux chez soi</h4>
                </article>

                <article>
                    <header>
                        <span>+243 likes</span>
                        <img src="https://images.unsplash.com/photo-1646762223107-aa30c58c48a2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1674&q=80" />
                    </header>
                    <h4>Comment etre le plus beaux chez soi</h4>
                </article>
                <article>
                    <header>
                        <span>+243 likes</span>
                        <img src="https://images.unsplash.com/photo-1646762223107-aa30c58c48a2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1674&q=80" />
                    </header>
                    <h4>Comment etre le plus beaux chez soi</h4>
                </article>
            
            </div>
        </section>




        <!-- <div class="card-info-dasboard-right">

            </div> -->




<script>
    // const Chart = document.getElementById("myChart");

    // console.log(Chart)

    const myChart = new Chart("myChart", {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', ],
            datasets: [{
                label: null,
                data: [0, 2, 3, 6],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                ],

                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>