<?php
declare(strict_types=1);


namespace App\Library;

class Book{
    private string $title;
    private string $author;
    private int $year;
    private string $genre;


    public function setTitle(string $title){
        $this->title = $title;
    }

    public function setAuthor(string $author){
        $this->author = $author;
    }

    public function setYear(int $year){
        $this->year = $year;
    }

    public function setGenre(string $genre){
        $this->genre = $genre;
    }

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
}