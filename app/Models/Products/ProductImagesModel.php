<?php

namespace App\Models\Products;

use App\Models\StatusesModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductImagesModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'product_images';
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
    public function ProductImageStatus(): BelongsTo
    {
        return $this->belongsTo(StatusesModel::class, 'status_id', 'id');
    }

    public function getProductImageFilenameAttribute($value)
    {
        return $value ? storage_url($value) : photo('avatars', 'empty.png');
    }
}
