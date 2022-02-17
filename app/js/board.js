let app = {
    // Penser à modifier ici l'adresse de votre API
    baseUrl: 'http://localhost:8888/cda_projet_project_hive/app/?api/',
    
    maxListeOrderNb: 0,
    loadedBoard: {
        boardId: null,
        title: "",
        color: "",
        backgroundId: null,
        backgroundUrl: "",
    },
    backgrounds: {},
    
    init: function() {
        //console.log("initialisation ...")
        app.loadBackgrounds();

        $('#burger-open').on('click', app.handleOpenMenu);
        $('#burger-close').on('click', app.handleCloseMenu);

        $('.add-liste').on('submit', app.handleCreateNewListe);
        $('.new-board').on('submit', app.handleCreateNewBoard);
        $('.board-listes').on('submit', '.edit-card-form', app.handleCreateOrEditCard);
        $('.edit-board').on('submit', app.handleEditBoard);
        $('.board-listes').on('submit', '.liste-header-title-form', app.handleUpdateListeName);
        
        $('.menu-boards-list').on('click', '.fa-paint-brush', app.handleOpenEditBoardForm);
        $('.menu-boards-list').on('click', '.fa-trash-alt', app.handleConfirmDeleteBoard);
        $('.burger-add-table').on('click', app.handleFormNewBoard);
        $('.board-abord').on('click', app.handleBoardFormAbord);
        $('.add-liste-input').on('blur', app.handleBlurNewListTitle);
        $('.board-listes').on('dblclick', '.liste-header-show', app.handleDblClickListTitle);
        $('.board-listes').on('blur', '.liste-header-title-input', app.handleBlurListTitle);
        $('.board-listes').on('click', '.add-card', app.handleFormNewCard);
        $('.board-listes').on('click', '.modify-card', app.handleClickModifyCard);
        $('.board-listes').on('click', '.abord-btn', app.handleCardFormAbord);
        $('.menu-boards-list').on('click', '.boards-list-item', app.handleSelectBoard);
        $('.burger-nav').on('click', '.background-thumb', app.selectBackground);


        $('.board-listes').on('click', '.delete-liste', app.handleDeleteListe);
        $('.board-listes').on('click', '.delete-card', app.handleDeleteCard);

        $(".communication").click(function(){
            $('.social-icons').toggleClass('open');
        });

        // l'élément n'existe pas lors de l'init, donc pas possible de lui déposer un écouteur directement
        // => je pose l'écouteur sur le container, qui lui écoutera son enfant (donné en second paramètre)

        // je charge mon tableau principal
        // app.loadBoard();
        // app.loadBoardMenu();
    },

    handleOpenMenu: function() {
        //console.log("opening")
        document.querySelector("#burger-wrap").style.width = "19rem";
        document.querySelector("#burger-open").style.display = "none";
        document.querySelector("#burger-close").style.display = "block";
        document.querySelector(".burger-nav").style.display = "block";
    },

    handleCloseMenu: function() {
        //console.log("closing")
        document.querySelector("#burger-wrap").style.width = "2rem";
        document.querySelector("#burger-close").style.display = "none";
        document.querySelector("#burger-open").style.display = "block";
        document.querySelector(".burger-nav").style.display = "none";
        document.querySelector(".modify-table").style.display = "none";
        document.querySelector('.edit-board').reset();
        document.querySelector(".burger-new-table").style.display = "none";
        document.querySelector('.new-board').reset();
        $('.modify-table-backgrounds .selected-bg').removeClass('selected-bg');
        document.querySelector('.menu-boards-list').style.height = "auto";
        document.querySelector('.burger-header').style.display = "block";
    },

    // Requête qui va chercher les backgrounds pour les afficher en vignettes dans les formulaires du menu
    loadBackgrounds: function() {
        $.ajax({
            url: app.baseUrl + 'backgrounds',
            method: 'POST',
            dataType: 'json',
        }).done(function(response) {
            app.backgrounds = response;
            let backgroundsModifyNode = document.querySelector('.modify-table-backgrounds');
            let backgroundsNewNode = document.querySelector('.new-table-backgrounds');
            app.backgrounds.map(background => {
                let newBackgroundItem = document.createElement('li');
                let newBackgroundImage = document.createElement('img');
                newBackgroundImage.classList.add('background-thumb');
                newBackgroundImage.id = "background-" + background.backgroundId;
                newBackgroundImage.setAttribute("background-id", background.backgroundId);
                newBackgroundImage.src = background.imageUrl
                newBackgroundItem.appendChild(newBackgroundImage);
                backgroundsModifyNode.appendChild(newBackgroundItem);
                backgroundsNewNode.appendChild(newBackgroundItem.cloneNode(true));
            });
            app.loadBoard();
            app.loadBoardMenu();
        }).fail(function(e) {
            console.error(e);
        });
    },

    // Appel à l'API pour récupérer toutes les infos du tableau principal de l'utilisateur connecté
    loadBoard: function(id = null) {
        let selectedBoardId = boardsList[0]['boardId'];
        if(id) {
            selectedBoardId = id;
        }
        //console.log('Loaded board id : ', selectedBoardId);
        $.ajax({
            url: app.baseUrl + 'boards',
            method: 'POST',
            data: {
                boardId: selectedBoardId,
            }
        }).done(function(boardDatas) {
            let board = JSON.parse(boardDatas)
            //console.log(board)
            app.loadedBoard.boardId = board.boardId;
            app.loadedBoard.title = board.title;
            app.loadedBoard.color = board.color;
            //console.log(app.loadedBoard)
            if(board.background_id) {
                app.loadedBoard.backgroundId = board.background_id;
                app.loadedBoard.backgroundUrl = app.backgrounds[board.background_id-1].imageUrl;
            } else {
                app.loadedBoard.backgroundId = null
                app.loadedBoard.backgroundUrl = ""
            }
            app.generateBoard();

            // Je place dans une variable toutes les listes reçues du tableau
            let listeCollection = board.listes;
            
            // Je stocke ici le plus grand des orderNb retourné parmi les listes
            app.maxListeOrderNb = app.getMaxListOrderNb(listeCollection);

            // Je boucle sur la collection de listes
            listeCollection.map(liste => {
                // remplir une liste en js et l'afficher dans le dom
                let newListeElement = app.generateListeElement(liste);
                app.addListeElement(newListeElement);
                
                // Je place dans une variable toutes les cartes reçues de la liste
                let cardCollection = liste.cards;
                
                // Je boucle sur la collection de cartes
                cardCollection.map(card => {
                    // remplir une carte en js et l'afficher dans le dom de la liste
                    let newCardElement = app.generateCardElement(card);
                    app.addCardElement(newCardElement);
                })

                // Si une liste est hors-ecran, reset de sa position
                // en cas de changement d'appareil par exemple
                let listeHeight = $('.liste[liste-id="'+liste.listeId+'"]').height();
                let listeWidth = $('.liste[liste-id="'+liste.listeId+'"]').width();
                let boardHeight = $('.board').height();
                let boardWidth = $('.board').width();
                //console.log(boardHeight)
                //console.log($('.liste[liste-id="'+liste.listeId+'"]').position().top)
                if($('.liste[liste-id="'+liste.listeId+'"]').position().top > (boardHeight-listeHeight)) {
                    $('.liste[liste-id="'+liste.listeId+'"]').css('top', 0);
                }
                if($('.liste[liste-id="'+liste.listeId+'"]').position().left > (boardWidth-listeWidth)) {
                    $('.liste[liste-id="'+liste.listeId+'"]').css('left', 0);
                }
                
            })
            // J'ajoute à chaque liste la capacité d'être déplacée une fois que TOUTES mes listes sont chargées
            app.setDragListes();
        }).fail(function(e) {
            console.error(e);
        });
    },

    // Je charge la liste des tableaux de l'utilisateur dans le menu "burger"
    loadBoardMenu: function() {
        let boardsListNode = document.querySelector('.menu-boards-list');
        // Je boucle sur la liste des tableaux pour créer les éléments
        boardsList.map(board => {
            let newBoard = document.createElement('li');
            let newBoardTitle = document.createElement('span');
            newBoardTitle.classList.add('boards-list-item');
            newBoardTitle.id = "board-" + board.boardId;
            newBoardTitle.setAttribute("board-id", board.boardId);
            newBoardTitle.textContent = board.title;
            newBoard.appendChild(newBoardTitle);
            newBoard.insertAdjacentHTML('beforeend', '<i class="fas fa-trash-alt"></i>');
            boardsListNode.appendChild(newBoard);
        })
        boardsListNode.firstElementChild.querySelector('span').classList.add('selected-board'); // A la connexion sur le 1er
        boardsListNode.querySelector('.selected-board').parentNode.insertAdjacentHTML('afterbegin', '<i class="fas fa-paint-brush"></i>'); 
    },

    // Je donne un id (récupéré par la requête) à chaque tableau afin de pouvoir en charger d'autre par la suite
    generateBoard: function(boardId, boardTitle) {
        let board = document.querySelector('.board');
        board.setAttribute('board-id', app.loadedBoard.boardId);
        board.querySelector('.board-title').textContent = app.loadedBoard.title;
        let background = $('.background');
        background.css('background-color', app.loadedBoard.color);
        //console.log( app.loadedBoard.backgroundUrl)
        background.css('background-image',"url("+app.loadedBoard.backgroundUrl+")");
    },

    // Pour changer de tableau après le clic sur son nom
    handleSelectBoard: function(event) {
        let selectedItem = $(event.currentTarget);

        // je récupère l'id du tableau selectionné pour la future requête
        let selectedItemId = selectedItem.attr('board-id');
        app.selectedBoardId = selectedItemId;
        
        // Je change la place du "pinceau", et la classe
        $('.selected-board').parent().find('.fa-paint-brush').remove();
        $('.selected-board').removeClass('selected-board');
        selectedItem.addClass('selected-board');
        selectedItem.parent().prepend('<i class="fas fa-paint-brush"></i>');
        
        // Je vide le DOM du tableau actuel (sauf les 2 premiers éléments -> templates)
        $('.board-listes').children().not(':lt(2)').remove();

        // Je charge le nouveau tableau
        app.loadBoard(app.selectedBoardId);
        
        // Je ferme le menu burger
        document.querySelector("#burger-wrap").style.width = "2rem";
        document.querySelector("#burger-close").style.display = "none";
        document.querySelector("#burger-open").style.display = "block";
        document.querySelector(".burger-nav").style.display = "none";

        // Je ferme les formulaire du tableau au cas où
        document.querySelector(".modify-table").style.display = "none";
        document.querySelector(".burger-new-table").style.display = "none";
    },
    
    // permet  de générer une nouvelle liste avec ses détails
    // elle reste "virtuelle" tant qu'elle n'est pas ajoutée au DOM
    generateListeElement: function(liste) {
        let newListe = document.querySelector('.template-liste').cloneNode(true)
        newListe.id = "liste-"+liste.listeId;
        newListe.classList.remove("template-liste")
        newListe.setAttribute('liste-id', liste.listeId)
        newListe.setAttribute('order-nb', liste.orderNb)
        newListe.querySelector('h3').textContent = liste.title;
        newListe.querySelector('input[name=liste-title]').value = liste.title;
        newListe.querySelector('.liste-cards').classList.add('liste-cards-'+liste.listeId)
        newListe.querySelector('.liste-cards').classList.add('connectedSortable')
        
        //console.log(window.innerWidth)
        if(window.innerWidth >= 1023) {
            newListe.classList.add("drag");
            newListe.style.zIndex = liste.orderNb;
            newListe.style.left = liste.posLeft+'px';
            newListe.style.top = liste.posTop+'px';
        } else {
            document.querySelector('.board-listes').classList.add("res-sort");
            app.setSortListes();
        }
        
        newListe.classList.remove('is-hidden');
        return newListe;
    },
    
    // Ajoute la liste générée au DOM
    addListeElement: function(newListeElement) {
        let listeContainer = $('.board-listes');
        listeContainer.append(newListeElement);

        if(window.innerWidth >= 1023) {
            app.setDragListes();
        } else {
            app.setSortListes();
        }
        app.setSortCards();
    },

    // permet  de générer une nouvelle carte  avec ses détails
    // elle reste "virtuelle" tant qu'elle n'est pas ajoutée au DOM
    generateCardElement: function(card) {
        let newCard = document.querySelector('.template-card').cloneNode(true)
        //console.log(newCard)
        newCard.id = "card-"+card.cardId;
        newCard.classList.remove("template-card");
        newCard.classList.add("sortable");
        newCard.querySelector('h4').textContent = card.title;
        newCard.querySelector('.card-content-description').textContent = card.content;
        newCard.querySelector('input[name=edit-card-title]').value = card.title;
        newCard.querySelector('textarea[name=edit-card-content]').value = card.content;
        newCard.querySelector('input[name=edit-card-color]').value = card.color;
        newCard.setAttribute('liste-id', card.liste_id);
        newCard.setAttribute('card-id', card.cardId)
        newCard.setAttribute('order-nb', card.orderNb)
        newCard.style.borderColor = card.color;
        newCard.classList.remove('is-hidden');
        return newCard;
    },

    // Ajoute la carte générée au DOM
    addCardElement: function(newCardElement) {
        let cardContainer = $('.liste-cards-'+newCardElement.getAttribute('liste-id'));
        cardContainer.append(newCardElement);
        app.setSortCards();
    },

    // Fait apparaitre le formulaire de modification du titre de la liste
    handleDblClickListTitle: function(event) {
        let listTitle = $(event.currentTarget);
        listTitle.addClass('is-hidden');
        let listTitleForm = listTitle.next('form');
        listTitleForm.removeClass('is-hidden');
        listTitleForm[0].querySelector('.liste-header-title-input').focus();
    },

    // Réinitialise le titre et la valeur du form si le changement n'est pas validé
    handleBlurListTitle: function(event) {
        let listTitleForm = $(event.currentTarget).parent();
        listTitleForm.addClass('is-hidden');
        let listTitle = listTitleForm.prev('.liste-header-show');
        $(event.currentTarget)[0].value = listTitle.contents()[1].textContent;
        listTitle.removeClass('is-hidden');
    },

    // Requête d'ajout d'une nouvelle liste
    handleCreateNewListe: function(event) {
        event.preventDefault();
        let newListeName = $('.add-liste-input').eq(0).val();
        let boardId = $('.board').attr('board-id');
        if(newListeName.trim()) {
            $.ajax({
                url: app.baseUrl + 'liste/add',
                method: 'POST',
                dataType: 'json',
                data: {
                    title: newListeName,
                    orderNb: parseInt(app.maxListeOrderNb)+1,
                    board_id: boardId,
                }
            }).done(function(liste) {
                // Si c'est ok, je génère la nouvelle liste et je l'ajoute au DOM
                // ça évite de recharger la page et de faire une nouvelle requête
                let newListeElement = app.generateListeElement(liste);
                $('.add-liste-input').eq(0).val("");
                $('.add-liste-input').blur();
                app.addListeElement(newListeElement);
                app.maxListeOrderNb ++
                app.setDragListes();
            }).fail(function(e) {
                console.error(e);
            });
        }
    },

    handleBlurNewListTitle: function() {
        document.querySelector(".add-liste").reset();
    },

    // Requête pour mettre à jour une liste donnée
    updateListe: function(liste) {
        let listeTitle = liste.find('.liste-header-title-input').val();
        if(listeTitle.trim()) {
            $.ajax({
                url: app.baseUrl + 'liste/update',
                method: 'POST',
                data: {
                    listeId: liste.attr('liste-id'),
                    title: listeTitle,
                    orderNb: liste.attr('order-nb'),
                    posLeft: liste.position().left,
                    posTop: liste.position().top,
                }
            }).done(function(updatedListe) {
                updatedListe = JSON.parse(updatedListe);
                // Si c'est ok je mets à jour les détails la liste ciblée dans le DOM
                let listeToUpdateId = updatedListe.listeId;
                let listeToUpdate = $('.liste[liste-id='+ listeToUpdateId +']');
                listeToUpdate.attr('order-nb', updatedListe.orderNb)
                listeToUpdate.find('h3').text(updatedListe.title);
                listeToUpdate.find('input[name=liste-title]').val( updatedListe.title);
            }).fail(function(e) {
                console.error(e);
            });
        }
    },

    // Sélectionne et envoie la liste complète visée par l'action pour la requête
    handleUpdateListeName: function(event) {
        event.preventDefault();
        let liste = $(event.currentTarget).parent().parent();
        app.updateListe(liste);
        $('.liste-header-title-input').blur();
    },

    // Trouve le orderNb le plus grand parmi les listes
    // (Servira aussi plus tard pour gérer la superposition des listes)
    getMaxListOrderNb: function(listeCollection) {
        let allListes = [... listeCollection];
        orderedListes = allListes.sort((a, b) => b.orderNb - a.orderNb);
        if(orderedListes[0]) {
            maxOrderNb = orderedListes[0].orderNb;
        } else {
            maxOrderNb = 0;
        }
        
        return(maxOrderNb);
    },

    // Réattribue un nouvel orderNb suite à un déplacement pour gérer les superpositions
    updateOrderListe: function(liste) {
        // attribue le orderNb maximum à la liste déplacée
        liste.attr('order-nb', app.maxListeOrderNb);
        app.updateListe(liste); // pour mettre à jour la bdd
        liste.css('zIndex', liste.attr('order-nb')); // pour mettre à jour le dom

        // pour sélectionner toutes les listes qui étaient après celle déplacée
        let afterListes = liste.nextAll();

        afterListes.each(function () {
            // pour chaque liste suivante, je descends le orderNb et le zIndex de 1
            $(this).attr('order-nb', $(this).attr("order-nb")-1);
            app.updateListe($(this));
            $(this).css('zIndex', $(this).attr('order-nb'));
        });
    },

    // Requête pour supprimer une liste
    handleDeleteListe: function(event) {
        // Je trouve la bonne carte à supprimer grâce à l'ecouteur d'évènement, ainsi que son id
        let listeToDelete = $(event.currentTarget).parent().parent();
        let listeToDeleteId = listeToDelete.attr("liste-id");
        $.ajax({
            url: app.baseUrl + 'liste/delete',
            method: 'POST',
            dataType: 'json',
            data: {
                listeId: listeToDeleteId,
            }
        }).done(function(response) {
            // Je la supprime du DOM maintenant qu'elle est supprimée de la BDD
            listeToDelete.remove();
        }).fail(function(e) {
            console.error(e);
        });
    },

    // Pour rendre les listes déplaçables en horizontal (responsive)
    setSortListes: function() {
        $(".res-sort").sortable({
            axis: "x",
            cursor: "move",
            cancel: ".card",
            placeholder: "liste-placeholder"
        });
    },

    // Pour rendre une liste déplaçable dans le tableau
    setDragListes: function() {
        //Pour le déplacement des div .liste avec jquery draggable
        $(".drag").draggable({  
            cursor: "move", // pour modifier le curseur de déplacement
            //containment: "#cible"// limite le déplacement à une zone
            containment: ".board-listes",
            //Pour l'enregistrement des positions après le déplacement
            cancel: ".card",
            
            stop: function(event, ui){
                let posLeft = $(this).position().left;//voir si je change offset par position
                let posTop = $(this).position().top;//offset modifié par position car parent ajouté par Coralie
                let listeId = $(this).attr("liste-id");//à changer avec liste-id
                //console.log(listeId);
                //console.log(posLeft, posTop); //permet de vérifier que le offset fonctionne bien
                app.updateOrderListe($(this));
                $(".board-listes").append(this);
            }
        });
    },

    setSortCards: function(event) {
        $( ".liste-cards" ).sortable({
            cursor:"move",
            connectWith: ".connectedSortable",
            placeholder: "card-placeholder",
            stack: ".board-listes",

            start: function(event, ui) {
                oldList = ui.item.parent().parent().attr('liste-id')
                //console.log(oldList)
            },

            stop: function(event, ui) {
                newList = ui.item.parent().parent().attr('liste-id')
                let index = ui.item.index()+1;
                //console.log(index);
                ui.item.attr('order-nb', index);
                //console.log( ui.item.attr('order-nb'))
                let cardListeId = ui.item.parent().parent().attr('liste-id');
                ui.item.attr('liste-id', cardListeId);
                //app.updateCardOnMove(ui.item);

                //console.log(oldList)
                //console.log(newList)
                //console.log($('.liste[liste-id = "'+oldList+'"]').find('.card'))
                $('.liste[liste-id = "'+oldList+'"]').find('.card').each(function(index){
                    $(this).attr('order-nb', $(this).index()+1);
                    app.updateCardOnMove($(this))
                });
                $('.liste[liste-id = "'+newList+'"]').find('.card').each(function(index){
                    $(this).attr('order-nb', $(this).index()+1);
                    app.updateCardOnMove($(this))
                }); 
            }
        })
    },

    updateCardOnMove: function(card) {
        let cardId = card.attr('card-id');
        let newListeId = card.attr('liste-id');
        let newOrderNb = card.attr('order-nb');
        let cardTitle = card.find('.card-header-title').val();
        let cardTextContent = card.find('.card-header-content').val();
        let cardColor = card.find('.card-header-color').val();
        //console.log(newOrderNb);

        $.ajax({
            url: app.baseUrl + 'card/update',
            method: 'POST',
            data: {
                title: cardTitle,
                content: cardTextContent,
                orderNb: newOrderNb,
                color: cardColor,
                liste_id: newListeId,
                cardId: cardId,
            }
        }).done(function(updatedCard) {
            updatedCard = JSON.parse(updatedCard);
            //console.log(updatedCard)
            //console.log("Card "+updatedCard.title+" updated !")
        }).fail(function(e) {
            console.error(e);
        });
    },

    // Formulaire d'ajout d'une nouvelle carte
    handleFormNewCard: function(event) {
        let ListeEnCours = $(event.currentTarget).parent().parent().parent();
        //console.log(ListeEnCours);
        let cardClone= $('.template-card').clone(true, true);
        cardClone.removeClass('template-card is-hidden');
        (cardClone.find('.card-show')).addClass('is-hidden');
        (cardClone.find('.edit-card-form')).removeClass('is-hidden');
        cardClone.appendTo(ListeEnCours.find('.liste-cards'));
    },

    // Requête d'ajout ou d'édition d'une carte
    handleCreateOrEditCard: function(event) {
        event.preventDefault();
        let cardTitleInput = $(event.currentTarget).find('.card-header-title').val();
        //console.log(cardTitleInput);
        let cardTextContent = $(event.currentTarget).find('.card-header-content').val();
        //console.log(cardTextContent);
        let cardColor = $(event.currentTarget).find('.card-header-color').val();
        //console.log(cardColor);
        let listeId = $(event.currentTarget).parent().parent().parent().attr('liste-id');
        //console.log(listeId);
        
        // Je cherche si c'est une nouvelle carte ou une édition
        // en cherchant si elle a déjà un id
        //console.log($(event.currentTarget).parent().attr('card-id'));
        if($(event.currentTarget).parent().attr('card-id')) {
            cardToEditId = $(event.currentTarget).parent().attr('card-id');
            //console.log(cardToEditId);
            let cardNumber = $(event.currentTarget).parent().attr('order-nb');
            //console.log(cardNumber);
            // Je vérifie si le titre est non vide avant d'envoyer la requête
            if(cardTitleInput.trim()) {
                $.ajax({
                    url: app.baseUrl + 'card/update',
                    method: 'POST',
                    data: {
                        title: cardTitleInput,
                        content: cardTextContent,
                        orderNb: cardNumber,
                        color: cardColor,
                        liste_id: listeId,
                        cardId: cardToEditId,
                    }
                }).done(function(updatedCard) {
                    // Si c'est ok, je modifie la carte avec les infos reçues
                    updatedCard = JSON.parse(updatedCard);
                    //console.log(updatedCard)
                    $(event.currentTarget).prev().find('.card-content-title').text(updatedCard.title);
                    $(event.currentTarget).prev().find('.card-content-description').text(updatedCard.content);
                    $(event.currentTarget).parent().css('border-color', updatedCard.color);
                    // masquage du form et apparition de la carte
                    $(event.currentTarget).addClass('is-hidden');
                    $(event.currentTarget).prev().removeClass('is-hidden');
                    
                }).fail(function(e) {
                    console.error(e);
                });
            } else {
                // Si pas de titre, j'affiche un message d'erreur
                let errorMessage  =  document.createElement('p');
                errorMessage.classList.add('error-message');
                errorMessage.textContent = "Merci de renseigner un titre";
                let cardForm = $(event.currentTarget).parent();
                cardForm.prepend(errorMessage);
            }
        } 
        else {
            let cardNumber = $(event.currentTarget).parent().parent().children().length;
            //console.log(cardNumber);
            if(cardTitleInput.trim()) {
                $.ajax({
                    url: app.baseUrl + 'card/add',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        title: cardTitleInput,
                        content: cardTextContent,
                        orderNb: cardNumber,
                        color: cardColor,
                        liste_id: listeId,
                    }
                }).done(function(card) {
                    // Si c'est ok, je complète la nouvelle carte avec les infos reçues
                    // elle a déjà été créée dans le DOM lors du click sur le +
                    $(event.currentTarget).prev().find('.card-content-title').text(card.title);
                    $(event.currentTarget).prev().find('.card-content-description').text(card.content);
                    $(event.currentTarget).parent().addClass('sortable');
                    $(event.currentTarget).parent().attr('id', 'card-'+card.cardId);
                    $(event.currentTarget).parent().attr('liste-id', card.liste_id);
                    $(event.currentTarget).parent().attr('card-id', card.cardId);
                    $(event.currentTarget).parent().attr('order-nb', cardNumber);
                    $(event.currentTarget).parent().css('border-color', card.color);
                    // masquage du form et apparition de la carte
                    $(event.currentTarget).addClass('is-hidden');
                    $(event.currentTarget).prev().removeClass('is-hidden');
        
                }).fail(function(e) {
                    console.error(e);
                });
            } else {
                // Si pas de titre, j'affiche un message d'erreur
                let errorMessage  =  document.createElement('p');
                errorMessage.classList.add('error-message');
                errorMessage.textContent = "Merci de renseigner un titre";
                let cardForm = $(event.currentTarget).parent();
                cardForm.prepend(errorMessage);
            }
        }
    },

    //Requête pour supprimer une card
    handleDeleteCard: function(event) {
        let cardToDelete = $(event.currentTarget).parent().parent().parent();
        let cardToDeleteId = cardToDelete.attr("card-id");
        //console.log(cardToDelete);
        $.ajax({
            url: app.baseUrl + 'card/delete',
            method: 'POST',
            dataType: 'json',
            data: {
                cardId: cardToDeleteId,
            }
        }).done(function(response) {
            cardToDelete.remove();
        }).fail(function(e) {
            console.error(e);
            cardToDelete.remove();
        });
    },

    //Requête pour afficher le formulaire de modifcation de la carte au click du pinceau
    handleClickModifyCard: function(event) { 
        let editCard = $(event.currentTarget).parent().parent().parent(); //chercher le bon parent
        //console.log(editCard); 
        editCard.find('.card-show').addClass('is-hidden'); 
        editCard.find('.edit-card-form').removeClass('is-hidden');
    },

    handleCardFormAbord: function(event) {
        let card = $(event.currentTarget).parent().parent();
        if(card.attr("card-id")) {
            card.find('.card-header-title').val(card.find('.card-content-title').text());
            card.find('.card-header-content').val(card.find('.card-content-description').text());

            let color = card.css('border-color');
            color = color.replace('rgb(','').replace(')', '');
            color = color.split(', ');
            //console.log(color)
            let hexColor = app.convertRGBtoHex(color[0], color[1], color[2]);
            card.find('.card-header-color').val(hexColor);
            card.find('.error-message').remove();
            card.find('.edit-card-form').addClass('is-hidden');
            card.find('.card-show').removeClass('is-hidden'); 
        } else {
            card.remove();
        }
    },

    colorToHex: function(color) {
        var hexadecimal = color.toString(16);
        return hexadecimal.length == 1 ? "0" + hexadecimal : hexadecimal;
    },

    convertRGBtoHex(red, green, blue) {
        return "#" + app.colorToHex(parseInt(red)) + app.colorToHex(parseInt(green)) + app.colorToHex(parseInt(blue));
    },

    // Sert à afficher un indicateur visuel sur le background sélectionné
    // Et indirectement à récupérer son id à l'envoi du formulaire
    selectBackground: function(event) {
        let selectedImage = $(event.currentTarget);

        // Pour pouvoir "déselectionner"
        if($(event.currentTarget).hasClass('selected-bg')) {
            $(event.currentTarget).removeClass('selected-bg');
        } else {
            $(event.currentTarget).parent().parent().find('.selected-bg').removeClass('selected-bg');
            selectedImage.addClass('selected-bg');
        }
    },

    handleFormNewBoard: function() {
        document.querySelector(".burger-new-table").style.display = "block";
        document.querySelector(".modify-table").style.display = "none";
        document.querySelector('.burger-header').style.display = "none";
    },

    // Requête d'ajout d'un nouveau tableau
    handleCreateNewBoard: function(event) {
        event.preventDefault();
        let newBoardTitle = $('.new-board-title-input').eq(0).val();
        let newBoardColor = $('.new-board-colorpicker').eq(0).val();
        let newBoardBgId = $('.selected-bg').attr("background-id");
        
        // Je vérifie qu'un titre a bien été entré
        if(newBoardTitle) {
            $.ajax({
                url: app.baseUrl + 'board/add',
                method: 'POST',
                dataType: 'json',
                data: {
                    title: newBoardTitle,
                    color: newBoardColor,
                    background_id: newBoardBgId ? newBoardBgId : null,
                }
            }).done(function(board) {
                // J'ajoute le nouveau tableau à la liste dans le menu, avec ses informations
                let boardsListNode = document.querySelector('.menu-boards-list');
                let newBoard = document.createElement('li');
                let newBoardTitle = document.createElement('span');
                newBoardTitle.classList.add('boards-list-item');
                newBoardTitle.id = "board-" + board.boardId;
                newBoardTitle.setAttribute("board-id", board.boardId);
                newBoardTitle.textContent = board.title;
                newBoard.appendChild(newBoardTitle);
                newBoard.insertAdjacentHTML('beforeend', '<i class="fas fa-trash-alt"></i>');
                boardsListNode.appendChild(newBoard);
                document.querySelector('.burger-header').style.display = "block";

                // Je masque le formulaire et je le vide
                document.querySelector(".burger-new-table").style.display = "none";
                document.querySelector('.new-board').reset();
                $('.new-board').find('.selected-bg').removeClass('selected-bg');
            }).fail(function(e) {
                console.error(e);
            });
        } else {
            // Si pas de titre, j'affiche un message d'erreur
            let errorMessage  =  document.createElement('p');
            errorMessage.classList.add('error-message');
            errorMessage.textContent = "Merci de renseigner un titre";
            let newBoardForm = document.querySelector(".new-board");
            newBoardForm.prepend(errorMessage);
        }
    },

    // Ouvre et remplit le formulaire d'édition du tableau
    handleOpenEditBoardForm: function() {
        document.querySelector(".burger-header").style.display = "none";
        document.querySelector(".modify-table").style.display = "block";
        document.querySelector(".burger-new-table").style.display = "none";

        $('.board-title-input').eq(0).val(app.loadedBoard.title);
        $('.edit-board-colorpicker').eq(0).val(app.loadedBoard.color?app.loadedBoard.color:"#ffffff");
        $("img[background-id|='"+app.loadedBoard.backgroundId+"']").addClass('selected-bg');
    },

    handleBoardFormAbord: function() {
        document.querySelector(".modify-table").style.display = "none";
        document.querySelector(".burger-new-table").style.display = "none";
        document.querySelector(".burger-header").style.display = "block";
    },

    // Pour éditer un tableau
    handleEditBoard: function(event) {
        event.preventDefault();
        // je récupère dans le form les nouvelles valeurs dont j'ai besoin
        let newBoardTitle = $('.board-title-input').eq(0).val();
        let newBoardColor = $('.edit-board-colorpicker').eq(0).val();
        let newBoardBgId = $('.modify-table-backgrounds .selected-bg').attr("background-id");

        // Je les envois dans la requête
        $.ajax({
            url: app.baseUrl + 'board/edit',
            method: 'POST',
            dataType: 'json',
            data: {
                boardId: app.loadedBoard.boardId,
                title: newBoardTitle,
                color: newBoardColor,
                background_id: newBoardBgId ? newBoardBgId : null,
            }
        }).done(function(updatedBoard) {
            // Je mets à jour les éléments du DOM
            // console.log(updatedBoard)
            $('.selected-board').text(updatedBoard.title);
            document.querySelector('.board-title').textContent = updatedBoard.title;

            app.loadedBoard.color = updatedBoard.color;
            if(updatedBoard.background_id) {
                app.loadedBoard.backgroundId = updatedBoard.background_id
                app.loadedBoard.backgroundUrl = app.backgrounds[updatedBoard.background_id-1].imageUrl;
            } else {
                app.loadedBoard.backgroundId = null
                app.loadedBoard.backgroundUrl = "";
            }
            
            $('.background').css('background-color', app.loadedBoard.color);
            $('.background').css('background-image',"url("+app.loadedBoard.backgroundUrl+")");

            $('.board-title-input').eq(0).val(updatedBoard.title);
            $('.edit-board-colorpicker').eq(0).val(updatedBoard.color);
            
            $('.modify-table-backgrounds .selected-bg').removeClass('selected-bg');
            document.querySelector(".modify-table").style.display = "none";
            document.querySelector('.burger-header').style.display = "block";
        }).fail(function(e) {
            console.error(e);
        });
    },

    // Faire apparaitre la fenêtre de confirmation de suppression de tableau
    handleConfirmDeleteBoard: function(event) {
        let confirmDialog = $(".delete-board-confirm");

        // Pour afficher le nom du tableau dans le text de la fenêtre
        // -> vérification de plus pour l'utilisateur
        let boardToDeletName = $(event.currentTarget).prev().text();
        confirmDialog.find(".delete-board-name").text(boardToDeletName);

        // Je récupère l'id du tableau pour faciliter la requête de supprsesion
        let boardToDeleteId = $(event.currentTarget).prev().attr("board-id");
        //console.log(boardToDeleteId)
        // je cache cet id dans le bouton de confirmation
        confirmDialog.find(".delete-board-submit").attr("board-id", boardToDeleteId);
        confirmDialog.removeClass("is-hidden");

        // Je prévois un retour en arrière possible
        $(".delete-board-abord").on('click', function () {
            // et dans ce cas, je nettoie les infos de la fenêtre pour éviter les erreurs
            confirmDialog.find(".delete-board-name").text("");
            confirmDialog.find(".delete-board-submit").attr("board-id", "");

            // Puis je masque la fenêtre
            confirmDialog.addClass("is-hidden");
        });
        
        $('.delete-board-submit').on('click', app.handleDeleteBoard);
    },

    // Pour supprimer définitivement un tableau
    handleDeleteBoard: function(event) {
        // Je trouve le bon tableau (et son id) à supprimer (presque caché dans le bouton !)
        let boardToDeleteId = $(event.currentTarget).attr("board-id");
        let boardToDelete = $(".menu-boards-list").find("span[board-id="+boardToDeleteId +"]").parent();
        //console.log(boardToDelete)
        $.ajax({
            url: app.baseUrl + 'board/delete',
            method: 'POST',
            dataType: 'json',
            data: {
                boardId: boardToDeleteId,
            }
        }).done(function(response) {
            // Je le supprime de la liste dans le menu maintenant qu'il est supprimé de la BDD
            boardToDelete.hide();
            // Je masque la fenêtre de confirmation de suppression
            $(".delete-board-confirm").addClass("is-hidden");
        }).fail(function(e) {
            console.error(e);
        });
    }
};

// Pour charger le script seulement sur la page concernée
if(window.location.search === "?board") {
    document.addEventListener('DOMContentLoaded', app.init);
}
