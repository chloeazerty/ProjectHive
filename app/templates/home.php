<?php
require_once 'includes/header.php';
?>

<div class="accueil">
    <div class="background"></div>

    <div class="row-home col-6">
        <div class="text">
            <h1>PROJECT HIVE</h1>
            <p class="desc"> Project Hive est une application en ligne qui permet une gestion efficace de projets, qu’il soient privés, professionnels, individuels, d’équipe, etc... Intuitif, simple d’utilisation, il est personnalisable et gratuit. </p>
            <p class="desc"> Avec Project Hive, l’utilisateur peut créer des listes pour organiser son projet, le découper en tâches, avec application d’échéances si besoin. L’avancée du projet apparaît lisiblement à l’écran dans un style propre à chaque utilisateur ou équipe, consultable sur ordinateur ou sur appareil mobile.</p>
            <?php if (empty($_SESSION)) { ?>
                <a href="?signup" class="btn btn-home">S'inscrire</a>
                <p class="grey">En cliquant sur le bouton "S'inscrire", vous acceptez les conditions générales d'utilisation.</p>
            <?php } ?>
        </div>

        <div class="preview">
            <img class="preview-img" src="images/hive_preview.png" alt="">
        </div>
    </div>

</div>

<?php
require_once 'includes/footer.php';
?>