@extends('site.layout.master')
@section('title', __('real_state_service'))

@php
$side = app()->getLocale() === 'en' ? 'r' : 'l';
$unSide = app()->getLocale() === 'en' ? 'l' : 'r';
@endphp
@section('content')
<style>
    @media only screen and (max-width: 345px) {
        .infos {
            font-size: 10px;
        }
    }
    .form-select {
        display: block;
        width: 100%;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        -moz-padding-start: calc(0.75rem - 3px);
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>

<main class="content-offset-to-top">
    <div class="theme-container">
        <div class="row text-center">
            <h1 class="fw-600 mx-auto py-3">
                @if ( $current_category )
                    {{ $current_category['title_'.app()->getLocale()] }}
                @else
                    {{__('real_state_service')}} {{__('category')}}
                @endif
            </h1>
        </div>
        <div class="row md:px-5 justify-content-center col-">
            @foreach($categories as $category)
                <div class="px-1 col-xs-6 col-sm-6 col-md-2 card-mobile-tablet">
                    <div class="card card-subscribe card-buy shadow companies-card rounded  p-0 sm:m{{$side}}-2 mb-3"
                         style="height: max-content">
                        <div class="card-body p-3 sm:p-1.5">
                            <div class="row justify-content-center">
                                <div class="col-md-4 col-md-12 pt-3">
                                    <a href="{{route('site.search.service' , [app()->getLocale() , $category->id , $route_city])}}"
                                       class="company-img h-100 mx-auto d-flex justify-content-center align-items-center">
                                        <img style="object-fit: cover;    aspect-ratio: 1/1;"
                                             src="{{ is_file(public_path($category->image)) ? asset($category->image) : asset('asset/assets/images/others/user.jpg') }}"
                                             alt="agent-image"
                                             class="mw-100  w-100 d-block rounded small-only-max-height max-height h-auto">
                                    </a>
                                </div>
                                <div class="col-md-8 col-xs-12 col-md-12 center-xs p-0">
                                        <a href="{{route('site.search.service' , [app()->getLocale() , $category->id , $route_city])}}"
                                            class="my-1 fw-600 text-body links company-name d-flex align-items-center justify-content-center line-height-sm">{{ $category['title_'.app()->getLocale()] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="row">
            <h1 class="fw-600 mx-auto py-3">{{__('real_state_service')}}</h1>
            <div class="fw-600 mx-auto py-3" onclick="$(this).hide();$('#filterCity').show();">
                <button class="mdc-button center d-lg-flex bg-navy-blue-forced"
                        style="color: #fff">
                    <span class="mdc-button__ripple"></span>
                    <span class="mdc-button__label">{{ __('filter_by_city') }}</span>
                </button>
            </div>
            <div class="fw-600 mx-auto py-3" id="filterCity" style="display:none;" >
                <select class="form-select" onchange="window.location.href = '{{route('site.search.service' , [app()->getLocale() , $route_service , ""])}}/'+$(this).val();" >
                    <option value="all" @if( "all" == $route_city) selected @endif>
                        {{ __('all') }}
                    </option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" @if( $city->id == $route_city) selected @endif >
                            {{ $city['name_'.app()->getLocale()] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="theme-container md:px-5" id="app" data-locale="{{app()->getLocale()}}" data-requiredPage="0" data-servicePage="1" @if( (int) $route_service > 0 ) data-categoryService="{{ $route_service }}" @endif @if( (int) $route_city > 0 ) data-cityService="{{  $route_city  }}" @endif>

            <div class="center-xs">
                <h3 v-if="notFound && newAds" class="alert alert-danger text-center mt-2">
                    <strong>{{__('norecord')}}</strong></h3>
                <h3 v-if="notFound && newAds" class="alert text-center mt-2"><strong>{{__('showing_new_ads')}}</strong>
                </h3>
            </div>

            @include('site.sections.card')

                <div class="center-xs" id="pageEnd1">
                    <img v-if="isLoading !== false" src="{{asset('images/main/loading.gif')}}" alt="loading"
                         class="loading">
                    {{-- <h3 v-else-if="noMore" class="mt-2">{{__('no_more_ads')}}</h3>--}}
                    <h3 v-else-if="notFound && !newAds" class="alert alert-danger text-center mt-2">
                        <strong>{{__('norecord')}}</strong></h3>
                    <button  v-if="isLoading === false && noMore === false" v-on:click="pageEnd" class="mdc-button mdc-button--raised bg-blue-forced mdc-ripple-upgraded" style=" justify-content: right !important;">
                        <span class="mdc-button__ripple"></span>
                        <span class="mdc-button__label">{{ __('load_more_ads') }}</span>
                    </button>
                </div>

        </div>
    </div>
</main>


<script>
    if(!sessionStorage.getItem("app-modal")){
        window.location.href = window.location.href.split('#')[0]+ '#app-modal'
        sessionStorage.setItem("app-modal", true);
    }
    window.authUser = @json(auth()->check() ? auth()->user() : (object)[]);
</script>
<script src="{{ mix('js/app.js') }}?v1" defer></script>
@if (Session::has('reported'))
<script>
    alert(`{{ Session::get('reported') }}`)
</script>
@endif

@if (Session::has('blocked'))
<script>
    alert(`{{ Session::get('blocked') }}`)
</script>
@endif

<style>
    .image-box::before {
        content: "";
        position: absolute;
        @if(app()->getLocale()==='en') left @else right @endif : -5px;
        top: 1.27rem;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;

        border- {
                {
                app()->getLocale()==='en'? 'right': 'left'
            }
        }

        : 5px solid var(--badge);
        font-size: .875rem;
    }

    .image-box::after {
        content: "{{__('premium_short')}}";
        position: absolute;
        @if(app()->getLocale()==='en') left @else right @endif : -5px;
        top: 0;
        background: var(--badge);
        border-radius: 2px 2px 0 2px;
        padding: .09rem .6rem;
        color: #fff;
        font-size: .8rem;
    }
</style>

@endsection
