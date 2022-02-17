<?php
require_once 'includes/header.php';
?>

<div class="not_found">
    <div class="background"></div>

    <div class="not_found-text col-6">
        <h2>Page non trouvée</h2>
        <p class="compass"><i class="far fa-compass"></i></p>
        <?php if(isset($_GET['board']) && empty($_SESSION['userId'])) : ?>
            <p class="maybe">Ce tableau sera peut-être accessible si vous vous connectez.</p>
        <?php endif ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>