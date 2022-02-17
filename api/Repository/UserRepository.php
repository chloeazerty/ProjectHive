<?php

namespace Api\Repository;

use Api\Entity\User;
use Api\Repository\ManagerRepository;

class UserRepository extends ManagerRepository
{
    public function addUser($user)
    {
        if(!empty($user)) {
            // Si tous les champs ont été remplis
            if (!in_array('', $user)) {
            //if(isset($_POST['email']) && isset($_POST['username']) && ($_POST['password']) && ($_POST['password2'])){
                $email = htmlspecialchars($user['email']);
                $username = htmlspecialchars($user['username']);
                $password = htmlspecialchars($user['password']);
                $password2 = htmlspecialchars($user['password2']);
                
                // On voudrait que l'email et le username soient uniques
                $verifMail = "SELECT * FROM user WHERE email = '{$email}'";
                $verifUsername = "SELECT * FROM user WHERE username = '{$username}'";
                // Chercher si l'adresse email existe déjà dans la BDD
                $resultVerifMail = $this->createQuery($verifMail)->fetchColumn();
                // Ajout d'un filtre de validation de l'email
                if(filter_var($email, FILTER_VALIDATE_EMAIL) && !$resultVerifMail) {
                    // On vérifie que le username n'existe pas déjà
                    $resultVerifUsername =  $this->createQuery($verifUsername)->fetchColumn();
                    if (!$resultVerifUsername) {
                        // On vérifie que les passwords correspondent
                        if(strlen($password) > 6) {
                            if ($password === $password2) {
                                $password = password_hash($password, PASSWORD_DEFAULT);// par défaut, meilleur encodeur actuel
                                // Requête préparée avec verrouillage des valeurs dans des variables
                                $sql = 'INSERT INTO user (email, username, password, role, createdAt, updatedAt) VALUES (?, ?, ?, "registered", NOW(), NOW())';
                                $this->createQuery($sql, [$email, $username, $password]);//createQuery dans ManagerRepository
                                
                                header('Location: ?registered');
                            } else {
                                return "<p class='editpass-error'>Les mots de passes sont différents.</p>";
                            }
                        } else {
                            return "<p class='editpass-error'>Le mot de passe doit comporter 6 caractères au minimum.</p>";
                        }
                    } else {
                        return "<p class='editpass-error'>Ce pseudo est déjà utilisé.</p>";
                    }
                }else {
                    return "<p class='editpass-error'>Cet email est incorrect ou existe déjà.</p>";
                }
            }  else {
                return "<p class='editpass-error'>Certains champs sont vides.</p>";
            }
        }
    }
    
    public function connectUser($user)
    {
        //! Si le bouton submit a été cliqué
        if (!empty($user)) {
            //! Si tous les champs ont été remplis
            if (!in_array('', $user)) {
                //! Assainissement des variables
                $email = htmlspecialchars($user['email']);
                $password = htmlspecialchars($user['password']);
                
                //! Début de la requête de vérification de l'email
                //? Requete SQL pour récupérer la ligne qui correspond à l'email
                $getRowByEmail = "SELECT * FROM user WHERE email = '{$email}'";
                
                //? Lancement de ma requête
                $getUser = $this->createQuery($getRowByEmail);
                
                //? Si ma requête a pu être effectuée, alors crée une variable $userInfos avec les infos
                if ($userInfos = $getUser->fetch()) {
                    if (password_verify($password, $userInfos->password)) {
                        $_SESSION['userId'] = $userInfos->userId;
                        $_SESSION['username'] = $userInfos->username;
                        $_SESSION['email'] = $userInfos->email;
                        // $_SESSION['token'] = uniqid(rand(),true); //pour vérifier que c'est bien le bon utilisateur
                        header('Location: ?board');
                    } else {
                        return "<p class='editpass-error'>Vérifiez votre mot de passe.</p>";
                    }
                } else {
                    return "<p class='editpass-error'>Votre email n'est pas valide.</p>";
                }
            } else {
                return "<p class='editpass-error'>Veuillez renseigner tous les champs.</p>";
            }
        } else {
            return "<p class='editpass-error'>Veuillez renseigner tous les champs.</p>";
        }
    }

