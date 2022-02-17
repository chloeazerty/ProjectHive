
<div class="menu-burger">
    <div id="burger-wrap">
        <div class="btn-burger">
            <i id="burger-open"class="fas fa-arrow-right"></i>     
            <i id="burger-close" class="fas fa-arrow-left"></i>
        </div>
        
        <nav class="burger-nav">
            <div class="burger-header">
                <h3 class="burger-title">Liste des tableaux</h3>
                <ul class="menu-boards-list">

                </ul>
                <button class="btn burger-add-table">Ajouter un tableau</button>
            </div>
            
            <div class="modify-table">
                <h4 class="burger-liste">Modifier le tableau</h4>
                <form action="" class="edit-board">
                    <label class="label-modify-table" for="title">Titre du tableau</label>
                        <input type="text" class="board-title-input" value="" name="title">
                    <label class="label-modify-table" for="color">Choisir une couleur de fond</label>
                        <input class="edit-board-colorpicker" type="color" value="#ffffff" name="color">
                    <label class="label-modify-table" for="background">Choisir une image de fond</label>
                        <ul class="modify-table-backgrounds">
                        
                        </ul>
                    <button type="submit" class="btn edit-board-submit">Enregistrer</button>
                    <button type="button" class="board-abord">Annuler</button>

                </form>
            </div>

            <div class="burger-new-table">
                <h4 class="burger-liste">Nouveau tableau</h4>
                <form action="" class="new-board">
                    <label class="label-modify-table" for="title">Titre du tableau</label>
                        <input type="text" class="new-board-title-input" value="" placeholder="" name="title">
                    <label class="label-modify-table" for="color">Choisir une couleur de fond</label>
                        <input class="new-board-colorpicker" type="color" value="#ffffff" name="color">
                    <label class="label-modify-table" for="background">Choisir une image de fond</label>
                    <ul class="new-table-backgrounds">

                    </ul>
                    <button type="submit" class="btn new-board-submit">Envoyer</button>
                    <button type="button" class="board-abord">Annuler</button>
                </form>
            </div>
        </nav>
    </div>
</div>
<div class="delete-board-confirm is-hidden">
    <h5 class="delete-board-confirm-title">Supprimer ce tableau ?</h5>
    <p class="delete-board-confirm-text">Le tableau "<span class="delete-board-name"></span>" sera définitivement supprimé ainsi que son contenu.</p>
    <p class="delete-board-confirm-text">Etes-vous sûr(e) ?</p>
    <button class="btn delete-board-submit" board-id="">Supprimer</button>
    <button class="btn delete-board-abord">Annuler</button>
</div>
