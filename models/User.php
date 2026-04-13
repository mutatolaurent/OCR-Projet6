<?php

/**
 * Entité User, représente un utilisateur de l'application.
 */
class User extends AbstractEntity
{
    private string $email = "";
    private ?string $password = null;
    private string $pseudo = "";
    private ?string $photo = null;
    private ?DateTime $created_at = null;

    // Cette propriété contiendra un tableau d'objets Book
    private array $books = [];

    /**
     * Setter pour l'email.
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter pour l'email.
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Setter pour le mot de passe.
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Getter pour le mot de passe.
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter pour le pseudo.
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Getter pour le pseudo.
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * Setter pour la photo.
     * @param string|null $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * Getter pour la photo.
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
     * Setter pour les livres de l'utilisateur.
     * @param Book $book
     */    public function setBooks(Book $book): void
    {
        $this->books[] = $book;
    }

    /**
     * Getter pour les livres de l'utilisateur.
     * @return array
     */    public function getBooks(): array
    {
        return $this->books;
    }
}
