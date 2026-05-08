<?php
declare(strict_types=1);


namespace App\Entity;

/**
 * Represents a student in the library system.
 *
 * Stores basic student information used for borrowing records
 * and tracking library transactions.
 *
 */
class Student{
    private int $studentId;
    private string $studentName;

    public function setStudentId(int $studentId): void {
        $this->studentId = $studentId;
    }

    public function setStudentName(string $studentName): void {
        $this->studentName = $studentName;
    }

    public function getStudentId(): int{
        return $this->studentId;
    }

    public function getStudentName(): string {
        return $this->studentName;
    }
}
