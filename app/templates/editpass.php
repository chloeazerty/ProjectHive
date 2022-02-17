<div class="editpass">
    <div class="background"></div>

    <form method="post" action="">
        <h2 class="editpass-title">Mot de passe</h2>
        <?php if($message) : ?>
            <?= $message ?></p>
        <?php else : ?>
            <p class='editpass-noerror'>&nbsp;</p>
        <?php endif ?>

        <div class="editpass-input">
            <label for="password" class="editpass-label">Mot de passe actuel</label>
            <input type="password" name="password">
        </div>
        <div class="editpass-input">
            <label for="newPassword" class="editpass-label">Nouveau mot de passe (au moins 6 caractères)</label>
            <input type="password" name="newPassword">
        </div>
        <div class="editpass-input">
            <label for="newPassword2" class="editpass-label">Confirmez votre nouveau mot de passe</label>
            <input type="password" name="newPassword2">
        </div>
        <input type="submit"  class="btn editpass-btn" name="submit" value="Modifier">
        <a href="?profile" class="editpass-link" data-link-alt="C'est par là !"><span>Pour revenir au profil</span></a>
    </form>
</div>