<?php

namespace App\Models\Products;

use App\Models\StatusesModel;
use App\Models\Suppliers\SupplierModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductsModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'products';
    protected $primaryKey = 'id';

    /**
     * Get the supplier that owns the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductSupplier(): BelongsTo
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'id');
    }

    /**
     * Get the supplier that owns the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProductStatus(): BelongsTo
    {
        return $this->belongsTo(StatusesModel::class, 'status_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductAccessibilities(): HasMany
    {
        return $this->hasMany(ProductAccessibilitiesModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductAgeRanges(): HasMany
    {
        return $this->hasMany(ProductAgeRangeModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductCategories(): HasMany
    {
        return $this->hasMany(ProductCategoriesModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductExclusions(): HasMany
    {
        return $this->hasMany(ProductExclusionsModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductGuideLanguages(): HasMany
    {
        return $this->hasMany(ProductGuideLanguagesModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductImages(): HasMany
    {
        return $this->hasMany(ProductImagesModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductInclusions(): HasMany
    {
        return $this->hasMany(ProductInclusionsModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductItinerary(): HasMany
    {
        return $this->hasMany(ProductItineraryModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductOptions(): HasMany
    {
        return $this->hasMany(ProductOptionsModel::class, 'product_id', 'id');
    }

    /**
     * Get all of the ProductAccessibilities for the ProductsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductPickups(): HasMany
    {
        return $this->hasMany(ProductPickupModel::class, 'product_id', 'id');
    }
}
