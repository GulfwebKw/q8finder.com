@extends('site.layout.master')
@section('title' , __('report'))
@section('disableHeaderNavbar' , 'yes')
@section('content')
    <section>
        <div class="container">
            <div class="row">
                <!-- Gallery -->
                <div class="col-lg-3 mob_hide">&nbsp;</div>

                <div class="col-12 col-lg-6">
                    <div class="seach_container">
                        <h3 class="text-center" dir="rtl">{{ @$confirmMsg }}؟</h3>
                        @if((session('status')) == 'success')
                            <div class="alert alert-success">
                                <strong>{{ __('success_title') }}!</strong> {{ __('profile_success') }}
                            </div>
                        @elseif((session('status')) == 'unsuccess')
                            <div class="alert alert-danger">
                                <strong>{{ __('un_success_title') }}!</strong> {{ __('contact_unsuccess') }}
                            </div>
                        @endif
                        <form action="{{ $action }}" method="{{ @$method ?? 'post' }}">
                            @csrf
                            <div class="form-check">
                                <input type="radio" name="reason" id="offensive" style="display: inline-block;width: auto;">
                                <label for="offensive" style="display: inline-block;">{{ __('Offensive_content') }}</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="reason" id="fake" style="display: inline-block;width: auto;">
                                <label for="fake" style="display: inline-block;">{{ __('fake') .' '.( @$type == 'ad' ? __("advertising_title") : __('user')) }}</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="reason" id="suspicious" style="display: inline-block;width: auto;">
                                <label for="suspicious" style="display: inline-block;">{{ __('suspicious_or_spam') }}</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="reason" id="other" style="display: inline-block;width: auto;">
                                <label for="other" style="display: inline-block;">{{ __('other') }}</label>
                            </div>
                            <div class="form-check">
                                <textarea name="description" rows="5"
                                          placeholder="{{__('what_is_wrong')}}"></textarea>
                            </div>
                            <button type="submit" class="text-center mt-3 w-100 btn btn_lg btn-danger"
                                          style="margin-top:30px;">{{__('confirm')}}</button>
                        </form>
                        <a href="{{  url()->previous() }}" class="text-center mt-3 w-100 btn btn_lg btn-info"
                           style="margin-top:30px;">{{__('cancel')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
