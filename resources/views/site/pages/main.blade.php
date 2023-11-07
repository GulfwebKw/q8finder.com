@extends('site.layout.master')

@isset($company)
@section('title', $company->company_name . ' | ' . $company->company_phone)
@endisset
@isset($required_for_rent)
@section('title', __('required_for_rent_page_title'))
@endisset

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
</style>

<main>
    @isset($company)
    @include('site.sections.company-info')
    @endisset
    <div class="px-3">
        <div class="theme-container" id="app" @isset($company) data-company="{{$company->id}}" @endisset
            data-requiredPage="{{ isset($required_for_rent) ? '1' : '0' }}" data-locale="{{app()->getLocale()}}">

            @isset($company)
            <h2>{{__('company_ads')}}</h2>
            @endisset

            {{-- <div class="mt-5"></div>--}}

            @if(! isset($company))
            @include('site.sections.search')
            @endif
            <div class="d-flex justify-content-end">
                <a class="price-search-btn" href="#modal">{{ __('price') }} &ensp;<svg xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                    </svg>
                </a>
            </div>
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

<div class="modal-container">

    <div class="md-modal w-auto " id="app-modal" style="top: 50%">
        <a href="#" class="decoration-none float-right text-lg  py-1 "
        style="margin: 0 0 0 auto;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000"
            class="bi bi-x-lg" viewBox="0 0 16 16">
            <path
                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
        </svg>
    </a>
        <div class="md-content" style="border-radius: 14px;">
            <div class="row justify-content-center  flex-column d-flex">
                    <div class="col-12 text-center pb-5">
                        <h1 class="text-black">{{ __("download_app") }}</h1>
                    </div>
                    <div class="col-12 text-center">
                        @if(\App\Http\Controllers\site\MessageController::getSettingDetails('ios_link') )
                            <a href="{{ \App\Http\Controllers\site\MessageController::getSettingDetails('ios_link')  }}" target="_blank" style="display: inline-block;    color: inherit;">
                                <img src="{{ asset('asset/images/App-Store-Button-300x98.png') }}" style="width: 185px;height: 62px;">
                            </a>
                        @endif
                    </div>
                    <div class="col-12 text-center">
                        @if(\App\Http\Controllers\site\MessageController::getSettingDetails('android_link') )
                            <a href="{{ \App\Http\Controllers\site\MessageController::getSettingDetails('android_link')  }}" target="_blank" style="display: inline-block;    color: inherit;">
                                <img src="{{ asset('asset/images/Google-Play-Store-Button-300x98.png') }}" style="width: 185px;height: 62px;">
                            </a>
                        @endif
                    </div>
            </div>
        </div>
    </div>
    <div class="md-overlay" onclick="document.querySelector('.price-search-modal-close-btn').click()"></div>
</div>

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
