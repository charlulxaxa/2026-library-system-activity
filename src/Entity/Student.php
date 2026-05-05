<?php
declare(strict_types=1);


namespace App\Library;


class Student{
    private int $studentId;
    private string $studentName;

    public function setStudentId(int $studentId){
        $this->studentId = $studentId;
    }

    public function setStudentName(string $studentName){
        $this->studentName = $studentName;
    }

    public function getStudentId(): int{
        return $this->studentId;
    }

    public function getStudentName(): string {
        return $this->studentName;
    }
}
