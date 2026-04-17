<?php

/**
 * Entité Book, un Book est défini par les champs
 * id, id_user, title, content, date_creation, date_update
 */
class Book extends AbstractEntity
{
    private int $idUser;
    private string $title = "";
    private string $author = "";
    private string $description = "";
    private int $idState;
    private ?string $photo = "";
    private ?string $stateLabel = "";
    private ?DateTime $created_at = null;

    private ?User $owner = null;

    /**
     * Setter pour l'id de l'utilisateur.
     * @param int $idUser
     */
    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * Getter pour l'id de l'utilisateur.
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * Setter pour le titre du livre.
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter pour l'id de l'utilisateur.
     * @return
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
    * Setter pour l'auteur du livre.
    * @param string $author
    */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
    * Getter pour l'auteur du livre.
    * @return string
    */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
    * Setter pour la description du livre.
    * @param string $description
    */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
    * Getter pour la description du livre.
    * @return string
    */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
    * Setter pour l'id de l'état.
    * @param int $idState
    */
    public function setIdState(int $idState): void
    {
        $this->idState = $idState;
    }

    /**
    * Getter pour l'id de l'état.
    * @return int
    */
    public function getIdState(): int
    {
        return $this->idState;
    }

    /**
    * Setter pour le label de l'état
    * @param int $stateLabel
    */
    public function setStateLabel(string $stateLabel): void
    {
        $this->stateLabel = $stateLabel;
    }

    /**
    * Getter pour le label de l'état
    * @return string
    */
    public function getStateLabel(): string
    {
        return $this->stateLabel;
    }

    /**
    * Setter pour la photo du livre.
    * @param string|null $photo
    */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
    * Getter pour la photo du livre.
    * @return string|null
    */
    public function getPhoto(): ?string
    {
        return $this->photo;
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

    /**
    * Setter pour le propriétaire du livre.
    * @param User $owner
    */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
    * Getter pour le propriétaire du livre.
    * @return User|null
    */
    public function getOwner(): ?User
    {
        return $this->owner;
    }
}
