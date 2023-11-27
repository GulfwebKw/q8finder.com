@php
$desc = @app()->view->getSections()['meta_description'] ? strip_tags(app()->view->getSections()['meta_description']) :
\App\Http\Controllers\site\MessageController::getSettingDetails('meta_description_' . app()->getLocale());
$keywords = @app()->view->getSections()['meta_keywords'] ? strip_tags(app()->view->getSections()['meta_keywords']) :
\App\Http\Controllers\site\MessageController::getSettingDetails('keywords_' . app()->getLocale());
@endphp

<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}" {!! app()->getLocale() === 'ar' ? ' dir="rtl"' : '' !!}>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@hasSection('title')@yield('title') | @endif{{ $title }}</title>


	@hasSection('meta')
	@yield('meta_description')
	@else
	<meta name="description" content="{{$desc}}">
	<meta name="keywords" content="{{$keywords}}">
	@endif
    <meta name="author" content="جي سوليوشنز - الكويت">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Scripts -->
	@include('site.layout.css')
	<script>
		window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
	</script>
	@yield('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    @hasSection('disableFloatAdButton')
    @else
        <!-- Add Advertisement -->
        <a href="{{route('site.advertising.create', app()->getLocale())}}" class="add_float"><i class="fa fa-plus fa-2x"></i></a>
    @endif

	@include('site.layout.header')

{{--    <a href="#" class="menu-close">--}}
    <main>
        @hasSection('disableHeaderNavbar')

        @else
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav>
                            <ul>
                                <li @if (Route::currentRouteName() == 'Main.index' and request()->get('type' , false) == 'rent') class="active" @endif>
                                    <a href="{{ route('Main.index', ['locale' => app()->getLocale() , 'type' => 'rent']) }}">{{__('rent')}}</a>
                                </li>
                                <li @if (Route::currentRouteName() == 'Main.index' and request()->get('type' , false) == 'sell') class="active" @endif>
                                    <a href="{{ route('Main.index', ['locale' => app()->getLocale() , 'type' => 'sell']) }}">{{__('sell')}}</a>
                                </li>
                                <li @if (Route::currentRouteName() == 'Main.index' and request()->get('type' , false) == 'exchange') class="active" @endif>
                                    <a href="{{ route('Main.index', ['locale' => app()->getLocale() , 'type' => 'exchange']) }}">{{__('exchange')}}</a>
                                </li>
                                <li style="border: none;" @if (Route::currentRouteName() == 'required_for_rent') class="active" @endif>
                                    <a href="{{ route('required_for_rent', ['locale' => app()->getLocale()]) }}">{{ __('required_for_rent') }}</a>
                                </li>
                                <li style="border: none;" @if (Route::currentRouteName() == 'Main.index' and request()->get('type' , false) == 'commercial') class="active" @endif>
                                    <a href="{{ route('Main.index', ['locale' => app()->getLocale() , 'type' => 'commercial']) }}">{{ __('commercial') }}</a>
                                </li>
                                <div class="clearfix"></div>
                            </ul>
                        </nav>

                    </div>
                </div>
        </section>
        @endif
	    @include('site.sections.fail-flash')

	    @yield('content')
    </main>
{{--    </a>--}}

	@include('site.layout.footer')


	@include('site.layout.js')


	<script>

	</script>

	@yield('finalScripts')
	@yield('js')

</body>

</html>
