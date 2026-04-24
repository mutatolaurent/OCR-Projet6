INSERT INTO message (id_thread, id_sender, content, created_at) VALUES
(1, 1, 'Salut ! As-tu terminé "Le Nom du Vent" ? Je serais curieux d’avoir ton avis.', NOW()),
(1, 2, 'Oui, j’ai adoré. L’univers est incroyable et Kvothe est fascinant.', NOW()),
(1, 1, 'Super ! Je pensais commencer le tome 2 bientôt.', NOW()),
(1, 2, 'Tu vas te régaler, il est encore meilleur.', NOW());

INSERT INTO message (id_thread, id_sender, content, created_at) VALUES
(2, 2, 'Salut ! Tu m’avais conseillé "Dune". J’ai enfin commencé.', NOW()),
(2, 3, 'Génial ! Le début est dense mais l’histoire devient incroyable.', NOW()),
(2, 2, 'J’aime beaucoup l’univers politique et les intrigues.', NOW()),
(2, 3, 'Attends de voir la suite, c’est encore plus riche.', NOW());

INSERT INTO message (id_thread, id_sender, content, created_at) VALUES
(3, 2, 'Hello ! Tu lis quoi en ce moment ?', NOW()),
(3, 4, 'Je découvre "La Passe-Miroir". Très original et poétique.', NOW()),
(3, 2, 'On m’en a beaucoup parlé, je devrais m’y mettre.', NOW()),
(3, 4, 'Oui, l’univers est vraiment unique. Je recommande.', NOW());
