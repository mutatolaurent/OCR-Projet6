<?php

/**
 * Classe Thread représentant une conversation entre deux utilisateurs.
 */
class Thread extends AbstractEntity
{
    private int $userOneId;
    private int $userTwoId;
    private ?DateTime $createdAt;

    public function getUserOneId(): int
    {
        return $this->userOneId;
    }

    public function setUserOneId(int $userOneId): void
    {
        $this->userOneId = $userOneId;
    }

    public function getUserTwoId(): int
    {
        return $this->userTwoId;
    }

    public function setUserTwoId(int $userTwoId): void
    {
        $this->userTwoId = $userTwoId;
    }

    public function getCreatedAt(): ?string
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

    /**
     * Retourne l'ID de l'autre utilisateur dans la conversation.
     * @param int $currentUserId
     * @return int
     */
    public function getOtherUserId(int $currentUserId): int
    {
        return $this->userOneId === $currentUserId ? $this->userTwoId : $this->userOneId;
    }
}
