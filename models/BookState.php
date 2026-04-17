<?php

/**
 * Entité BookState
 */
class BookState extends AbstractEntity
{
    private string $state = "";
    private ?DateTime $created_at = null;

    /**
     * Setter pour le nom de l'état.
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * Getter pour le nom de l'état
     * @return
     */
    public function getState(): string
    {
        return $this->state;
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
        $this->created_at = $created_at;
    }

    /**
    * Getter pour la date de création.
    * @return DateTime|null
    */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }
}
