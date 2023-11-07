<Search ref="search"
    :trans="{to: '{{__('search_to')}}', kuwait: '{{__('search_kuwait')}}', in: '{{__('search_in')}}', ad: '{{__('search_ad')}}', search_and_more: '{{__('search_and_more')}}', and: '{{__('and')}}'}"
    lang="{{app()->getLocale()}}"
    :purpose_lang="{rent: '{{ __('rent') }}' ,sell: '{{ __('sell') }}' ,exchange: '{{ __('exchange') }}' ,required_for_rent: '{{ __('required_for_rent') }}', all: '{{__('all')}}' }"
    :areas_count="{{env('SEARCH_AREAS', 2)}}" inline-template>
    <div>

        @if (request()->is(app()->getLocale().'/required'))
        <a href="{{route('site.advertising.createRFR', app()->getLocale())}}"
            class="mdc-button mdc-button--raised mt-4  w-100 d-sm-block d-md-none "
            style="color: white; background: #ff7e22">
            <span class="mdc-button__ripple"></span>
            <span class="mdc-button__label">{{ __('request_a_property') }}</span>
            <i class="material-icons mdc-button__icon">add</i>
        </a>
        @endif
        {{-- <div v-if="searchTitle" class="blue-search bg-primary blue-search center-xs p-4">--}}
            {{-- <span v-text="searchTitle" class="mb-3 text-lg fw-600"></span>--}}
            {{-- </div>--}}
        <div v-if="searchTitle" class="mdc-card main-content-header mb-3 my-search-box">
            <div v-if="searchTitle" class="blue-search blue-search center-xs primary-color">
                <span v-text="searchTitle" class="mb-3 text-lg fw-600"></span>
            </div>
        </div>

        <div class="mdc-card main-content-header mb-3 my-search-box">
            <form action="javascript:void(0);" id="filters" class="search-wrapper">
                <div class="row justify-content-center">
                    @if( ! isset($required_for_rent) )
                    <div class="col-xs-12 col-md-3 col-lg-4 p-2 d-flex align-items-center" id="select_areas">

                        <div id="select_header" class="d-lg-none d-md-none d-sm-none d-none">
                            <div class="d-flex align-items-center px-2 py-1" id="select_header_items">
                                <span class="material-icons-outlined">{{$side == 'l' ? 'arrow_forward_ios' :
                                    'arrow_back_ios'}}</span>
                            </div>
                        </div>
                        <multiselect ref="select_area" @open="selectOpened" @close="closeSelect" v-model="areas"
                            :options="options" @select="onSelect" placeholder="{{__('areas_filter')}}"
                            selected-label="{{__('selected')}}" deselect-group-label="{{__('deselect_areas')}}"
                            select-group-label="{{__('select_all_areas')}}" select-label=""
                            deselect-label="{{__('deselect')}}" :multiple="true" group-values="areas"
                            group-label="name_{{ app()->getLocale() }}" :group-select="true" track-by="id"
                            label="name_{{ app()->getLocale() }}" maxHeight="300">
                            <template slot="singleLabel" slot-scope="props">
                                <span class="option__desc">
                                    <span class="option__title">
                                        @{{ props.option['name_'+lang] }}
                                    </span>
                                    <span>
                                        (@{{ props.option['advertising_count'] }})
                                    </span>
                                </span>
                            </template>
                            <template slot="option" slot-scope="props">
                                <div class="option__desc" style="display: flex; justify-content: space-between">
                                    <span class="option__title">
                                        @{{ props.option['name_'+lang] }}
                                    </span>
                                    <span>
                                        (@{{ props.option['advertising_count'] }})
                                    </span>
                                </div>
                            </template>
                            <span slot="noResult">{{__('no_result')}}</span>
                        </multiselect>
                    </div>
                    @endif
                    <div class="col-xs-12 col-md-3 col-lg-4 p-2 d-flex align-items-center erfheight">
                        <multiselect v-model="venue_type" :options="venue_types" placeholder="{{__('venue_filter')}}"
                            selected-label="{{__('selected')}}" select-label="" deselect-label="{{__('deselect')}}"
                            track-by="id" label="title_{{ app()->getLocale() }}" :searchable="false"><span
                                slot="noResult">{{__('no_result')}}</span></multiselect>
                    </div>


                    <!--<div class="col-xs-12 col-sm-6 col-md-2 p-2 d-flex align-items-center erfheight">-->
                    <!--    <div class="mdc-text-field mdc-text-field--outlined">-->
                    <!--            <input class="mdc-text-field__input" type="number" name="price_from" placeholder="{{__('price_from')}}"-->
                    <!--                value="" >-->
                    <!--            <div class="mdc-notched-outline mdc-notched-outline--upgraded">-->
                    <!--                <div class="mdc-notched-outline__leading"></div>-->
                    <!--                <div class="mdc-notched-outline__notch">-->
                    <!--                    <label class="mdc-floating-label"-->
                    <!--                        style="">{{__('price_from')}}</label>-->
                    <!--                </div>-->
                    <!--                <div class="mdc-notched-outline__trailing"></div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--<div class="col-xs-12 col-sm-6  col-md-2 p-2 d-flex align-items-center erfheight">-->
                    <!--     <div class="mdc-text-field mdc-text-field--outlined">-->
                    <!--            <input class="mdc-text-field__input" type="number"  name="price_to" placeholder="{{__('price_to')}}"-->
                    <!--                value="" >-->
                    <!--            <div class="mdc-notched-outline mdc-notched-outline--upgraded">-->
                    <!--                <div class="mdc-notched-outline__leading"></div>-->
                    <!--                <div class="mdc-notched-outline__notch">-->
                    <!--                    <label class="mdc-floating-label"-->
                    <!--                        style="">{{__('price_to')}}</label>-->
                    <!--                </div>-->
                    <!--                <div class="mdc-notched-outline__trailing"></div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--</div>-->

                    @if( ! isset($required_for_rent) )
                    <div class="col-xs-12 col-md-4 mb-2 p-0 d-flex justify-evenly radios-padding">
                        <div class="mdc-form-field">
                            <div class="mdc-radio">
                                <input class="mdc-radio__native-control" type="radio" v-model="purpose" id="rent"
                                    name="purpose" value="rent">
                                <div class="mdc-radio__background">
                                    <div class="mdc-radio__outer-circle"></div>
                                    <div class="mdc-radio__inner-circle"></div>
                                </div>
                            </div>
                            <label class="p-0-important" style="padding: 0 !important;"
                                for="rent">{{__('rent')}}</label>
                        </div>
                        <div class="mdc-form-field">
                            <div class="mdc-radio">
                                <input class="mdc-radio__native-control" type="radio" v-model="purpose" id="sell"
                                    name="purpose" value="sell" {{ request()->purpose === 'sell' ? 'checked' : '' }}>
                                <div class="mdc-radio__background">
                                    <div class="mdc-radio__outer-circle"></div>
                                    <div class="mdc-radio__inner-circle"></div>
                                </div>
                            </div>
                            <label class="p-0-important" style="padding: 0 !important;"
                                for="sell">{{__('sell')}}</label>
                        </div>
                        <div class="mdc-form-field">
                            <div class="mdc-radio">
                                <input class="mdc-radio__native-control" type="radio" v-model="purpose" id="exchange"
                                    id="exchange" name="purpose" value="exchange">
                                <div class="mdc-radio__background">
                                    <div class="mdc-radio__outer-circle"></div>
                                    <div class="mdc-radio__inner-circle"></div>
                                </div>
                            </div>
                            <label class="p-0-important" style="padding: 0 !important;"
                                for="exchange">{{__('exchange')}}</label>
                        </div>
                    </div>
                    @endif
                </div>
                <span class="row center-xs middle-xs p-2 col-md-12 d-md-flex d-lg-flex w-100">
                    <button @click="search(true)" class="mdc-button mdc-button--raised w-100 bg-blue-forced"
                        type="submit">
                        <span class="mdc-button__ripple"></span>
                        <i class="material-icons mdc-button__icon">search</i>
                        <span class="mdc-button__label">{{__('search')}}</span>
                    </button>
                </span>


            </form>
        </div>

        <div class="modal-container">

            <div class="md-modal" id="modal">

                <div class="md-content">
                    <div class="row justify-content-center">
                        <div
                            class="col-xs-12 col-sm-12 col-md-12 p-2 d-flex align-items-center erfheight text-navy-blue-forced ">
                            <h4 class="text-navy-blue-forced ">{{ ucfirst(__('price')) }}</h4>

                            <a href="#" class="decoration-none float-right text-lg  py-1 "
                                style="margin: 0 0 0 auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000"
                                    class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path
                                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                </svg>
                            </a>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 p-2 d-flex align-items-center erfheight">
                            <input class="input" type="number" min="0" placeholder="{{__('from')}}"
                                v-model.lazy="price_from">
                        </div>
                        <div class="col-xs-12 col-sm-6  col-md-6 p-2 d-flex align-items-center erfheight">
                            <input class="input" type="number" min="0" placeholder="{{__('to')}}"
                                v-model.lazy="price_to">
                            {{-- <multiselect :max-height="600" open-direction="bottom" v-model="price_to"
                                :options="price_tos" placeholder="{{__('price_to')}}"
                                selected-label="{{__('selected')}}" select-label="" deselect-label="{{__('deselect')}}"
                                track-by="id" label="title_{{ app()->getLocale() }}" :searchable="true"><span
                                    slot="noResult">{{__('no_result')}}</span></multiselect> --}}
                        </div>
                    </div>
                    <div>
                        <a class="mdc-button mdc-button--raised w-100 bg-blue-forced price-search-modal-close-btn"
                            href="#" @click="search(true)">{{ __('search') }}</a>
                    </div>
                </div>
            </div>
            <div class="md-overlay" onclick="document.querySelector('.price-search-modal-close-btn').click()"></div>
        </div>
    </div>
</Search>
