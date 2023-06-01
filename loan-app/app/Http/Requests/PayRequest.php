<?php

namespace App\Http\Requests;

use App\Constants\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *      schema="PayRequestSchema",
 *      @OA\Property(
 *          property="installment_id",
 *          type="integer",
 *          description="Installment ID",
 *          nullable=false,
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="amount",
 *          type="float",
 *          description="Amount",
 *          nullable=false,
 *          example="3333.33"
 *      ),
 * )
 */
class PayRequest extends FormRequest
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
            'installment_id' => 'required|numeric',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
