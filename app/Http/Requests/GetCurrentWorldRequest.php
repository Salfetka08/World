<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "GetCurrentWorldRequest",
    description: "Запрос на получение текущей информации о мире",
)]
class GetCurrentWorldRequest extends FormRequest
{
    /**
     * Правила валидации
     */
    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
        ];
    }

    /**
     * Кастомные сообщения об ошибках
     */
    public function messages(): array
    {
        return [
            'userId.required' => 'Необходимо указать ID пользователя',
        ];
    }
}
