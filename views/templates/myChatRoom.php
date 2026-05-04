<?php

/**
 * Template pour afficher la page de messagerie de l'utilisateur
 *
 */
?>
<div class="msg-container">
    <section class="sect-thread <?= $context == 'thread-access' ? ' thread-invisible' : '' ?>" >
        <h1>Messagerie</h1>
        <?php if ($chatroom !== null) { ?>
            <?php foreach ($chatroom[0] as $thread) { ?>
                <a href="index.php?action=showMyChatRoom&idContact=<?= $thread['contact']->getId() ?>#content" class="nav-thread <?= $thread['threadActif'] ? 'active-thread' : '' ?>">
                    <img
                        class="img-owner"
                        src="<?= $thread['contact']->getPhoto() ? $thread['contact']->getPhoto() : 'images/books/placeholder.png' ?>"
                        alt="Image de l'avatar du correspondant"
                    />
                    <div class="thread-context">
                        <div class="thread-info">
                            <p class="thread-member"><?= $thread['contact']->getPseudo() ?></p>
                            <p class="thread-time"><?= Utils::formatMessageDate($thread['lastMessage']->getCreatedAt()) ?></p>
                        </div>
                        <p class="thread-last-msg"><?= $thread['lastMessage']->getContent() ?></p>
                    </div>
                </a>
            <?php } ?>
        <?php } ?>
    </section>

    <section class="sect-bubbles <?= $context == 'nothread-access' ? ' bubbles-invisible' : '' ?>">
        <h2 class="sr-only">Zone d'affichage des messages</h2>
        <a href="index.php?action=showMyChatRoom&bubble-zone=hidden" class="return-link">
            <img src="images/icones/retour.svg" alt="Lien de retour vers la liste des conversations" aria-hidden="true"/>
            <span>retour</span>
        </a>
        <?php if (!empty($chatroom[1]['chatcontact'])) {?>
            <div class="contact-info">
                <img
                    class="img-owner"
                    src="<?= $chatroom[1]['chatcontact']->getPhoto() ? $chatroom[1]['chatcontact']->getPhoto() : 'images/books/placeholder.png' ?>"
                    alt="Image de l'avatar du correspondant"
                />
                <span class="contact-pseudo"><?= $chatroom[1]['chatcontact']->getPseudo() ?></span>
            </div>
        <?php } ?>

        <div class="bubbles-zone">
            <?php if (!empty($chatroom[1]['messages'])) {?>
                <?php foreach ($chatroom[1]['messages'] as $msg) { ?>
                    <?php if ($msg->getIdSender() == $chatroom[1]['chatcontact']->getId()) { ?>
                        <div class="bubble-block left">
                            <div class="msg-meta">
                                <img
                                    src="<?= $chatroom[1]['chatcontact']->getPhoto() ?>"
                                    class="msg-avatar"
                                    alt=""
                                    aria-hidden="true"
                                />
                                <span class="msg-date"><?= Utils::formatFullDateTime($msg->getCreatedAt()) ?></span>
                                <!-- <span class="msg-time">10:42</span> -->
                            </div>
                            <div class="bubble bubble-left">
                                <?= $msg->getContent() ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="bubble-block right">
                            <div class="msg-meta">
                                <span class="msg-date"><?= Utils::formatFullDateTime($msg->getCreatedAt()) ?></span>
                                <!-- <span class="msg-time">10:43</span> -->
                            </div>
                            <div class="bubble bubble-right">
                                <?= $msg->getContent() ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
        <form action="<?= !empty($chatroom[1]['chatcontact']) ? 'index.php?action=sendMessage&idContact='.$chatroom[1]['chatcontact']->getId() : '#' ?>" method="POST" class="msg-input-zone">
            <label for="content" class="sr-only">Tapez votre message ici</label>
            <input
                type="text"
                name="content"
                id="content"
                class="msg-input"
                placeholder="Tapez votre message ici"
                autocomplete="off"
            />
            <button class="btn btn-filled">Envoyer</button>
        </form>
    </section>
</div>
