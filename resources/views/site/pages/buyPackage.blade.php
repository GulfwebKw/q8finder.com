@extends('site.layout.master')
@section('title' , __('buy_package_title'))
@section('content')

@if((session('status')) == 'success')
<div class="alert alert-success">
    <strong>{{ __('success_title') }}!</strong> {{__('packageSuccessBuy')}}!
</div>
@elseif((session('status')) == 'validation_failed')
<div class="alert alert-danger">
    <strong>{{__('un_success_title')}}!</strong> {{__('wrongInput')}}!
</div>
@elseif((session('status')) == 'account_upgraded')
<div class="alert alert-success">
    <strong>{{__('success_title')}}!</strong> {{__('upgraded_to_company')}}!
</div>
@elseif((session('status')) == 'ads_remaining')
<div class="alert alert-danger">
    <strong>{{__('un_success_title')}}!</strong> {{__('packageNotFinished')}}!
</div>
@elseif((session('status')) == 'account_downgraded')
<div class="alert alert-success">
    <strong>{{__('success_title')}}!</strong> {{__('account_downgraded_successfully')}} !
</div>
@elseif((session('status')) == 'unsuccess')
<div class="alert alert-danger">
    <strong>{{__('un_success_title')}}!</strong> {{__('un_success_alert_title')}}!
</div>
@elseif((session('status')) == 'have_no_package')
<div class="alert alert-danger">
    {{ __('have_no_package') }}
</div>
@endif
<style>
    :root {
        /*--mdc-theme-secondary: #E91E63 !important; // radio buttons*/
        --mdc-theme-secondary: #085b8f !important; // radio buttons
    }
