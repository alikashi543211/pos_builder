<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AdminUserEntityModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_admin_user_entities';
    protected $primaryKey = 'ID';

    public function user()
    {
        return $this->belongsTo('App\Models\Acl\AdminUserModel', 'admin_ID', 'ID');
    }
 
    public function entity()
    {
        return $this->belongsTo('App\Models\EntityModel', 'entity_ID', 'ID');
    }
}
