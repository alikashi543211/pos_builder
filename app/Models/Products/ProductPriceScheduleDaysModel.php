<?php

namespace App\Models\Products;

use App\Models\DaysOfWeekModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductPriceScheduleDaysModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'product_price_schedule_days';
    protected $primaryKey = 'id';

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductPriceSchedule(): BelongsTo
    {
        return $this->belongsTo(ProductPriceScheduleModel::class, 'product_price_schedule_id', 'id');
    }

    public function DayOfWeek(): BelongsTo
    {
        return $this->belongsTo(DaysOfWeekModel::class, 'day_id', 'id');
    }
}
