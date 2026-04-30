<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class SelfFollowException extends Exception
{
    public function __construct(string $message = 'Você não pode seguir a si mesmo.')
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json(["message" => $this->getMessage()], 403);
    }
}
