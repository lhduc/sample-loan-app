<?php

namespace App\Http\Controllers;

use App\Constants\InstallmentStatus;
use App\Events\InstallmentPaid;
use App\Services\InstallmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @var InstallmentService
     */
    private InstallmentService $installmentService;

    /**
     * @param InstallmentService $installmentService
     */
    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     * @OA\Post (
     *      path="/api/v1/payments",
     *      tags={"Loan"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              ref="#/components/schemas/PayRequestSchema"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/LoanSchema"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function pay(Request $request): JsonResponse
    {
        $amount = $request->get('amount');
        $installment = $this->installmentService->getInstallment($request->get('installment_id'));
        if (!$installment) {
            abort(400, 'This installment does not exist');
        }

        // Validate status and amount
        if ($installment->status == InstallmentStatus::PAID->value)  {
            abort(400, 'This installment has paid');
        }
        if ($amount < $installment->amount)  {
            abort(400, 'Insufficient amount');
        }

        $installment = $this->installmentService->setToPaid($installment->id, $amount);

        InstallmentPaid::dispatch($installment);

        return $this->success($installment);
    }
}
