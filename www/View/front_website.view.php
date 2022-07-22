<main>
    <section id="home">
        <div class="hero-content flex">

            <h1 class="title">Faites de vos idées une réalité</h1>
            <h2 class="sub-title">Simplifiez vous la vie et optez pour un site web de qualité</h2>
            <div class="hero-input">
                <form action="/register" method="GET">
                    <input type="email" name="mail" id="mail" placeholder="Saisir votre adresse mail">
                    <input type="submit" value="S'inscrire" class="button" href="/register">
                </form>
            </div>
            <br />
            <div class="hero-input" style="
                position: fixed;
                bottom: 0;
                right: 0;
                margin: 12px;
                background-color: white;
                border-radius: 12px;
                ">
                <form action="/subscribe-newsletter" method="POST">
                    <input type="email" name="email" id="mail" placeholder="Saisissez votre adresse mail">
                    <input type="submit" value="Newsletter" class="button" href="/register">
                </form>
            </div>
        </div>

        <div class="scrolling-container">
            <picture>
                <img class="hero-img" src="https://i.ibb.co/tMsQmkV/Image.png" alt="Image" border="0">
            </picture>
        </div>
    </section>

    <section id="templates">
        <h3>Personalisez vos templates</h3>
        <p>Choisissez parmis une dizaine de templates différents</p>
        <div class="template-showcase flex">
            <div class="template-card">
                <picture>
                    <img src="https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Template 1">
                </picture>
            </div>
            <div class="template-card">
                <picture>
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Template 2">
                </picture>
            </div>
            <div class="template-card">
                <picture>
                    <img src="https://images.unsplash.com/photo-1606814540563-5c02d62fd409?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1212&q=80" alt="Template 3">
                </picture>
            </div>
            <div class="template-card">
                <picture>
                    <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1169&q=80" alt="Template 4">
                </picture>
            </div>
        </div>
    </section>

    <section id="faq">
        <div class="faq-wrapper">
            <h3>FAQ</h3>

            <div class="faq-container">
                <input type="radio" name="show-icon" id="acc1" checked="checked">
                <label for="acc1">1. Qu'est-ce que VG-CREATOR ?</label>
                <div class="content">
                    <div class="inner">
                        VG-CREATOR est un CMS de création de site web. Il permet de créer des sites web personnalisés en utilisant des templates de sites web. Il est également possible de créer des sites web personnalisés en utilisant des templates de sites web.
                    </div>
                </div>
                <input type="radio" name="show-icon" id="acc2">
                <label for="acc2">2. L'origine du projet VG-CREATOR</label>
                <div class="content">
                    <div class="inner">
                        Le projet a été créé par un groupe d'étudiants en troisième année d'Ingénierie du Web à l'ESGI (École supérieure de génie informatique) dans le cadre d'un projet annuel.
                    </div>
                </div>
                <input type="radio" name="show-icon" id="acc3">
                <label for="acc3">3. </label>
                <div class="content">
                    <div class="inner">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, non dicta itaque animi quia nihil rerum eaque voluptas! Inventore commodi fugiat neque eos vero eum provident odio maiores doloribus fugit.
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<!-- <div class="newsletter-container">
    <div class="wrapper">
        <button class="notify">
            <div class="notify-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 24">

                    <path d="M12,24a3.07,3.07,0,0,0,3-3H9A3.12,3.12,0,0,0,12,24Z" transform="translate(-1.76)" fill="#ffffff" />

                    <path d="M15.14,3.95a2.1,2.1,0,0,1-1-1.82h0a2.1,2.1,0,1,0-4.19,0h0a2.1,2.1,0,0,1-1,1.82C4.2,6.66,6.88,15.66,2,17.25V19H22V17.25C17.12,15.66,19.8,6.66,15.14,3.95ZM12,3a1,1,0,1,1,1-1A1,1,0,0,1,12,3Z" transform="translate(-1.76)" fill="#ffffff" />

                    <line class="notify-off-line" x1="21.24" y1="2.65" x2="0.92" y2="22.97" stroke="" stroke-miterlimit="10" />

                    <line class="notify-off-line" x1="20.24" y1="1.98" x2="0.53" y2="21.69" fill="none" stroke="#ffffff" stroke-miterlimit="10" stroke-width="1.5" />
                </svg>
            </div><span class="notify-label">Get Notification</span><span class="notify-off-label">Turn Off Notifications</span>
        </button>
        <div class="form">
            <input class="email-input" placeholder="Your email here." />
            <input class="name-input" placeholder="Your name here." />
        </div>
        <button class="newsletter">
            <div class="newsletter-icon">
                <svg version="1.1" id="iconmonstr" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
                    <path id="paper-plane-1" style="fill:#FFFFFF;" d="M24,0l-6,22l-8.129-7.239l7.802-8.234L7.215,13.754L0,12L24,0z M9,16.669V24l3.258-4.431L9,16.669z" />
                </svg>
            </div><span class="start-label">Get Newsletter</span><span class="form-label">Add Me</span>
        </button>
    </div>
</div> -->