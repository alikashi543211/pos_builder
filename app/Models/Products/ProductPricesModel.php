<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductPricesModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'product_prices';
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

    public function PriceCategory(): BelongsTo
    {
        return $this->belongsTo(PriceAgeGroupCategoriesModel::class, 'price_category_id', 'id');
    }
}
