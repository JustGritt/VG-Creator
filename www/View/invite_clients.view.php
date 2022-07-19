<link rel="stylesheet" href="/dist/css/clients.css">
<?php if (!\App\Core\Security::isVGdmin() && !\App\Core\Security::isAdmin()) { ?>
    <h2 class="title">Créez votre site et revenez voir cette page !</h2>
<?php } else { ?>
    <main>
        <section id="invite-client">
        
            <div class="wrapper flex center-transform">

                <h1 class="title">Invitation de l'utilisateur</h1>
                <p>Remplir le pseudo ou le mail pour l'utilisateur</p>

                <?php $this->includePartial("form", $user->getInviteClientForm()) ?>

                <select name="roles" id="roleSelector">
                    <option value="Manager" selected>Manager</option>
                    <option value="Moderator">Moderator</option>
                    <option value="Editor">Editor</option>
                </select>

            </div>
        </section>
    </main>
<?php } ?>

<script>
    const roleSelector = document.getElementById("roleSelector");
    const formRole = document.querySelector("form input[name='roles']");
    const form = document.querySelector("form input[name='roles']");
    form.parentNode.insertBefore(roleSelector, form);

    roleSelector.addEventListener("change", function() {
        let roleValue = roleSelector.options[roleSelector.selectedIndex].value;
        formRole.value = roleSelector.value;
    });
;
    const email  = document.querySelector("form input[name='email']");
    const pseudo  = document.querySelector("form input[name='pseudo']");
    console.log('email', email);

    document.addEventListener("keyup", function() {
        email.value.length > 0 ? pseudo.setAttribute('disabled', pseudo) : pseudo.removeAttribute('disabled', pseudo) ;
        pseudo.value.length > 0 ? email.setAttribute('disabled', email) : email.removeAttribute('disabled', email) ;
    });

</script>