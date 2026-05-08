<?php
declare(strict_types = 1);


namespace App\Entity;

/**
 * Book Entity
 *
 * Represents a book record in the library system.
 * Acts as a data model used across repository and service layers.
 */
class Book{
    private string $title;
    private string $author;
    private int $year;
    private string $genre;
    private int $id;

    /**
     * Book constructor
     *
     * @param string $title  Book title
     * @param string $author Book author
     * @param int $year      Publication year
     * @param string $genre  Book genre
     */
    public function __construct(string $title, string $author, int $year, string $genre){
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
    }

     /* =========================
       Specific Setters
       ========================= */

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setAuthor(string $author): void {
        $this->author = $author;
    }

    public function setYear(int $year): void {
        $this->year = $year;
    }

    public function setGenre(string $genre): void {
        $this->genre = $genre;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
    
    /* =========================
       Specific Getters
       ========================= */
    public function getTitle(): string {
        return $this->title;
    }

    public function getAuthor(): string {
        return $this->author;
    }

    public function getYear(): int {
        return $this->year;
    }

    public function getGenre(): string {
        return $this->genre;
    }

    public function getId(): int {
        return $this->id;
    }
}
