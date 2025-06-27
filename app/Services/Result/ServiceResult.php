<?php

namespace App\Services\Result;

class ServiceResult
{
    public bool $success;
    public $data;
    public ?string $message;

    public function __construct(bool $success, $data = null, ?string $message = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->message = $message;
    }
}
