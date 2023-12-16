@extends('site.layout.master')
@section('disableHeaderNavbar' , 'yes')

@php
    function uniord($u) {
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }
    function is_arabic($str) {
        if(mb_detect_encoding($str) !== 'UTF-8') {
            $str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
        }
        preg_match_all('/.|\n/u', $str, $matches);
        $chars = $matches[0];
        $arabic_count = 0;
        $latin_count = 0;
        $total_count = 0;
        foreach($chars as $char) {
            $pos = uniord($char);
            if($pos >= 1536 && $pos <= 1791) $arabic_count++; else if($pos> 123 && $pos < 123)
                $latin_count++;
            $total_count++;
        }
        if(($arabic_count/$total_count)> 0.6)
            return true;
        return false;
    }
    $side = app()->getLocale() === 'en' ? 'r' : 'l';
    $unSide = app()->getLocale() === 'en' ? 'l' : 'r';
    if ( $advertising->purpose != "service")
        $name = __($advertising->purpose) . ' ' . (app()->getLocale() == 'en' ? $advertising->venue->title_en :
            $advertising->venue->title_ar) . ' ' . __('in') . ' ' . (app()->getLocale() == 'en' ? $advertising->area->name_en :
            $advertising->area->name_ar);
    else
        $name = $advertising['title_'.app()->getLocale()];
    $tel = \Illuminate\Support\Str::startsWith($advertising->phone_number, '+') ? $advertising->phone_number :
        "+{$advertising->phone_number}";
    $tel = \Illuminate\Support\Str::startsWith($tel, '+965') ? $tel : str_replace('+', '+965', $tel);
    $tel = str_replace(' ', '', $tel);
    $indexImage = 0 ;
@endphp

@section('title' , $name)

@section('meta')
    {{--
    <meta name="description" content="{{Str::limit($advertising->description, 300, '...')}}">--}}
    <meta name="description" content="{{$advertising->description}}">
@endsection

@section('content')

<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <span class="arrow" onclick="rightArrowClicked()">&rarr;</span>
  <p class="modal-content"><img  id="img01">
  </p>
  <span class="left-arrow" onclick="leftArrowClicked()">&larr;</span>
  <div id="caption"></div>
</div>


    <section class="mt-20">
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                @if ($advertising->purpose !== 'required_for_rent1')
                <div class="col-12 col-lg-7">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            @if($advertising->video)
                                @php
                                    $indexImage++;
                                @endphp
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            @endif
                            @foreach((array) optional(json_decode($advertising->other_image))->other_image as
                            $other_image)
                                @php
                                    $indexImage++;
                                @endphp
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $indexImage }}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img  alt="slide image" src="{{$advertising->main_image ? asset($advertising->main_image) : route('image.noimagebig', '') }}"
                                     onerror="this.onerror=null;this.src='{{route('image.noimage', '')}}';"
                                     class="d-block w-100 h-300">
                            </div>
                            @if($advertising->video)
                                <div class="carousel-item">
                                    <video class="d-block w-100" controls>
                                        <source src="{{asset($advertising->video)}}" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                            @foreach((array) optional(json_decode($advertising->other_image))->other_image as
                            $other_image)
                                <div class="carousel-item">
                                    <img  alt="slide image" src="{{  $other_image ? asset($other_image) : route('image.noimage', '')}}"  class="d-block w-100 h-300" onerror="this.onerror=null;this.src='{{route('image.noimage', '')}}';" >
                                </div>
                            @endforeach
                        </div>
                        @if($indexImage > 0)
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                        @endif
                    </div>
                </div>
                @else
                    <div class="col-12 col-lg-7">
                        <h6>{{ __('Description') }}</h6>
                        <hr>
                        <div dir="{{$advertising->description && ! empty($advertising->description) ? (is_arabic($advertising->description) ? 'rtl' : 'ltr') : ''}}">
                            {!! nl2br(e($advertising->description))!!}


                            @if ( $advertising->location_lat and $advertising->location_long)
                                <p class="">
                                <div id="map" style="width: 100%;height: 250px;border-radius: 5px;"></div>
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
                <!-- Price -->
                <div class="col-12 col-lg-5">
                    <div class="details_con">
                        <h6 class="text-center text-dark">{{$name}}
                            @if( $advertising->area ) - {{app()->getLocale() == 'en' ?
                                    $advertising->area->name_en : $advertising->area->name_ar}} @endif</h6>
                        @if($advertising->price || $advertising->price == 0)
                            <h5 class="text-center text-dark"><strong> {{number_format($advertising->price , env('NUMFORMAT' , 0 ))}} {{__('kd_title')}}</strong></h5>
                        @endif
                    </div>

                    @if($isPhoneVisible)
                    <p class="text-center mt-20" dir="rtl"><i class="fa fa-calendar fa-lg"></i> {{$advertising->created_at}}  <i class="fa fa-eye fa-lg"></i> {{$advertising->view_count}} <a href="javascript:void(0);" id="shareBtn"  style="cursor: pointer"><i class="fa fa-share-square-o fa-lg"></i></span></p>
                    <hr>
                    <p>
                    <button class="phone incrementClick" data-href="tel:{{$tel}}"><i class="fa fa-phone fa-lg"></i> {{str_replace('+965', '', $tel)}}</button>
                    <button class="whatsapp incrementClick" data-href="http://wa.me/{{str_replace('+', '', $tel)}}"><i class="fa fa-whatsapp fa-2x"></i></button>
                    </p>
                     <div class="clearfix"></div>
