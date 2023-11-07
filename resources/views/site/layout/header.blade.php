<!-- Your content goes here -->
<header>
    <div class="top_line">
        <div class="container">
            <div class="row">
                <div class="col-4 col-lg-6">
                    <div class="desk_hide">
                        <div class="menu">
                            <a href="#" class="slide-menu-open" style="color: #fff;"><i class="fa fa-bars fa-lg" aria-hidden="true"></i></a>
                            <a href="javascript:history.back()"><i class="fa fa-arrow-circle-left fa-lg" aria-hidden="true"></i></a>
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
                                    <li>
                                        @include('site.sections.socials', ['sidebar' => true])
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-8 col-lg-6 text-left">
                    <a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}"><img src="{{ asset('assets/img/logo.png') }}" alt="" class="logo"></a>
                </div>
            </div>
        </div>
    </div>
</header>

