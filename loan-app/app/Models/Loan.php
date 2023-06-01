<?php

namespace App\Models;

use App\Constants\InstallmentStatus;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Throwable;

/**
 * @OA\Schema(
 *      schema="LoanSchema",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Loan ID",
 *          nullable=false,
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="user_id",
 *          type="integer",
 *          description="User ID",
 *          nullable=false,
 *          example="1"
 *      ),
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
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          description="Status",
 *          enum={
 *             "1 - Pending",
 *             "2 - Approved",
 *             "3 - Canceled",
 *             "4 - Paid",
 *          },
 *          nullable=false,
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          description="Created at",
 *          nullable=false,
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          description="Updated at",
 *          nullable=false,
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="installments",
 *          type="array",
 *          description="List of installments",
 *          @OA\Items(
 *              ref="#/components/schemas/InstallmentSchema"
 *          )
 *      )
 * )
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property int $terms
 * @property int $status
 */
class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'terms',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    /**
     * @return HasMany
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function generateInstallments(): void
    {
        if (!$this->installments()->count()) {
            $this->getConnection()->transaction(function () {
                $installmentAmount = round($this->amount / $this->terms, 2);
                for ($i = 1; $i <= $this->terms; $i++) {
                    $now = new \DateTime($this->created_at);
                    $dueDate = $now->add(\DateInterval::createFromDateString($i . ' week'));
                    $installment = new Installment();
                    $installment->loan_id = $this->id;
                    $installment->user_id = $this->user_id;
                    $installment->installment_no = $i;
                    $installment->amount = $installmentAmount;
                    $installment->due_date = $dueDate;
                    $installment->status = InstallmentStatus::PENDING->value;
                    $this->installments()->save($installment);
                }
            });
        }
    }
}
