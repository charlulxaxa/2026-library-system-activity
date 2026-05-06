<?php
declare(strict_types=1);


namespace App\Entity;


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
