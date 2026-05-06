<?php
declare(strict_types = 1);


namespace App\Entity;


use DateTime;

class BorrowRecord {

    private string $status;
    private int $studentId;
    private float $fineAmount;
    private DateTime $dueDate;
    private DateTime $borrowDate;
    private int $bookId;
    private ?int $borrowRecordId;

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setStudentId(int $studentId): void {
        $this->studentId = $studentId;
    }

    public function setFineAmount(float $fineAmount): void {
        $this->fineAmount = $fineAmount;
    }

    public function setDueDate(DateTime $dueDate): void {
        $this->dueDate = $dueDate;
    }

    public function setBorrowDate(DateTime $borrowDate): void {
        $this->borrowDate = $borrowDate;
    }

    public function setBookId(int $bookId): void {
        $this->bookId = $bookId;
    }

    public function setBorrowRecordId(?int $borrowRecordId): void {
        $this->borrowRecordId = $borrowRecordId;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getStudentId(): int {
        return $this->studentId;
    }

    public function getFineAmount(): float {
        return $this->fineAmount;
    }

    public function getDueDate(): DateTime {
        return $this->dueDate;
    }

    public function getBorrowDate(): DateTime {
        return $this->borrowDate;
    }

    public function getBookId(): int {
        return $this->bookId;
    }

    public function getBorrowRecordId(): ?int {
        return $this->borrowRecordId;
    }
}
