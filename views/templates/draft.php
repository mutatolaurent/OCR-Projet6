    <div class="cards-nos-livres">
        <?php if ($books === false) { ?>
            <p class="no-results">Aucun livre trouvé pour votre recherche.</p>
        <?php } else { ?>     
            <?php foreach ($books as $book) { ?>
                <a href="indexphp?action=book&id=<?= $book->getId() ?>">
                    <article class="card">
                        <img
                            src="<?= $book->getPhoto() ?>"
                            alt="<?= $book->getTitle() ?>"
                        />
                        <h3 class="book-title"><?= $book->getTitle() ?></h3>
                        <p class="book-author"><?= $book->getAuthor() ?></p>    
                        <p class="book-owner">
                            Vendu par : <?= $book->getOwner()->getPseudo() ?>   
                        </p>
                    </article>
                </a>
            <?php } ?>
        <?php } ?>
    </div>


            $sql = "SELECT b.*, u.pseudo,
            MATCH(b.title, b.author) AGAINST(:terms) AS score
            FROM book b
            INNER JOIN user u ON b.id_user = u.id
            WHERE MATCH(b.title, b.author) AGAINST(:terms IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC";
