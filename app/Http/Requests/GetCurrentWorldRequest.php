<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "GetCurrentWorldRequest",
    description: "Запрос на получение текущей информации о мире",
    required: ["userId"]  // только userId обязателен
)]
class GetCurrentWorldRequest extends FormRequest
{
    /**
     * Правила валидации
     */
    public function rules(): array
    {
        return [
//            'userId' => 'required|integer|exists:users,id',
//            'latitude' => 'sometimes|numeric|between:-90,90',        // необязательное
//            'longitude' => 'sometimes|numeric|between:-180,180',     // необязательное
//            'timestamp' => 'sometimes|date',
            'userId' => 'required|integer',
            'latitude' => 'sometimes|numeric',        // необязательное
            'longitude' => 'sometimes|numeric',     // необязательное
            'timestamp' => 'sometimes|date',
        ];
    }

    /**
     * Кастомные сообщения об ошибках
     */
    public function messages(): array
    {
        return [
            'latitude.between' => 'Широта должна быть от -90 до 90',
            'longitude.between' => 'Долгота должна быть от -180 до 180',
            'userId.exists' => 'Пользователь не найден',
            'userId.required' => 'Необходимо указать ID пользователя',
        ];
    }
}
