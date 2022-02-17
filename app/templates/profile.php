<div class="profile">
    <div class="background"></div>
    
    <form method="post" action="">
        <h2 class="signin-title">Page de profil</h2>
        <?php if($message) : ?>
            <?= $message ?></p>
        <?php else : ?>
            <p class='profile-noerror'>&nbsp;</p>
        <?php endif ?>

        <div class="profile-input">
            <label for="email" class="profile-label">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>">
        </div>
        <div class="profile-input">
            <label for="username" class="profile-label">Pseudo</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>">
        </div>
        <div class="profile-input">
            <label for="password" class="profile-label">Entrez votre mot de passe pour confirmer</label>
            <input type="password" name="password">
        </div>
        <input type="submit"  class="btn profile-btn" name="submit" value="Modifier">
        <a href="?editpass" class="profile-link" data-link-alt="C'est par là !"><span>Pour modifier votre mot de passe</span></a>
    </form>
</div>

