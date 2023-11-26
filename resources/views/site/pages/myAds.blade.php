@extends('site.layout.panel')
@section('title' , __('my_ads_title'))

@php
$edge = app()->getLocale() == 'en' ? 'left' : 'right';
@endphp

@section('panel-content')
@if((session('status')) == 'success')
<div class="alert alert-success">
    <strong>{{__('success_title')}}!</strong> {{__('ad_delete_success_title')}} !
</div>
@elseif((session('status')) == 'unsuccess')
<div class="alert alert-danger">
    <strong>{{__('un_success_title')}}!</strong> {{__('un_success_alert_title')}} !
</div>
@elseif((session('status')) == 'expire_your_credit')
<div class="alert alert-danger">
    <strong>{{__('un_success_title')}}!</strong> {{__('un_success_alert_title')}} !
</div>
@elseif((session('status')) == 'ad_created')
<div class="alert alert-success">
    <strong>{{__('success_title')}}!</strong> {{__('ad_created_title')}} !
</div>
@elseif((session('status')) == 'upgraded_premium')
<div class="alert alert-success">
    <strong>{{__('success_title')}}!</strong> {{__('upgraded_premium')}} !
</div>
@elseif((session('status')) == 'dont_have_premium_package')
<div class="alert alert-success">
    <strong>{{__('un_success_title')}}!</strong> {{__('dont_have_premium_package')}} !
</div>
@endif
@if((session('status')) == 'package_bought')
<div class="alert alert-success">
    <strong>{{ __('success_title') }}!</strong> {{ __('packageSuccess') }} !
</div>
@endif
@if ( ! env('NORMAL_ADS_FREE' , false) )
<div class="card card-subscribe card-buy companies-card rounded" style="background-color: #06090c70;border-color: #fff;">
    <div class="card-body p-3">
        <div class="row">
            <p class="w-100 text-center text-md fw-600">{{__('balance')}}</p>
            <div class="d-flex justify-content-around w-100 px-3">
                <p class="primary-color fw-600">{{ @$credit['count_normal_advertising'] ?? 0 }} {{__('ads_title')}}</p>
                <p class="primary-color fw-600">{{ @$credit['count_premium_advertising'] ?? 0}} {{__('premium_short')}}</p>

            </div>
            <a class="fw-600 mx-auto text-center" style="color:#25a3d6" href="/{{ app()->getLocale() }}/buypackage">{{
                __('buy_package_title') }}</a>
        </div>
    </div>
</div>
@endif

<div class="d-flex justify-content-between mt-3">
    <h3 class="d-inline-block fw-600 mb-2 uppercase">
        {{ __('my_ads_title') }}
    </h3>
    <div class="description-box-icons flex-container mb-2 md-float-{{app()->getLocale() == 'en' ? 'right' : 'left'}} sm-justify-evenly">
        <a href="{{ route('Main.myAds.archived' , [ app()->getLocale() ]) }}" class="mdc-button">
            <span class="mdc-button__ripple"></span>
            <span class="mdc-button__label">({{ __('expired_ads') }})</span>
        </a>
    </div>
