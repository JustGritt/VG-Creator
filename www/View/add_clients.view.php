<link rel="stylesheet" href="/dist/css/clients.css">
<?php if (!\App\Core\Security::isVGdmin() && !\App\Core\Security::isAdmin()) { ?>
    <h2>Creer votre site et revenez voir cette page !</h2>
<?php } else { ?>
    <main>
        <section id="new-client">
        
            <div class="wrapper flex center-transform">

                <h1 class="title">Invitation de l'utilisateur</h1>

                <?php $this->includePartial("form", $user->getAddClientForm()) ?>

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
</script>