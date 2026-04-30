<?php

namespace App\Http\Controllers;

use App\Support\Swagger\OpenApiSpec;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SwaggerController extends Controller
{
    public function index(): View
    {
        return view('swagger.index', [
            'specUrl' => route('swagger.spec'),
        ]);
    }

    public function spec(): JsonResponse
    {
        return response()->json(OpenApiSpec::document());
    }
}