    public function editUser($id, $post) {
        // Si l'utilisateur a cliqué sur "modifier"
        if (!empty($post)) {
            // On vérifie que tous les champs ont été complétés
            if (!in_array('', $post)) {
                // verif inputs
                $email = htmlspecialchars($post['email']);
                $username = htmlspecialchars($post['username']);
                $password = htmlspecialchars($post['password']);
                
                //! Début de la requête de vérification de l'email
                // 1. je vérifie si l'email a été modifié
                if($email !== $_SESSION['email']) {
                    // Si c'est le cas, je vérifie que le nouvel email n'existe pas déjà en bdd
                    $verifEmail = "SELECT * FROM user WHERE email = '{$email}'";
                    $resultVerifEmail = $this->createQuery($verifEmail)->fetchColumn();

                    // Si le fetchColumn ne me retourne rien, alors je stocke le nouvel email
                    if(filter_var($email, FILTER_VALIDATE_EMAIL) && !$resultVerifEmail) {
                        $newEmail = $email;
                    }
                    // Sinon je retourne un message d'erreur
                    else {
                        return "<p class='editpass-error'>Cet email est incorrect ou existe déjà.</p>";
                    }
                }
                // S'il n'a pas été modifié, l'email reste identique
                else {
                    $newEmail = $email;
                }

                //! Début de la requête de vérification du pseudo
                // 2. je vérifie si le pseudo a été modifié
                if($username !== $_SESSION['username']) {
                    // Si c'est le cas, je vérifie que le nouveau pseudo n'existe pas déjà en bdd
                    $verifUsername = "SELECT * FROM user WHERE username = '{$username}'";
                    $resultVerifUsername = $this->createQuery($verifUsername)->fetchColumn();
                    
                    // Si le fetchColumn ne me retourne rien, alors je stocke le nouveau pseudo
                    if(!$resultVerifUsername) {
                        $newUsername = $username;
                    }
                    // Sinon je retourne un message d'erreur
                    else {
                        return "<p class='editpass-error'>Ce pseudo est déjà utilisé.</p>";
                    }
                }
                // S'il n'a pas été modifié, le pseudo reste identique
                else {
                    $newUsername = $username;
                }

                //! Début de la requête de vérification du mot de passe
                // 3. Je vais chercher les infos de l'utilisateur connecté
                $userInfos = $this->findOne($id);
                
                // 4. Je vérifie que le mot de passe entré correspond à celui enregistré en bdd
                if (password_verify($password, $userInfos->getPassword())) {
                    // Si c'est ok je prépare ma requête
                    $sql = "UPDATE user SET email = ?, username = ?, updatedAt = ? WHERE userId = ?";
                    // puis j'exécute ma requête
                    $this->createQuery($sql, [
                        $newEmail,
                        $newUsername,
                        date("Y-m-d H:i:s"),
                        $id
                    ]);
                    return "<p class='editpass-valid'>Les modifications ont été enregistrées.</p>";
                } else {
                    return "<p class='editpass-error'>Vérifiez votre mot de passe.</p>";
                }
            }
            else {
                return "<p class='editpass-error'>Veuillez compléter tous les champs.</p>";
            }
        }
    }

    public function editpass($id, $post) {
        if (!empty($post)) {
            if (!in_array('', $post)) {
                $password = htmlspecialchars($post['password']);
                $newPassword = htmlspecialchars($post['newPassword']);
                $newPassword2 = htmlspecialchars($post['newPassword2']);

                $userInfos = $this->findOne($id);

                // verif inputs
                if (password_verify($password, $userInfos->getPassword())) {
                    if(strlen($newPassword) > 6) {
                        if ($newPassword === $newPassword2) {
                            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                            // prepare request
                            $sql = "UPDATE user SET password = ?, updatedAt = ? WHERE userId = ?";
            
                            // query
                            $this->createQuery($sql, [
                                $newPasswordHash,
                                date("Y-m-d H:i:s"),
                                $id
                            ]);
            
                            // message
                            return "<p class='editpass-valid'>Les modifications ont été enregistrées.</p>";
                        } else {
                            return "<p class='editpass-error'>Les nouveaux mots de passes doivent correspondre.</p>";
                        }
                    } else {
                        return "<p class='editpass-error'>Le nouveau mot de passe doit comporter 6 caractères au minimum.</p>";
                    }
                } else {
                    return "<p class='editpass-error'>Vérifiez votre mot de passe.</p>";
                }

            }
            else {
                return "<p class='editpass-error'>Veuillez compléter tous les champs.</p>";
            }
        }
    }
}