<br>
                    <p>
                     <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{$name}} {{url('/ar/advertising/'.$advertising->hash_number.'/details')}}" style="color:#999999;width:30px;"><i class="fa fa-facebook fa-2x"></i></a>
                     <a target="_blank" href="https://twitter.com/intent/tweet?text={{$name}} {{url('/ar/advertising/'.$advertising->hash_number.'/details')}}" style="color:#999999;width:30px;"><i class="fa fa-twitter fa-2x"></i></a>
                     <a target="_blank" href="https://pinterest.com/pin/create/button/?url={{url('/ar/advertising/'.$advertising->hash_number.'/details')}}&media={{url($advertising->main_image)}}" style="color:#999999;width:30px;"><i class="fa fa-pinterest fa-2x"></i></a>

                    </p>
                    <!--modal -->
                    <div class="modal fade" id="sharemodale" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sharemodaleLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <div class="modal-body">
                        <p>
                            <div class="row">
                                <div class="col-3"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{$name}} {{url('/ar/advertising/'.$advertising->hash_number.'/details')}}" style="color:#999999;"><i class="fa fa-facebook fa-2x"></i></a></div>
                                <div class="col-3"><a target="_blank" href="https://twitter.com/intent/tweet?text={{$name}} {{url('/ar/advertising/'.$advertising->hash_number.'/details')}}" style="color:#999999;"><i class="fa fa-twitter fa-2x"></i></a></div>
                                <div class="col-3"><a target="_blank" href="https://pinterest.com/pin/create/button/?url={{url('/ar/advertising/'.$advertising->hash_number.'/details')}}&media={{url($advertising->main_image)}}" style="color:#999999;"><i class="fa fa-pinterest fa-2x"></i></a></div>
                            </div>
                            </p>
                        </div>

                        </div>
                    </div>
                    </div>
                    <!--end modal -->
                    <div class="clearfix"></div>
                    <hr>
                    @endif
                    @if($advertising->user->isCompany and false)
                        <a href="{{route('companies.info', [app()->getLocale(),$advertising->user->company_phone,$advertising->user->company_name])}}"
                           class="btn btn_lg">
                            {{__('company_details')}}
                        </a>
                        <div class="clearfix"></div>
                        <hr>
                    @endif
                    @if ( false and auth()->user() != null and auth()->user()->id == $advertising->user_id and $advertising->expire_at and $advertising->purpose !== 'service' )
                        <div class="alert alert-danger">
                            <div>{{ __('renewDescription') }}</div>
                            <div>
                                <form action="{{ route('advertising.repost' , app()->getLocale()) }}" method="POST">
                                    @csrf()
                                    <input type="hidden" name="advertising_type" value="{{$advertising->advertising_type}}">
                                    <input type="hidden" name="phone_number" value="{{$advertising->phone_number}}">
                                    <input type="hidden" name="city_id" value="{{$advertising->city_id}}">
                                    <input type="hidden" name="area_id" value="{{$advertising->area_id}}">
                                    <input type="hidden" name="venue_type" value="{{$advertising->venue_type}}">
                                    <input type="hidden" name="purpose" value="{{$advertising->purpose}}">
                                    <input type="hidden" name="location_lat" value="{{$advertising->location_lat}}">
                                    <input type="hidden" name="location_long" value="{{$advertising->location_long}}">
                                    <input type="hidden" name="price" value="{{$advertising->price}}">
                                    <input type="hidden" name="description" value="{{$advertising->description}}">
                                    <input type="hidden" name="video" value="{{$advertising->video}}">
                                    <input type="hidden" name="replace_other_image" value="{{$advertising->other_image}}">
                                    <input type="hidden" name="replace_main_image" value="{{$advertising->main_image}}">
                                    <button type="submit" class="btn btn_lg">
                                        ({{ __('renew') }})
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                    @endif

                    <div>
                        <span class="f-left" style="line-height: 50px;"><a href="/{{ app()->getLocale() }}/confirm-report/ad/{{ $advertising->id }}">{{__('report').' '.__('advertising_title')}} <i class="fa fa-flag fa-lg"></i></a></span>
                        <span class="back_btn"><a href="javascript:history.back()"><img src="{{ asset('assets/img/back.png') }}" alt=""></a></span>
                    </div>
                </div>
                @if ($advertising->purpose !== 'required_for_rent1')
                <div class="col-12 col-lg-12 mt-30 mb-50">
                    <h6>{{ __('Description') }}</h6>
                    <hr>
                    <div dir="{{$advertising->description && ! empty($advertising->description) ? (is_arabic($advertising->description) ? 'rtl' : 'ltr') : ''}}">
                        {!! nl2br(e($advertising->description))!!}


                        @if ( $advertising->location_lat and $advertising->location_long)
                            <p class="">
                            <div id="map" style="width: 100%;height: 250px;border-radius: 5px;"></div>
                            </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if ( $advertising->location_lat and $advertising->location_long)
        <script>
            window.onload = function() {
                var map = L.map('map').setView([{{ $advertising->location_lat }}, {{ $advertising->location_long }}], 12.5);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(map);
                var marker = L.marker([{{ $advertising->location_lat }}, {{ $advertising->location_long }}]).addTo(map);
            };
        </script>
    @endif


