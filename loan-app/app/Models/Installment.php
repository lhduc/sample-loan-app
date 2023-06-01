<?php

namespace App\Models;

use App\Scopes\UserScope;
use Datetime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *      schema="InstallmentSchema",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Installment ID",
 *          nullable=false,
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="loan_id",
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
 *          property="installment_no",
 *          type="integer",
 *          description="Installment number",
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
 *      @OA\Property(
 *          property="due_date",
 *          type="string",
 *          description="Due date",
 *          nullable=false,
 *          format="date-time",
 *          example="2023-05-31T08:18:55.000000Z"
 *      ),
 *      @OA\Property(
 *          property="paid_date",
 *          type="string",
 *          description="Due date",
 *          nullable=true,
 *          format="date-time",
 *          example="2023-05-31T08:18:55.000000Z"
 *      ),
 *      @OA\Property(
 *          property="paid_amount",
 *          type="string",
 *          description="Paid amount",
 *          nullable=true,
 *          format="date-time",
 *          example="3333.33"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          description="Status",
 *          enum={
 *             "1 - Pending",
 *             "2 - Paid",
 *          },
 *          nullable=false,
 *          example="1"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          description="Created at",
 *          nullable=false,
 *          format="date-time",
 *          example="2023-05-31T08:18:55.000000Z"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          description="Updated at",
 *          nullable=false,
 *          format="date-time",
 *          example="2023-05-31T08:18:55.000000Z"
 *      )
 * )
 * @property int $id
 * @property int $loan_id
 * @property int $user_id
 * @property int $installment_no
 * @property float $amount
 * @property Datetime $due_date
 * @property Datetime $paid_date
 * @property float $paid_amount
 * @property int $status
 */
class Installment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'loan_id',
        'user_id',
        'installment_no',
        'amount',
        'due_date',
        'paid_date',
        'paid_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime',
        'paid_date' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope());
    }

    /**
     * @return BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
