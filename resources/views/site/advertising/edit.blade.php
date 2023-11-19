@extends('site.layout.master')

@section('title' , __('edit_ad_title'))
@section('disableHeaderNavbar' , 'yes')

@section('head')
{{-- {{ dd(@$errors,@$error) }} --}}
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<style>
    .filepond--item {
        width: calc(50% - 0.5em);
    }

    @media (min-width: 30em) {
        .filepond--item {
            width: calc(50% - 0.5em);
        }
    }

    @media (min-width: 50em) {
        .filepond--item {
            width: calc(33.33% - 0.5em);
        }
    }

    .invalid-feedback {
        display: unset !important;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
    integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
    integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>

@endsection

@php
$unSide = app()->getLocale() === 'en' ? 'l' : 'r';
@endphp

@section('content')

    <section>
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-lg-3 mob_hide">&nbsp;</div>

                <form action="{{ str_contains(request()->path(), 'create') ? route('site.advertising.store', app()->getLocale()) : route('site.advertising.updateAdvertising', app()->getLocale()) }}" method="post" id="sp-basic-form" class="row" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div class="seach_container">
                            @if (str_contains(request()->path(), 'create'))
                                <h3 class="text-center">{{request()->get('service' , false) ? __('add_a_service'): __('create_ad_title')}}</h3>
                            @else
                                @method('PUT')
                                <input type="hidden" name="id" value="{{@$advertising->id}}">
                                <h3 class="text-center">{{request()->get('service' , false) ? __('edit_a_service'): __('edit_ad_title')}}</h3>
                            @endif
                            @if((session('status')) == 'unsuccess')
                                <div class="alert alert-danger mt-3">
                                    <strong>{{__('un_success_title')}}!</strong> {{__('un_success_alert_title')}}!
                                </div>
                            @endif

                            @if (str_contains(request()->path(), 'required_for_rent'))
                                <input type="hidden" name="advertising_type" value="normal">
                            @else
                                @if(env('NORMAL_ADS_FREE' , false) && !str_contains(request()->path(), 'create'))
                                    @if( in_array("premium",[old('advertising_type',request()->get('type') ) , @$advertising->advertising_type]) )
                                        <input type="hidden" name="advertising_type" value="premium">
                                        <p>
                                            <label>
                                                {{__('normal_title')}}
                                                @if($credit['count_normal_advertising'] > 0)
                                                    <span class="text-success m{{$unSide}}-1">{{$credit['count_normal_advertising']}} {{__('remaining_title')}}</span>
                                                @else
                                                    <span class="text-danger m{{$unSide}}-1">{{$credit['count_normal_advertising']}} {{__('remaining_title')}}</span>
                                                @endif
                                            </label>
                                        </p>
                                        <a href="{{ route('Main.buyPackage',app()->getLocale()) }}" class="w-100 px-2 mb-3 primary-color links">
                                            {{__('buy_package_title')}}
                                        </a>
                                        <hr>
                                    @else
                                        <input type="hidden" name="advertising_type" value="normal">
                                    @endif
                                @else
                                    {{--                            <p class="uppercase m-2 fw-500">{{__('ADVERTISE_TYPE')}}</p>--}}
                                    <p>
                                        <input type="radio" style="width: auto;" id="normal" name="advertising_type" value="normal"
                                            {{ old('advertising_type',
                                                    @$advertising->advertising_type) =="normal" ? 'checked' : '' }}>
                                        <label for="normal">
                                            {{__('normal_title')}}
                                            @if($credit['count_normal_advertising'] > 0)
                                                <span class="text-success m{{$unSide}}-1">{{$credit['count_normal_advertising']}} {{__('remaining_title')}}</span>
                                            @else
                                                <span class="text-danger m{{$unSide}}-1">{{$credit['count_normal_advertising']}} {{__('remaining_title')}}</span>
                                            @endif
                                        </label>
                                    </p>
                                    <p>
                                        <input type="radio" style="width: auto;" id="premium" name="advertising_type" value="premium"
                                            {{ ((old('advertising_type', @$advertising->advertising_type ) || request()->type) =="premium") && $credit['count_premium_advertising'] > 0 ? 'checked' : '' }}
                                            {{ (!@$advertising && $credit['count_premium_advertising'] <= 0)
                                            ? 'disabled' : '' }}>
                                        <label for="premium">
                                            {{__('premium_short')}}
                                            @if($credit['count_premium_advertising'] > 0)
                                                <span class="text-success m{{$unSide}}-1">{{$credit['count_premium_advertising']}} {{__('remaining_title')}}</span>
                                            @else
                                                <span class="text-danger m{{$unSide}}-1">{{$credit['count_premium_advertising']}} {{__('remaining_title')}}</span>
                                            @endif
                                        </label>
                                    </p>
                                    <div class="my-2 p-0">
                                        @error('advertising_type')
                                        <div class="invalid-feedback warn-color d-inline-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                        @enderror
                                    </div>
                                    <hr>
                                @endif
                            @endif
{{--                            @if (str_contains(request()->path(), 'required_for_rent'))--}}
{{--                                {{ __('request_a_property') }}--}}
{{--                            @endif--}}

{{--                                <div class="col-xs-12   p-2 d-none">--}}
{{--                                    <div class="mdc-text-field mdc-text-field--outlined">--}}
{{--                                        <input class="mdc-text-field__input" placeholder="{{__('full_name_title')}}"--}}
{{--                                               value="{{auth()->user()->name}}" disabled required>--}}
{{--                                        <div class="mdc-notched-outline mdc-notched-outline--upgraded">--}}
{{--                                            <div class="mdc-notched-outline__leading"></div>--}}
{{--                                            <div class="mdc-notched-outline__notch">--}}
{{--                                                <label class="mdc-floating-label"--}}
{{--                                                       style="">{{__('full_name_title')}}</label>--}}
{{--                                            </div>--}}
{{--                                            <div class="mdc-notched-outline__trailing"></div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


                            @if(request()->get('service' , false))
                                <div class="mb-20">
                                    <label>{{__('title')}}</label>
                                    <input type="text" dir="rtl" class="input text-right"  name="title_en"
                                           placeholder="{{__('title')}}"
                                           value="{{ old('title_en', @$advertising->title_en ?? "")}}"
                                           required>
                                    @error('title_en')
                                        <div class="invalid-feedback warn-color d-inline-block">
                                                <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            @endif
                            <div class="mb-20">
                                <label> {{__('phone_number_title')}}</label>
                                <input type="text" dir="ltr" class="input text-left"  name="phone_number"
                                       placeholder="{{__('phone_number_title')}}"
                                       value="{{ old('phone_number', @$advertising->phone_number ?? auth()->user()->mobile)}}"
                                       @if( ! request()->get('service' , false)) readonly @endif required>
                                @error('phone_number')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                            <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            @if(request()->get('service' , false))
                                <div class="mb-20">
                                    <label> {{__('city')}}</label>
                                    <select name="city_id">
                                        @foreach($cities as $city)
                                            @php $isOld = old('city_id', @$advertising->city_id) == $city->id;
                                            @endphp
                                            <option {{ $isOld ? 'selected' : '' }} value="{{ $city->id }}">
                                                {{ app()->getLocale() == 'en' ? $city->name_en : $city->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <input type="hidden" name="purpose" value="service">
                                <input type="hidden" name="service" value="1">
                            @else
                                <div class="mb-20">
                                    <label> {{__('Area')}}</label>
                                    <select name="area_id" id="area_id"></select>
                                    @error('area_id')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <script type="module">
                                    $(document).ready(function() {
                                        fill_area_list(null)
                                    })
                                    function fill_area_list (city_id) {
                                        $.post('/{{app()->getLocale()}}/areas', {city_id}, function(data, status){
                                            console.log({status,data})
                                            if (status === 'success') {
                                                $('#area_id').empty()
                                                $('#area_id').val('')
                                                let oldId = '{{ old('area_id', @$advertising->area_id ? @$advertising->area_id : 'null') }}';
                                                let selectedArea = null;
                                                $.each(data, function(index, area) {
                                                    selectedArea = oldId && area.id == oldId ? area.name_{{app()->getLocale()}} : selectedArea;
                                                    let selectedAttr = oldId && area.id == oldId ? `selected` : null;
                                                    let option = `<option  ${selectedAttr} value="${area.id}">${area.name_{{app()->getLocale()}}}</option>`
                                                    $('#area_id').append(option);
                                                });
                                            } else
                                                console.error('error in get areas with ajax request')
                                        })
                                    }
                                </script>


                                <div class="mb-20">
                                    <label> {{__('property_type')}}</label>
                                    <select name="venue_type">
                                        @foreach($types as $type)
                                            @php $isOld = old('venue_type', @$advertising->venue_type) == $type->id;
                                            @endphp
                                            <option {{ $isOld ? 'selected' : '' }} value="{{@$type->id}}">
                                                {{ app()->getLocale() == 'en' ? $type->title_en : $type->title_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('venue_type')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                @if (str_contains(request()->path(), 'required_for_rent'))
                                    <input type="hidden" name="purpose" value="required_for_rent">
                                @else
                                    <div class="mb-20">
                                        <label> {{__('purpose')}}</label>
                                        <select name="purpose">
                                            @foreach($purposes as $purpose)
                                                @php $isOld = old('purpose', @$advertising->purpose) == $purpose;
                                                @endphp
                                                <option {{ $isOld ? 'selected' : '' }} value="{{@$purpose}}">
                                                    {{ __($purpose) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('purpose')
                                        <div class="invalid-feedback warn-color d-inline-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                @endif
                            @endif
                            <div class="mb-20">
                                <label>{{__('price_title')}}
                                    ({{__('kd_title')}})</label>
                                <input type="text" dir="ltr" class="input text-left"  name="price"
                                       placeholder="{{__('price_title')}} ({{__('kd_title')}})"
                                       value="{{ old('price', @$advertising->price)}}">
                                @error('price')
                                <div class="invalid-feedback warn-color d-inline-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-20">
                                <label>{{__('description_title')}}</label>
                                <textarea class="input" name="description" rows="5">{{ old('description', @$advertising->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            @if (!str_contains(request()->path(), 'required_for_rent'))
                                @if(env('CAN_UPLOAD_VIDEO_IN_SITE' , true))
                                    @if( old('video' , @$advertising->video) )
                                        <input style="visibility: hidden;position: absolute;" name="video"
                                               type="text" value="{{ old('video' , @$advertising->video) }}"
                                               accept="video/mp4,video/x-m4v,video/*" id="input_video"
                                               onchange="$('#name_video').val($(this).val().replace(/C:\\fakepath\\/i, ''))">
                                        <input class="custom-file-input"
                                               onclick="$('#input_video').attr('type' , 'file').trigger('click');"
                                               id="name_video" lang="es" style="margin: -40px 0 0 0;"
                                               value="{{ old('video' , @$advertising->video) }}">
                                        <label class="green_btn mt-10" for="name_video">{{__('video')}} &nbsp;<i class="fa fa-file-video-o fa-lg"></i></label>
                                    @else
                                        <input style="visibility: hidden;position: absolute;" name="video"
                                               type="file" accept="video/mp4,video/x-m4v,video/*" id="input_video"
                                               onchange="$('#name_video').val($(this).val().replace(/C:\\fakepath\\/i, ''))">
                                        <input class="custom-file-input"
                                               onclick="$('#input_video').attr('type' , 'file').trigger('click');"
                                               id="name_video" lang="es" style="margin: -40px 0 0 0;">
                                        <label class="green_btn mt-10" for="name_video">{{__('video')}} &nbsp;<i class="fa fa-file-video-o fa-lg"></i></label>
                                    @endif
                                    @error('video')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                @else
                                    @if( old('video' , @$advertising->video) )
                                        <input name="video" type="hidden" value="{{ old('video' , @$advertising->video) }}">
                                    @endif
                                @endif

                                <div class="col-xs-12 p-2">
                                    <input type="file" class="custom-file-input" id="customFileImages" name="other_image[]" lang="es" style="margin: -40px 0 0 0;" accept=".png,.jpg,.jpeg">
                                    <label class="green_btn mt-10" for="customFileImages">{{__('image')}} &nbsp;<i class="fa fa-image fa-lg"></i></label>
                                    @error('other_image.*')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                    @error('other_image')
                                    <div class="invalid-feedback warn-color d-inline-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            @endif


                            @if(env('CAN_CHOOSE_LOCATION_IN_SITE' , true) and ! request()->get('service' , false))
                                <div class="col-xs-12 p-2">
                                    <div id="map" style="width: 100%;height: 250px;border-radius: 5px;"></div>
                                    <input type="hidden" id="location_lat" name="location_lat">
                                    <input type="hidden" id="location_long" name="location_long">
                                </div>
                            @else
                                @if( old('location_lat', @$advertising->location_lat) )
                                    <input type="hidden" value="{{ old('location_lat', @$advertising->location_lat) }}"
                                           name="location_lat">
                                    <input type="hidden"
                                           value="{{ old('location_long', @$advertising->location_long) }}"
                                           name="location_long">
                                @endif
                            @endif
                            <div class="text-center mt-30"><button class="btn btn_lg"><strong>
                                        @if (str_contains(request()->path(), 'create'))
                                            @if(request()->get('service' , false))
                                                {{ __('upload_your_service') }}
                                            @else
                                                {{ __('upload_your_ad') }}
                                            @endif
                                        @else
                                            {{__('edit_title')}}
                                        @endif
                                    </strong></button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>



@endsection
@section('scripts')

@if(env('CAN_CHOOSE_LOCATION_IN_SITE' , true))
<script>
    $(document).ready(function () {
                $('#choose-file').change(function () {
                    var i = $(this).prev('label').clone();
                    var file = $('#choose-file')[0].files[0].name;
                    $(this).prev('label').text(file);
                });
            });
            window.onload = function() {
                var map = L.map('map').setView([{{ old('location_lat', (isset($advertising) ? $advertising->location_lat : 29.303844 )) }}, {{ old('location_long', (isset($advertising) ? $advertising->location_long : 47.979262 )) }}], 12.5);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(map);
                var marker = L.marker([{{ old('location_lat', (isset($advertising) ? $advertising->location_lat : 29.303844 )) }}, {{ old('location_long', (isset($advertising) ? $advertising->location_long : 47.979262 )) }}],{
                    draggable: true,
                    autoPan: true
                }).addTo(map);

                const options = {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                };
                function success(pos) {
                    const crd = pos.coords;
                    $('#location_long').val(crd.longitude.toFixed(6));
                    $('#location_lat').val(crd.latitude.toFixed(6));
                    marker.setLatLng([ crd.latitude, crd.longitude ]);
                    map.setView(marker.getLatLng(),map.getZoom());
                }
                function error(err) {
                    console.warn(`ERROR(${err.code}): ${err.message}`);
                }
                @if( old('location_lat', (isset($advertising) ? $advertising->location_lat : null )) == null  )
                    navigator.geolocation.getCurrentPosition(success, error, options);
                @else
                    $('#location_long').val({{ old('location_long', @$advertising->location_long) }});
                    $('#location_lat').val({{ old('location_lat', @$advertising->location_lat) }});
                @endif
                marker.on("drag", function(e) {
                    var marker = e.target;
                    var position = marker.getLatLng();
                    map.panTo(new L.LatLng(position.lat, position.lng));
                    $('#location_long').val(position.lng.toFixed(6));
                    $('#location_lat').val(position.lat.toFixed(6));
                });
            };
</script>
@endif

@if (!str_contains(request()->path(), 'required_for_rent'))
<!-- include FilePond library -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<!-- include FilePond plugins -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

<script>
    // First register any plugins
            $.fn.filepond.registerPlugin(FilePondPluginImagePreview);

            // Single image seletors
            $('.my-pond').filepond({
                credits: {
                    label: '',
                    url: ''
                },

                labelIdle: "{{ __('add_photos')  }} ({{ __('optional')  }})"
            });
            // Set allowMultiple property to true
            $('.my-pond').filepond('allowMultiple', true);
            $('.my-pond').filepond('storeAsFile', true);

            @php
                $other_images = (array)json_decode(old( 'replace_other_image', @$advertising->other_image) , true);
            @endphp

            @if (@$other_images && count($other_images))
            @foreach($other_images as $i1 => $files )
            @foreach((array)$files as $i2 => $file )
            @if ($file)
            $('.my-pond').filepond('addFile', "{{ @$file  }}").then(function(file){
                console.log('file added', file);
            });
            @endif
            @endforeach
            @endforeach
            @endif


            @if ( old( 'replace_main_image' , @$advertising->main_image) )
            $('.my-pond').filepond('addFile', "{{ old( 'replace_main_image' , @$advertising->main_image)  }}").then(function(file){
                console.log('file added', file);
            });
            @endif

</script>
@endif

@endsection
