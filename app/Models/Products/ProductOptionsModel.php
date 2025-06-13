<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductOptionsModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'product_options';
    protected $primaryKey = 'id';

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Product(): BelongsTo
    {
        return $this->belongsTo(ProductsModel::class, 'product_id', 'id');
    }

    /**
     * Get the accessibility that owns the ProductAccessibilitiesModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductPriceSchedules(): HasMany
    {
        return $this->hasMany(ProductPriceScheduleModel::class, 'product_option_id', 'id');
    }
}