</style>
<main>
    <div class="px-3">
        <div class="container">
            <div class="my-5">
                @if ( ! env('NORMAL_ADS_FREE' , false) )
                <div class="col-xs-11 col-sm-7 col-md-4 my-1 mx-auto mb-2">
                    <div class="card card-subscribe card-buy companies-card rounded" style="background-color: #06090c70;border-color: #fff;">
                        <div class="card-body p-3">
                            <div class="row">
                                <p class="w-100 text-center text-md fw-600">{{__('balance')}}</p>
                                <div class="d-flex justify-content-around w-100 px-3">
                                    <p class="primary-color fw-600">{{ $credit['count_normal_advertising'] }}
                                        {{__('ads_title')}}</p>
                                    <p class="primary-color fw-600">{{ $credit['count_premium_advertising'] }}
                                        {{__('premium_short')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="column text-center middle-xs text-center">
                    <h1 class="uppercase">{{__('buy_package_title')}}</h1>
                    <p class="text-muted fw-500">{{__('subscribetoourpackagenote')}}</p>
                </div>

                <div class="mdc-tab-bar-wrapper centered pricing-tabs">
                    @if ( ! env('NORMAL_ADS_FREE' , false) )
                        <ul  class="d-flex justify-content-center nav" role="tablist" style="column-gap: 20px;">
                            <li role="presentation" style="cursor: pointer;" class="menuTab active p-3 rounded" onclick="$('.tab-pane').hide();$('#payasyougo').show();$('.menuTab').removeClass('active');$(this).addClass('active');">{{__('payasyougo')}}</li>
                            <li role="presentation" style="cursor: pointer;" class="menuTab p-3 rounded" onclick="$('.tab-pane').hide();$('#longtermsubscribe').show();$('.menuTab').removeClass('active');$(this).addClass('active');">{{__('longtermsubscribe')}}</li>
                        </ul>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane" style="display: block;" id="payasyougo">
                            <div class="row">
                                @foreach($statics as $static)
                                <div class="col-xs-12 col-sm-12 col-md-6 p-2">
                                    <form method="post" action="{{ route('Main.buyPackageOrCredit',app()->getLocale()) }}"
                                        class="d-flex">
                                        <div class="w-100">
                                            @csrf
                                            <div class="mdc-card pricing-card text-center border-accent p-0 h-100" style="border-color: #fff;">
                                                <div class="bg-accent pricing-header px-2 py-4 rounded" style="background-color: #06090c70;">
                                                    <h1 class="fs-sm-20">@if( $static->old_price > $static->price ) <small>
                                                            <span class="del opacity-70">{{ $static->old_price }} </span>
                                                        </small> @endif {{$static->price }}<small> {{__('kd_title')}}
                                                            <br>
                                                            {{-- {{ app()->getLocale()=="en" ? $static->title_en :
                                                            $static->title_ar }} --}}
                                                            {{-- /{{ $static->count_day }} {{__('days')}} --}}</small></h1>
                                                    <p class="desc mb-2">
                                                        @if(app()->getLocale()=="en"){{$static->title_en}}@else{{$static->title_ar}}@endif
                                                    </p>
                                                </div>
                                                <div class="px-2 ad-plan-bottom">
                                                    <p class=" add-plan-description">
                                                        @if(app()->getLocale()=="en"){{$static->description_en}}@else{{$static->description_ar}}@endif
                                                    </p>

                                                    <div>
                                                        <input type="hidden" class="form-control" name="payment_type"
                                                            value="CBKPay">

                                                        <label>{{__('noofads')}}</label>
                                                        <input type="number" dir="ltr" class="input text-left "
                                                               value="1"  name="count" min="1" id="{{ "
                                                                static-num-" . $static->id }}" required
                                                               placeholder="{{__('noofads')}}">
                                                    </div>
                                                    <input type="hidden" name="type" value="static">
                                                    <input type="hidden" name="package_id" value="{{ $static->id }}">

                                                    <div class="w-100 desk_hide">
                                                        <button type="submit" class="btn btn_lg d-block">{{__('buy')}} </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 mob_hide">
                                            <div class="c w-100 d-flex justify-center" style="height: calc(100% - 50px);align-items: center;">
                                                <div class="text-center f-d-column w-100">
                                                    <div class="d-block pb-2">
                                                        <div class="c" style="cursor: pointer;">
                                                            <div onclick='{{ $credit["count_premium_advertising"] }} > 0 ? (location.href = "/"+"{{ app()->getLocale() }}"+"/advertising/create?type=premium&show_ad_type_option=true") : alert("{{ trans("have_no_package") }}")'
                                                                class="pe-auto bg-orange rounded-full ar-eq w-30px c d-inline-block mx-auto">
                                                                <i class="fa fa-plus-circle fa-2x" style="color: #ff9800;margin-left: 0;"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>{{ __('add_credit') }}</p>
                                                </div>
                                            </div>
                                            <div class="w-100 px-2" style="height: 40px">
                                                <button type="submit" class="btn btn_lg d-block"
                                                    style="margin-left: auto;max-width: 100px;">{{__('buy')}}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane" id="longtermsubscribe">
                            <div class="row">
                                @foreach($normals as $normal)
                                <div class="col-xs-12 col-sm-6 col-md-3 p-2">
                                    <div class="mdc-card pricing-card text-center border-accent p-0 h-100">
                                        <div class="bg-accent pricing-header px-2 py-4 rounded" style="background-color: #06090c70;">
                                            <h1 class="fs-sm-20"> @if( $normal->old_price > $normal->price ) <small> <span
                                                        class="del opacity-70">{{ $normal->old_price }} </span></small>
                                                @endif {{ $normal->price }}<small> {{__('kd_title')}}
                                                    <br>
                                                    {{-- {{ $normal->count_day }} {{__('days')}} --}}
                                                </small></h1>
                                            <p class="desc mb-2">
                                                @if(app()->getLocale()=="en"){{$normal->title_en}}@else{{$normal->title_ar}}@endif
                                            </p>
                                        </div>
                                        <div class="p-3">
                                            <p class="py-2">
                                                <!--<span class="mx-2 fw-500">10</span>-->
                                                @if(app()->getLocale()=="en"){{$normal->description_en}}@else{{$normal->description_ar}}@endif
                                            </p>

                                            <form method="post"
                                                action="{{ route('Main.buyPackageOrCredit',app()->getLocale()) }}">
                                                @csrf
                                                <input type="hidden" class="form-control" name="payment_type"
                                                    value="CBKPay">
                                                <input type="hidden" name="type" value="normal">
                                                <input type="hidden" name="package_id" value="{{ $normal->id }}">
                                                <button type="submit"  class="btn btn_lg d-block"
                                                        style="max-width: 100px;">{{__('buy')}}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
