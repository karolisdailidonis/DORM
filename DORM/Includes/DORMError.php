<?php
namespace DORM\Includes;

class DORMError
{
    protected array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function add(string $message, $errors = []): void
    {
        $this->errors[] = array('message' => $message, 'errors' => $errors);
    }
}