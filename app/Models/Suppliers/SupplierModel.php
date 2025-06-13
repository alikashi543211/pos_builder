<?php

namespace App\Models\Suppliers;

use App\Models\CategoriesModel;
use App\Models\CitiesModel;
use App\Models\CountriesModel;
use App\Models\EmployeesModel;
use App\Models\Products\ProductsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Suppliers\SupplierContractsModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SupplierModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_supplier';

    public function SupplierContracts()
    {
        return $this->hasMany(SupplierContractsModel::class, 'supplier_id');
    }
    public function SupplierAssignedUser()
    {
        return $this->hasMany(SupplierAssignedUsersModel::class, 'supplier_id');
    }
    public function SupplierContactPerson()
    {
        return $this->hasMany(SupplierContactPersonsModel::class, 'supplier_id');
    }
    public function SupplierType()
    {
        return $this->belongsTo(SupplierTypeModel::class, 'supplier_type_id', 'supplier_type_id');
    }
    public function SupplierCountry()
    {
        return $this->belongsTo(CountriesModel::class, 'supplier_legal_registered_country', 'country_id');
    }
    public function SupplierCity()
    {
        return $this->belongsTo(CitiesModel::class, 'supplier_experience_city', 'city_id');
    }

    public function SupplierUserProducts()
    {
        return $this->hasMany(ProductsModel::class, 'supplier_id', 'id');
    }

    public function SupplierExcursions()
    {
        return $this->hasMany(SupplierExcursionsModel::class, 'supplier_id');
    }
    public function SupplierUsers()
    {
        return $this->hasMany(SupplierUserModel::class, 'supplier_id');
    }
    public function employee(){
        return $this->belongsTo(EmployeesModel::class, 'created_by', 'user_name');
    }
    /**
     * The supplierCategories that belong to the SupplierModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function SupplierCategories() : BelongsToMany
    {
        return $this->belongsToMany(CategoriesModel::class, 'tbl_supplier_categories', 'supplier_id', 'category_id');
    }

    public function getStatusAttribute($value)
    {
        $class = 'light-info';
        $title = 'Status of supplier profile.';
        if($value == 'Pending'){
            $class = "light-info";
            $title = 'The profile is currently in progress.';
        }elseif($value == 'In Review'){
            $class = "light-primary";
            $title = 'The profile is currently in review.';
        }elseif($value == 'In Modification'){
            $class = "light-danger";
            $title = 'The profile is currently in modification.';
        }elseif($value == 'Approved'){
            $class = "light-success";
            $title = 'The profile is currently in approved.';
        }

        return '<span data-bs-toggle="tooltip" title="'. $title .'" class="badge badge-pill badge-' . $class . '">' . $value . '</span>';
    }

    public function getSupplierCompanyLogoAttribute($value)
    {
        return $value ? storage_url($value) : photo('avatars', 'empty.png');
    }

    public function getCompanyRegistrationCertificateAttribute($value)
    {
        return $value ? storage_url_for_document($value) : null;
    }

    public function scopeSupplierTypeNotNull($query)
    {
        $query->whereIn('supplier_type', ['Freelancer', 'Company']);
    }


}
