<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\Area;
use App\Models\City;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\VenueType;
use App\User;
use Illuminate\Http\Request;

class GeneralController extends ApiBaseController
{

    public function getSettings()
    {

        $settings=Setting::select(['setting_key','setting_value'])->get()->keyBy('setting_key');
        $venueTypes=VenueType::where('is_enable',1)->get();
        $paymentTypes=PaymentType::where('is_enable',1)->get();
        return $this->success("",['settings'=>$settings,'venueTypes'=>$venueTypes,'paymentTypes'=>$paymentTypes]);
    }

    public function getServiceCategories(Request $request)
    {
        $parent_id = $request->get('parent_id' , null);
        $parent_id = intval($parent_id) == 0 ? null : $parent_id ;
        $serviceCategories = cache()->remember('serviceCategories' , 0 , function () use ($parent_id) {
                return ServiceCategory::getSubCat($parent_id);
            } );
        return $this->success("",['serviceCategories'=>$serviceCategories]);
    }

    public function getSetting()
    {
        $settings = [] ;
        Setting::select(['setting_key','setting_value'])->get()
            ->map(function ($item) use (&$settings){
                $settings[$item->setting_key ] = $item->setting_value;
            });
        $venueTypes=VenueType::where('is_enable',1)->get();
        $paymentTypes=PaymentType::where('is_enable',1)->get();
        return $this->success("",['settings'=>$settings,'venueTypes'=>$venueTypes,'paymentTypes'=>$paymentTypes]);
    }
    public function getSettingMarqueeText()
    {
        $settings = [] ;
        Setting::select(['setting_key','setting_value'])->whereIn('setting_key' , ['marquee_en' , 'marquee_ar'])->get()
            ->map(function ($item) use (&$settings){
                $settings[$item->setting_key ] = strip_tags(preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($item->setting_value)))));
            });
        return $this->success("",$settings);
    }

    public function getCities()
    {
        $cities=City::withCount(['advertising' => function($query){
            $query->whereNotNull('expire_at')
            ->where('expire_at', '>', date('Y-m-d'))
            ->whereHas("user")->where("purpose", "!=" , 'required_for_rent');
        }])->with(['areas' => function($query){
            $query->withCount(['advertising' => function($query){
                $query->whereNotNull('expire_at')
                ->where('expire_at', '>', date('Y-m-d'))
                ->whereHas("user")->where("purpose", "!=" , 'required_for_rent');
            }]);
        }])->orderBy('id','DESC')->get();
        return $this->success("",$cities);
    }

    public function getAreas(Request $request)
    {
        $areas=Area::where('is_active',1);

        if(isset($request->cityId)){
            $areas=$areas->where('city_id',$request->cityId);
        }
        $areas=$areas->get();
        return $this->success("",$areas);
    }


    public function getStaticPackages(Request $request)
    {
        $packages=Package::where('is_enable',1)->where('type',"static")->where('is_visible',1)->get();
        return $this->success("",['packages'=>$packages]);

    }


    public function getPackages(Request $request)
    {
        $packages=Package::where('is_enable',1)->where('is_visible',1)->where('type',"!=","static")->get();
        return $this->success("",['packages'=>$packages]);
    }


    public function getSearchProperty(Request $request)
    {

        $data=[
                 'type'=>VenueType::all(),
              ];

        return $this->success("",$data);
    }


    public function is_valid(Request $request)
    {
        $user = User::query()->where("api_token",$request->api_token)->exists();
        return $this->success("",$user);
    }

    public function test(Request $request)
    {

        $packages= VenueType::where('is_enable',1)->get();
        dd($packages);
        $mainImageFile = $request->image;
        $fileName = $mainImageFile->getClientOriginalName();
        $storeName = uniqid(time()).$fileName;


        $path = public_path('resources/uploads/images/') . $storeName;
        $mainImageFile->move(public_path('resources/uploads/images/'), $storeName);
        return $this->success($path);
    }




}
