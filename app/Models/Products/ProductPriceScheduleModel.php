<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductPriceScheduleModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'product_price_schedule';
    protected $primaryKey = 'id';

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductOption(): BelongsTo
    {
        return $this->belongsTo(ProductOptionsModel::class, 'product_option_id', 'id');
    }

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductPriceScheduleDays(): HasMany
    {
        return $this->hasMany(ProductPriceScheduleDaysModel::class, 'product_price_schedule_id', 'id');
    }

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductPriceTimeSlots(): HasMany
    {
        return $this->hasMany(ProductPriceTimeSlotsModel::class, 'product_price_schedule_id', 'id');
    }

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductPrices(): HasMany
    {
        return $this->hasMany(ProductPricesModel::class, 'product_price_schedule_id', 'id');
    }
}
