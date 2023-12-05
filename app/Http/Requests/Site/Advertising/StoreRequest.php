<?php

namespace App\Http\Requests\Site\Advertising;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $phone_required = request()->has("purpose") && request()->purpose != 'required_for_rent' ? 'required' :'nullable';

        if (!in_array($this->method(), ['PUT', 'PATCH'])) { // create
            $rules = [
                'phone_number' =>  'required|digits:8',
                'advertising_type' => 'required|in:normal,premium',
                'venue_type' => 'required',
                'purpose' => 'required|in:rent,sell,exchange,commercial,required_for_rent,service',
                'city_id' => 'nullable',
                'area_id' => 'required|numeric|exists:areas,id',
                'price' => 'nullable|numeric',
                'other_image' => 'nullable|array|min:1',
                'other_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:50000',
                //            // 'other_image' => 'nullable|array',
                //            //   'other_image.*' => 'mimes:jpeg,bmp,png|max:2048',
                //            'main_image' => 'nullable|mimes:jpeg,bmp,png|max:2048',
                //            'floor_plan' => 'nullable|mimes:jpeg,bmp,png|max:2048',
                //             'number_of_rooms' => 'nullable',
                //             'number_of_bathrooms' => 'nullable',
                //             'number_of_master_rooms' => 'nullable',
                //             'number_of_parking' => 'nullable',
                //            'gym' => 'required|in:1,0',
                //            'pool' => 'required|in:1,0',
                //            'furnished' => 'required|in:1,0',
            ];
        } else { // edit
            $rules = [
                'phone_number' => 'required|digits:8',
                'advertising_type' => 'nullable',
                'venue_type' => 'required',
                'purpose' => 'required|in:rent,sell,exchange,commercial,required_for_rent,service',
                'city_id' => 'nullable',
                'area_id' => 'required|numeric|exists:areas,id',
                'price' => 'nullable|numeric',
                'other_image' => 'nullable|array|min:1',
                'other_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:50000',
            ];
        }
        if( request()->get('service' , false)) {
            $rules['phone_number'] = 'required';
            $rules['area_id'] = 'nullable|numeric|exists:areas,id';
            $rules['venue_type'] = 'nullable';
            $rules['other_image'] = 'nullable';
        }

        if ( request()->hasFile("video")  )
            $rules['video'] = 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:20000';
        elseif ( request()->has("video") and is_string(request()->has("video")) )
            $rules['video'] = 'nullable|string';

        return $rules;
    }

    public function attributes()
    {
        return [
            'other_image.*' => 'IMAGE',
            'other_image' => 'IMAGE',
            'city_id' => 'CITY',
            'area_id' => 'AREA',
        ] ;
    }
}