<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  {{--
  <script type="text/javascript">
        document.querySelector('#share').addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: '{{$name}}',
                    text: `{{ \Illuminate\Support\Str::limit($advertising->description, 100, $end='...')}}`,
                    url: '{{url()->current()}}'
                })
                    .then(() => console.log('Share complete'))
                    .error((error) => console.error('Could not share at this time', error))
            } else {
                console.log('Share not supported on this browser, do it the old way.');
                window.open('https://web.whatsapp.com/send?text='+encodeURIComponent('{{url()->current()}}')+'&title='+encodeURIComponent('{{$name}}'));
            }
        });
    </script>
    --}}
    <script type="module">
        $('.incrementClick').click(function(event) {
            event.preventDefault();

            let payload = {
                advertising_id: {{$advertising->id}},
                user_id: {{optional(auth())->user ?? 'null'}}
            }
            $.post("/api/v1/user/logVisitAdvertising", payload, function(data) {
                $(".result").html( data )
            }).done(() => {
                location.href = $(event.target.closest('button')).data('href');
            });
        });

        $("#sharemodalex").click(function(){
        $("#sharemodale").modal('show');
        });

    </script>


    <script>
		document.querySelector('#shareBtn')
		.addEventListener('click', event => {

			// Fallback, Tries to use API only
			// if navigator.share function is
			// available
			if (navigator.share) {
				navigator.share({

					// Title that occurs over
					// web share dialog
					title: 'Q8-Finder',
                   // text:'Q8finder details',
					// URL to share
					url: '{{url("/")}}'
				}).then(() => {
					console.log('Thanks for sharing!');
				}).catch(err => {

					// Handle errors, if occurred
					console.log(
					"Error while using Web share API:");
					console.log(err);
				});
			} else {

				// Alerts user if API not available
				alert("Browser doesn't support this API !");
			}
		})
	</script>

@endsection

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>
@endsection
