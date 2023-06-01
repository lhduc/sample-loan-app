<?php

namespace App\Http\Controllers;

use App\Constants\UserType;
use App\Exceptions\BadRequestException;
use App\Http\Requests\CreateLoanRequest;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LoanController extends Controller
{
    /**
     * @var LoanService
     */
    private LoanService $loanService;

    /**
     * @param LoanService $loanService
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * @OA\Get (
     *      path="/api/v1/loans",
     *      tags={"Loan"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="page",
     *          description="Page",
     *          in="query",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      ref="#/components/schemas/LoanSchema",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        if ($request->get('page')) {
            return response()->json($this->loanService->getLoansWithPagination());
        }

        return $this->success($this->loanService->getLoans());
    }

    /**
     * @OA\Get (
     *      path="/api/v1/loans/{id}",
     *      tags={"Loan"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Loan ID",
     *          in="path",
     *          required=true
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
     *      )
     * )
     * @param $id
     * @return JsonResponse
     */
    public function getDetails($id): JsonResponse
    {
        return $this->success($this->loanService->getLoan($id));
    }

    /**
     * @OA\Post (
     *      path="/api/v1/loans",
     *      tags={"Loan"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              ref="#/components/schemas/CreateLoanRequestSchema"
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
     *          response=422,
     *          description="Validation error",
     *      )
     * )
     * @param CreateLoanRequest $request
     * @return JsonResponse
     */
    public function create(CreateLoanRequest $request): JsonResponse
    {
        $loan = $this->loanService->create(
            Auth::id(),
            $request->get('amount'),
            $request->get('terms'),
        );

        return $this->success($loan);
    }

    /**
     * @OA\Post (
     *      path="/api/v1/loans/{id}/approve",
     *      tags={"Loan"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Loan ID",
     *          in="path",
     *          required=true
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
     *          response=400,
     *          description="Bad request",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     * @param int $loanId
     * @return JsonResponse
     * @throws Throwable
     */
    public function approve(int $loanId): JsonResponse
    {
        // Only admin can approve loan
        /** @var User $user */
        $user = Auth::user();
        if ($user->type !== UserType::ADMIN->value) {
            abort(401, 'Forbidden');
        }

        // Loan must be existed
        $loan = $this->loanService->getLoan($loanId);
        if (!$loan) {
           abort(400, 'Loan does not exist');
        }

        try {
            $loan = $this->loanService->approve($loan);
        } catch (BadRequestException $exception) {
            abort($exception->getStatusCode(), $exception->getMessage());
        }

        return $this->success($loan);
    }
}
