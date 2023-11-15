@extends('site.layout.master')
@section('content')
    <section class="mt-20">
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-md-3 mob_hide col-lg-3 col-3">
                    <div class="sidebar">
                        <div class="row p-3">
                            <div class="float-right" style="max-width: 50px;">
                                <img src="{{ auth()->check() && is_file(public_path(auth()->user()->image_profile)) ? asset(auth()->user()->image_profile) : asset('asset/assets/images/others/user.jpg') }}" alt="user-image" style="border-radius: 50%;">
                            </div>
                            <div class="float-right">
                                <h5 class="mx-3 mt-3">@if(auth()->user()->name){{ auth()->user()->name }}@else<a href="{{ route('Main.profile',app()->getLocale()) }}">{{ __('update_name') }}</a>@endif</h5>
                            </div>
                        </div>
                        <hr class="m-0">
                        <ul style="margin-top: 10px !important;margin-right: 0 !important;    list-style-type: none;">
                            <li>
                                <a href="{{ route('Main.buyPackage',app()->getLocale()) }}" class="mdc-list-item" role="menuitem" style="@if(collect(request()->segments())->last() == "buypackage") background-color: var(--mdc-theme-primary); color: white; @endif">
                                    {{__('buy_package_title')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('Main.myAds',app()->getLocale()) }}" class="mdc-list-item" role="menuitem" style="@if(collect(request()->segments())->last() == "myads") background-color: var(--mdc-theme-primary); color: white; @endif">
                                    {{__('my_ads_title')}}
                                </a>
                            </li>
                            @if(env('PACKAGE_HISTORY', false))
                                <li>
                                    <a href="{{ route('Main.paymentHistory',app()->getLocale()) }}" class="mdc-list-item" role="menuitem" style="@if(collect(request()->segments())->last() == "paymenthistory") background-color: var(--mdc-theme-primary); color: white; @endif">
                                        {{__('package_history_title')}}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('Main.profile',app()->getLocale()) }}" class="mdc-list-item" role="menuitem" style="@if(collect(request()->segments())->last() == "profile") background-color: var(--mdc-theme-primary); color: white; @endif">
                                    {{__('edit_profile_title')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('Main.changePassword',app()->getLocale()) }}" class="mdc-list-item" role="menuitem" style="@if(collect(request()->segments())->last() == "changepassword") background-color: var(--mdc-theme-primary); color: white; @endif">
                                    {{__('change_password_title')}}
                                </a>
                            </li>
                            <li>
                                <a href="#" class="mdc-list-item" role="menuitem" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{__('Logout')}}
                                </a>
                                <form id="logout-form" action="{{ route('logout',app()->getLocale()) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 col-lg-9">
                    @yield('panel-content')
                    @hasSection('pagination')
                        @yield('pagination')
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