</div>
<div class="table-responsive border-0 w-100 mt-3">
    <table class="table table-striped table-borderless sm:compress" aria-label="Dessert calories">
        <thead>
            <tr class="mdc-data-table__header-row">
                <th class="mdc-data-table__header-cell uppercase sm:px-2 text-center-important"
                    style="padding-{{$edge}}: 0 !important;">{{ __('image') }}</th>
                <th class="mdc-data-table__header-cell uppercase sm:px-2 text-center-important mob_hide">
                    {{__('ADVERTISE_TYPE')}}</th>
                <th class="mdc-data-table__header-cell uppercase sm:px-2 text-center-important">{{ __('location_title') }}
                </th>
                <th class="mdc-data-table__header-cell uppercase sm:px-2 text-center-important display-table-control">{{
                    __('price') }}</th>
                <th class="mdc-data-table__header-cell uppercase sm:px-2 text-center-important display-table-control mob_hide">{{
                    __('action_title') }}</th>
                {{-- <th class="mdc-data-table__header-cell uppercase sm:px-2 text-center-important">{{
                    __('auto_extend_title') }}</th> --}}
            </tr>
        </thead>
        <tbody class="mdc-data-table__content">

            <style>
                @media screen and (max-width: 600px) {
                    .image-box::after {
                        content: "{{__('premium_short')}}";
                        position: absolute;
                        @if(app()->getLocale()==='en') left @else right @endif : 0;
                        top: 0;
                        background: var(--badge);
                        border-radius: 2px 2px 0 2px;
                        padding: .09rem .6rem;
                        color: #fff;
                        font-size: .8rem;
                    }
                }

            </style>
            @foreach($ads as $ad)
            <tr class="mdc-data-table__row" @if($ad->expire_at) style="background-color:rgba(0,0,0,0.1);" @endif>
                <td class="mdc-data-table__cell sm:px-2 text-center-important" style="padding-{{$edge}}: 0 !important;">
                    <a href="{{route('site.ad.detail', [app()->getLocale(), $ad->hash_number])}}">

                        <div class="{{ $ad->advertising_type == "normal" ? "" : 'image-box' }}" style="position: relative; ">
                            <img src="{{ $ad->main_image ? asset($ad->main_image) : route('image.noimage', '')  }}"
                                style="max-width: 70px;" class="d-block my-ads-image aspect-ratio">
                        </div>
                    </a>
                </td>
                <td class="mdc-data-table__cell sm:px-2 text-center-important mob_hide">
                    @if($ad->advertising_type == "premium")
                    {{__('premium_short')}}
                    @elseif($ad->advertising_type == "normal")
                    {{__('normal_title')}}
                    @endif
                </td>
                <td class="mdc-data-table__cell sm:px-2 text-center-important text-xs text-truncate">
                    {{ app()->getLocale()==='en'? optional($ad->city)->name_en . " - " . optional($ad->area)->name_en: optional($ad->city)->name_ar . "
                    - " . optional($ad->area)->name_ar }}

                    <div class="desk_hide">
                        <form id="delete-form-{{$ad->id}}" class="d-inline-block" method="post"
                            action="{{ route('site.advertising.destroy',app()->getLocale()) }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $ad->id }}">

                            @if(! $ad->expire_at)
                                @if ($ad->purpose === 'required_for_rent')
                                    <a href="{{ route('site.advertising.editRFR',[app()->getLocale(),$ad->hash_number]) . ( $ad->purpose == "service" ? '?service=1' : '') }}"
                                       class="btn btn-link text-warning sm:px-2"><i class="fa fa-pencil fa-2x" style="margin: 0;font-size: 1.5em;"></i></a>
                                @else
                                    <a href="{{ route('site.advertising.edit',[app()->getLocale(),$ad->hash_number]). ( $ad->purpose == "service" ? '?service=1' : '')  }}"
                                       class="btn btn-link text-warning sm:px-2"><i class="fa fa-pencil fa-2x" style="margin: 0;font-size: 1.5em;"></i></a>
                                @endif
                            @endif

                            <button type="button" id="delete-btn" onclick="showModal({{ $ad->id }})"
                                class="btn btn-link text-danger sm:px-2"><i class="fa fa-trash fa-2x" style="margin: 0;font-size: 1.5em;"></i>
                            </button>
                        </form>
                        @if ($ad->advertising_type == 'normal')
                        <form action="{{ route('site.advertising.upgrade_premium',app()->getLocale()) }}" method="post"
                            id="upgrade{{$ad->id}}" class="d-none">
                            @csrf
                            <input type="hidden" name="advertise_id" value="{{$ad->id}}">
                        </form>
                        <a type="button" id="delete-btn" class="btn btn-link material-icons d-inline-block"
                           style="color: #c7a014;" onclick="showUpgradeModal('{{$ad->id}}')"><i class="fa fa-star fa-2x" style="margin: 0;font-size: 1.5em;"></i></a>
                        @endif
                    </div>
                </td>
                <td class="text-center">
                    {{ $ad->price }} {{ __('kd_title') }}
                </td>
                <td class="mdc-data-table__cell sm:px-2 text-center-important display-table-control mob_hide">
                    <form id="delete-form-{{$ad->id}}" class="d-inline-block" method="post"
                        action="{{ route('site.advertising.destroy',app()->getLocale()) }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $ad->id }}">

                        @if(!$ad->expire_at )
                        @if ($ad->purpose === 'required_for_rent')
                        <a href="{{ route('site.advertising.editRFR',[app()->getLocale(),$ad->hash_number]) . ( $ad->purpose == "service" ? '?service=1' : '') }}"
                            class="btn btn-info material-icons primary-color sm:px-2"><i class="fa fa-pencil" style="margin: 0;"></i></a>
                        @else
                        <a href="{{ route('site.advertising.edit',[app()->getLocale(),$ad->hash_number]). ( $ad->purpose == "service" ? '?service=1' : '')  }}"
                            class="btn btn-info material-icons primary-color sm:px-2"><i class="fa fa-pencil" style="margin: 0;"></i></a>
                        @endif
                        @endif

                        <button type="button" id="delete-btn" onclick="showModal({{ $ad->id }})"
                            class="btn btn-danger material-icons warn-color sm:px-2"><i class="fa fa-trash" style="margin: 0;"></i>
                        </button>
                    </form>
                    @if ($ad->advertising_type == 'normal')
                    <form action="{{ route('site.advertising.upgrade_premium',app()->getLocale()) }}" method="post"
                        id="upgrade{{$ad->id}}" class="d-none">
                        @csrf
                        <input type="hidden" name="advertise_id" value="{{$ad->id}}">
                    </form>
                    <a type="button" id="delete-btn" class="btn btn-success material-icons d-inline-block"
                       onclick="showUpgradeModal('{{$ad->id}}')"><i class="fa fa-star" style="margin: 0;"></i></a>
                    @endif
                </td>
                {{-- <td class="sm:px-2 text-center-important">
                    @if(! $ad->expire_at)
                    <div class="col-xs-12 py-3 row middle-xs justify-content-center">
                        <div class="mdc-switch">
                            <div class="mdc-switch__track"></div>
                            <div class="mdc-switch__thumb-underlay">
                                <div class="mdc-switch__thumb">
                                    <input type="checkbox" id="extend{{$ad->id}}" class="mdc-switch__native-control" {{
                                        !$ad->auto_extend ?: 'checked'}}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var checkbox = document.getElementById('extend{{$ad->id}}')

                                checkbox.addEventListener('change', (event) => {
                                    if (event.currentTarget.checked) {
                                        $.post('/{{app()->getLocale()}}/advertising/auto_extend', {
                                            id: {{$ad->id}},
                                            extend: 'enable'
                                        }, function (data, status) {
                                            if (status === 'success') {
                                                alert(data)
                                            }
                                        })
                                    } else {
                                        $.post('/{{app()->getLocale()}}/advertising/auto_extend', {
                                            id: {{$ad->id}},
                                            extend: 'disable'
                                        }, function (data, status) {
                                            if (status === 'success') {
                                                alert(data)
                                            }
                                        })
                                    }
                                })
                    </script>
                    @endif
                </td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('confirmation')}}</h5>
            </div>
            <div class="modal-body">
                {{__('ask_delete_title')}} ?!
            </div>
            <div class="modal-footer justify-content-between mt-3">
                <hr>
                <button type="button" class="btn btn-secondary close mt-3">{{__('cancel_title')}}</button>
                <button type="button" class="btn btn-danger mt-3"
                    id="delete">{{__('yes_title')}},{{__('delete_title')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="confirmUpgrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('confirmation')}}</h5>
            </div>
            <div class="modal-body">
                {{__('ask_upgrade_title')}}!
            </div>
            <div class="modal-footer justify-content-between mt-3">
                <hr>
                <button type="button" class="btn btn-secondary close mt-3">{{__('cancel_title')}}</button>
                <button type="button" class="btn btn-danger mt-3" id="upgrade"
                    style="background: green !important; border-color: green !important;">
                    {{__('yes_title')}}
                    ,{{__('upgrade_title')}}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('pagination')
{!! $ads->links('vendor.pagination.housekey') !!}
@endsection
@section('head')
<style>
    .modal {
        color: black !important;
    }
</style>
@endsection
@section('js')
<script>
    // Get the modal
        var modal = document.getElementById("confirmDelete");
        var upgradeModal = document.getElementById("confirmUpgrade");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        var span2 = document.getElementsByClassName("close")[1];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        // When the user clicks on <span> (x), close the modal
        span2.onclick = function() {
            upgradeModal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            else if(event.target == upgradeModal)
            {
                upgradeModal.style.display = "none";
            }
        }
        var advertiseId='';
        function showModal(id){
            modal.style.display = "block";
            advertiseId= id;
        }
        $('#delete').on('click',function () {
            $('#delete-form-'+advertiseId).submit()
        })

        var upgradableId='';
        function showUpgradeModal(id){
            upgradeModal.style.display = "block";
            upgradableId= id;
        }
        $('#upgrade').on('click',function () {
            $('#upgrade'+upgradableId).submit()

        })
</script>
@endsection
