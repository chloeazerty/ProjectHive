-- (mdp pour les user = username)

USE cda_projet_project_hive;

INSERT INTO user (`email`, `username`, `password`, `role`, `createdAt`, `updatedAt`) VALUES
('testeur1@testeur1.fr', 'testeur1', '$2y$10$5pIKdrlVhslccRhoLbGB8OgXFTB5Lg7lGzo4HMIW8QhHfOX4RUhKO', 'registered', '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('testeur2@testeur2.fr', 'testeur2', '$2y$10$cMNEoOw319Ehzf.sJVXmuOf32QqqO.npLUhnPF3Z/EoqzXSfJouXa', 'registered', '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('testeur3@testeur3.fr', 'testeur3', '$2y$10$GtPKSmaiJVR6ky6Dnm17MupmCt9x3GgkptnXFXrGm5flfTWzqdUEu', 'registered', '2021-12-01 14:31:25', '2021-12-01 14:31:25');

INSERT INTO background (`imageUrl`, `createdAt`, `updatedAt`) VALUES
("https://picsum.photos/id/1002/945/630", '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
("https://picsum.photos/id/1019/945/630", '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
("https://picsum.photos/id/1016/945/630", '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
("https://picsum.photos/id/1018/945/630", '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
("https://picsum.photos/id/1021/945/630", '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
("https://picsum.photos/id/1028/945/630", '2021-12-01 14:31:25', '2021-12-01 14:31:25');


INSERT INTO board (`title`, `color`, `background_id`, `owner_id`, `createdAt`, `updatedAt`) VALUES
('tableau1', NULL, NULL, 1, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('tableau2', '#6ee7dd', NULL, 1, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('tableau3', NULL, 1, 1, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('tableau4', '#fdffb7', NULL, 2, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('tableau5', NULL, 4, 3, '2021-12-01 14:31:25', '2021-12-01 14:31:25');

INSERT INTO liste (`title`, `orderNb`, `board_id`, `posLeft`, `posTop`, `createdAt`, `updatedAt`) VALUES
('liste1', 1, 1, 50, 50, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste2', 2, 1, 150, 40, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste3', 3, 1, 350, 250, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste4', 1, 2, 50, 150, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste5', 2, 2, 200, 450, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste6', 1, 3, 50, 50, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste7', 1, 4, 50, 50, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste8', 2, 4, 50, 50, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste9', 4, 1, 50, 500, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('liste10', 1, 5, 50, 50, '2021-12-01 14:31:25', '2021-12-01 14:31:25');

INSERT INTO card (`title`, `content`, `orderNb`, `color`, `liste_id`, `createdAt`, `updatedAt`) VALUES
('carte1', "dolorem assumenda facilis aut fugit facilis ratione", 2, "#FFFF00", 1, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte2', "sit et fugiat voluptatem commodi libero", 1, "#FFFF00", 1, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte3', "itaque pariatur facilis pariatur quia et tenetur vel commodi et harum atque ducimus aut perspiciatis consequatur ab", 1, "#FFFF00", 2, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte4', "consectetur ab", 3, "#FFFF00", 2, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte5', "consequuntur qui et illo repellendus totam ut", 2, "#FFFF00", 2, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte6', "illo itaque", 1, "#FFFF00", 3, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte7', "possimus veritatis ipsum id earum", 1, "#FFFF00", 4, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte8', "ut eveniet eius voluptas possimus", 2, "#FFFF00", 4, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte9', "possimus adipisci id quasi ut quo similique quod nulla itaque architecto", 3, "#FFFF00", 6, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte10', "et et illo", 1, "#FFFF00", 6, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte11', "quis in animi quibusdam molestiae debitis", 2, "#FFFF00", 6, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte12', "dicta qui ipsa odio repellendus magnam ipsam ratione", 1, "#FFFF00", 7, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte13', "mollitia enim sunt impedit praesentium omnis beatae sequi ad dolor ex blanditiis", 1, "#FFFF00", 8, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte14', "voluptatem esse tempore in magnam", 2, "#FFFF00", 8, '2021-12-01 14:31:25', '2021-12-01 14:31:25'),
('carte15', "vitae et non voluptate ipsum est beatae dolorem quasi sunt id in iste sit repellat autem ut quibusdam exercitationem magni blanditiis rerum repudiandae maxime", 1, "#FFFF00", 9, '2021-12-01 14:31:25', '2021-12-01 14:31:25');