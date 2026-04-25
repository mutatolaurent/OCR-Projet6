<?php

/**
 * Classe Message représentant un message dans une conversation.
 */
class Message extends AbstractEntity
{
    private int $idThread;
    private int $idSender;
    private string $content;
    private bool $isRead;
    private ?DateTime $createdAt;

    // public function __construct(array $data = [])
    // {
    //     parent::__construct($data);
    //     $this->idThread = $data['id_thread'] ?? 0;
    //     $this->idSender = $data['id_sender'] ?? 0;
    //     $this->content = $data['content'] ?? '';
    //     $this->isRead = (bool) ($data['is_read'] ?? 0);
    //     $this->createdAt = $data['created_at'] ?? null;
    // }

    public function getIdThread(): int
    {
        return $this->idThread;
    }

    public function setIdThread(int $idThread): void
    {
        $this->idThread = $idThread;
    }

    public function getIdSender(): int
    {
        return $this->idSender;
    }

    public function setIdSender(int $idSender): void
    {
        $this->idSender = $idSender;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): void
    {
        $this->isRead = $isRead;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
    * Setter pour la date de création.
    * @param DateTime|string $created_at
    */
    public function setCreatedAt(string|DateTime $created_at, string $format = 'Y-m-d H:i:s'): void
    {
        if (is_string($created_at)) {
            $created_at = DateTime::createFromFormat($format, $created_at);
        }
        $this->createdAt = $created_at;
    }

}
