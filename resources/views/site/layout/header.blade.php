<!-- Your content goes here -->
<header>
    <div class="container mob_hide">
        <div class="row gray_bg">
            <div class="col-10 col-lg-10">
                <ul class="top_menu">
                    <li><a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}">{{__('home_title')}}</a></li>
                    <li><a href="{{route('site.advertising.create', app()->getLocale())}}">{{ __('add_listing_title') }}</a></li>
                    @if(auth()->check())
                        <li><a href="{{ route('Main.myAds',app()->getLocale()) }}">{{__('my_ads_title')}}</a></li>
                        <li><a href="{{ route('Main.myAds.archived' , [ app()->getLocale() ]) }}">{{ __('expired_ads') }}</a></li>
                        <li><a href="{{ route('Main.buyPackage',app()->getLocale()) }}">{{__('buy_package_title')}}</a></li>
                    @else
                        <li>
                            <a href="{{ route('login',app()->getLocale()) }}">{{__('login_title')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('register',app()->getLocale()) }}">{{__('sign_up_title')}}</a>
                        </li>
                    @endif
                    <li><a href="{{ route('companies', app()->getLocale()) }}">{{__('companies')}}</a></li>
                    <li><a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}">عقارات الكويت</a></li>
                    @if(auth()->check())
                        <li>
                            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                {{__('Logout')}}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="col-2 col-lg-2 text-left">
                @include('site.sections.socials', ['sidebar' => false])
            </div>
        </div>
    </div>
    <div class="top_line">
        <div class="container">
            <div class="row">
                <div class="col-4 col-lg-6">
                    <div class="desk_hide">
                        <div class="menu">
                            <a href="#" class="slide-menu-open" style="color: #fff;"><i class="fa fa-bars fa-lg" aria-hidden="true"></i></a>
{{--                            <a href="javascript:history.back()"><i class="fa fa-arrow-circle-left fa-lg" aria-hidden="true"></i></a>--}}
                            <div class="side-menu-overlay" style="width: 0px; opacity: 0;"></div>
                            <div class="side-menu-wrapper">
                                <a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}"><img src="{{ asset('assets/img/logo.png') }}" alt="" class="logo_sidemenu"></a>
                                <a href="#" class="menu-close">&times;</a>

                                <ul>
                                    <li><a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}"><i class="fa fa-home fa-lg"></i> {{__('home_title')}}</a></li>
                                    <li><a href="{{route('site.advertising.create', app()->getLocale())}}"><i class="fa fa-plus-circle fa-lg"></i> {{ __('add_listing_title') }}</a></li>
                                    @if(auth()->check())
                                    <li><a href="{{ route('Main.myAds',app()->getLocale()) }}"><i class="fa fa-file-text-o fa-lg"></i> {{__('my_ads_title')}}</a></li>
                                    <li><a href="{{ route('Main.myAds.archived' , [ app()->getLocale() ]) }}"><i class="fa fa-files-o fa-lg"></i> {{ __('expired_ads') }}</a></li>
                                    <li><a href="{{ route('Main.buyPackage',app()->getLocale()) }}"><i class="fa fa-credit-card fa-lg"></i> {{__('buy_package_title')}}</a></li>
                                    @else
                                        <li>
                                            <a href="{{ route('login',app()->getLocale()) }}"><i class="fa fa-sign-in fa-lg"></i> {{__('login_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('register',app()->getLocale()) }}"><i class="fa fa-user-plus fa-lg"></i> {{__('sign_up_title')}}</a>
                                        </li>
                                    @endif
                                    <li><a href="{{ route('companies', app()->getLocale()) }}"><i class="fa fa-briefcase fa-lg"></i> {{__('companies')}}</a></li>
                                    <li><a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}"><i class="fa fa-building-o fa-lg"></i> عقارات الكويت</a></li>
                                    <li><a href="{{ route('Message.create', ['locale' => app()->getLocale()]) }}"><i class="fa fa-envelope-o fa-lg"></i>{{__('contact_title')}}</a></li>
                                    @if(auth()->check())
                                        <li>
                                            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                <i class="fa fa-sign-out fa-lg"></i> {{__('Logout')}}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout',app()->getLocale()) }}" method="POST"
                                                  style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                                <div style="margin:15px;">
                                    @include('site.sections.socials', ['sidebar' => true])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-8 col-lg-12 text-center">
                    <a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}"><img src="{{ asset('assets/img/logo.png') }}" alt="" class="logo"></a>
                </div>
            </div>
        </div>
    </div>
</header>

