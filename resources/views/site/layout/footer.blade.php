@if( ! isset($hideHeaderAndFooter) or  ( isset($hideHeaderAndFooter) and !$hideHeaderAndFooter) )
    <footer class="mt-4" style="margin-top: 105px !important;background: #fafafa;overflow: initial;">
        <div class="px-3">
            <div class="theme-container">
                <div class="content">
                    <div class="row between-xs">
                        <div class="col-xs-12 col-md-6 text-center mb-5">
                            <div class="mt-4 mb-3">
                                <strong class="primary-color " style="font-size: 1.5rem;">{{ trans('footer_download') }}</strong>
                            </div>
                            @if(\App\Http\Controllers\site\MessageController::getSettingDetails('ios_link') )
                                <a href="{{ \App\Http\Controllers\site\MessageController::getSettingDetails('ios_link')  }}" target="_blank" style="display: inline-block;    color: inherit;">
                                    <img src="{{ asset('asset/images/App-Store.png') }}">
                                </a>
                            @endif
                            @if(\App\Http\Controllers\site\MessageController::getSettingDetails('android_link') )
                                <a href="{{ \App\Http\Controllers\site\MessageController::getSettingDetails('android_link')  }}" target="_blank" style="display: inline-block;    color: inherit;">
                                    <img src="{{ asset('asset/images/Google-Play-Store.png') }}" >
                                </a>
                            @endif
                            @if(\App\Http\Controllers\site\MessageController::getSettingDetails('android_huawei_link') )
                                <a href="{{ \App\Http\Controllers\site\MessageController::getSettingDetails('android_huawei_link')  }}" target="_blank" style="display: inline-block;    color: inherit;">
                                    <img src="{{ asset('asset/images/Huawei-Store.png') }}" >
                                </a>
                            @endif
                        </div>
                        <div class="col-xs-12 col-md-6 sm:mx-auto d-mobile-none d-flex align-items-end" style="margin-top: -85px;">
                            <a href="{{ route('Main.index', ['locale' => app()->getLocale()]) }}" class="logo sm:mx-auto" style="display: contents;">
                                <img src="{{ asset('asset/images/app-footer.png') }}" id="logo_footer" class="mx-auto"  alt="image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="favorites-snackbar" class="mdc-snackbar">
        <div class="mdc-snackbar__surface">
            <div class="mdc-snackbar__label">The property has been added to favorites.</div>
            <div class="mdc-snackbar__actions">
                <button type="button" class="mdc-button mdc-snackbar__action">
                    <div class="mdc-button__ripple"></div>
                    <span class="mdc-button__label">
                        <i class="material-icons warn-color">close</i>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div id="back-to-top"><i class="material-icons">arrow_upward</i></div>
@endif
