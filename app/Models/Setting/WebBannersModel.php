<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WebBannersModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_web_banners';
    protected $primaryKey = 'id';

    protected $fillable = [
        'banner_name',
        'banner_used_in',
        'website_url',
        'web_page_section',
        'web_page_section_colums',
        'device',
        'order_number',
        'page_url'
    ];

    public function getBannerPathAttribute($value)
    {
        return $value ? storage_url_for_banner($value) : url(env('STORAGEPATH') . '/blank-gallery.png');
    }

}
