<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use SoftDeletes;
    protected $guarded=['id'];
    public $table="service_category";
    // protected $appends = ['subCategories'];

    public function advertising(){
        return  $this->hasMany(Advertising::class, 'service_category_id');
    }

    public function subCategories(){
        return  $this->hasMany(ServiceCategory::class, 'parent_id');
    }
    public function parentCategory(){
        return  $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    public static function getSubCat($parent_id = null)
    {
        return self::query()->where('is_enable' , 1)
            ->orderBy('id','desc')
            ->where('parent_id' , $parent_id )
            ->get()->map(function ($serviceCategory){
                return[
                    'id'=> $serviceCategory->id,
                    'parent_id'=> $serviceCategory->parent_id,
                    'title_en'=> $serviceCategory->title_en,
                    'title_ar'=> $serviceCategory->title_ar,
                    'image'=>  asset($serviceCategory->image),
                    'subCats' => self::getSubCat($serviceCategory->id)
                ];
            });
    }

}
