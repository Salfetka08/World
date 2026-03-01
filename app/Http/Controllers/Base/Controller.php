<?php

namespace App\Http\Controllers\Base;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API для получения информации связанной с окружающим миром.",
    title: "World API",
    contact: new OA\Contact(
        email: "https://github.com/Salfetka08"
    )
)]
abstract class Controller
{
}
