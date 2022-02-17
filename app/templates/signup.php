<div class="signup">
    <div class="background"></div>

    <form method="post" action="?signup">
        <h2 class="signup-title">Créer un compte</h2>
        <?php if($message) : ?>
            <?= $message ?></p>
        <?php else : ?>
            <p class='signup-noerror'>&nbsp;</p>
        <?php endif ?>
        <div class="signup-input">
            <label for="email" class="signup-label">Email</label>
            <input type="text" name="email" placeholder="email@email.com">
        </div>
        <div class="signup-input">
            <label for="username" class="signup-label">Pseudo</label>
            <input type="text" name="username" placeholder="Pseudonyme">
        </div>
        <div class="signup-input">
            <label for="password" class="signup-label">Mot de passe (au moins 6 caractères)</label>
            <input type="password" name="password" placeholder="Mot de passe">
        </div>
        <div class="signup-input">
            <label for="password2" class="signup-label">Confirmez le mot de passe</label>
            <input type="password" name="password2" placeholder="Mot de passe">
        </div>
        <input type="submit" class="btn signup-btn" name="submit" value="S'inscrire">
        <a href="?signin" class="signup-link" data-link-alt="Se connecter"><span>Vous avez déjà un compte ?</span></a>
    </form>
</div>
