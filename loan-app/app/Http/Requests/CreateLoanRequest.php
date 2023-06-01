<?php

namespace App\Http\Requests;

use App\Constants\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *      schema="CreateLoanRequestSchema",
 *      @OA\Property(
 *          property="amount",
 *          type="float",
 *          description="Amount",
 *          nullable=false,
 *          example="10000"
 *      ),
 *      @OA\Property(
 *          property="terms",
 *          type="integer",
 *          description="Terms",
 *          nullable=false,
 *          example="3"
 *      ),
 * )
 */
class CreateLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->type === UserType::USER->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'terms' => 'required|numeric|min:1',
        ];
    }
}
