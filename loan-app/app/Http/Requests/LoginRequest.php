<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="LoginRequestSchema",
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          description="User email",
 *          nullable=false,
 *          example="lehongduc87@gmail.com"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          type="string",
 *          description="User password",
 *          nullable=false,
 *          example="password"
 *      ),
 * )
 * @property string $email
 * @property string $password
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
