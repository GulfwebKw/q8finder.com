<section>
    <style>
        @media all and (max-width: 480px) and (min-width: 320px) {
            .select2-container--open {
                position: fixed;
                width: calc(100% - 2px) !important;
                top: 0;
                left: 0;
                right: 0;
                z-index: 10000;
            }
            .select2-dropdown {
                position: fixed;
                width:  calc(100% - 4px);
                height: calc(100% - 47px);
                /*height: calc(80% - 47px);*/
                top: 47px;
            }
            .select2-container--default .select2-results>.select2-results__options {
                max-height: 100%;
                height: 100% ;
            }
            .select2-container--default .select2-results {
                /*max-height: 100%;*/
                /*height: 100% ;*/
                height: calc(100% - 47px);
                max-height: calc(100% - 47px);
            }
        }
        .select2-container--default .select2-results__option .select2-results__option {
            display: grid;
        }
        .select2-results__option--group li {
            margin-right: 20px ;
        }
    </style>
    <script>
        function formatState (state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<div class="wwwwwww">' +
                '<span style="float: right">' + state.text + '</span>' +
                ( state.element.dataset.numad !== "" ? ('<span style="float: left">(' + state.element.dataset.numad + ')</span>') : '' )  +
                '</div>'
            );
            return $state;
        };
    </script>
    <div class="container">
        <div class="mt-10">
            <div class="row search-form-for-mobile">
                <div class="col-5 col-lg-6 my_search" >
                    <select x-init="
      $($refs.selectField).select2({
  templateResult: formatState,
  placeholder: 'المنطقة'
}).on('change', function (e) {
        selectedCity = Array.from(e.target.options).filter(option => option.selected).map(option => option.value);
      });
	" x-ref="selectField"  class="input select_input multiple-select2" multiple  >
                        <optgroup>
                            <option value="-2" data-numad=""  @click="selectedCityObject = null;">جميع المناطق الكويت</option>
                        </optgroup>
                        <template x-for="city in areas" >
                            <optgroup :label="city.name_{{ app()->getLocale() }}">
                                <template x-for="cityArea in city.areas">
                                    <option
                                        :value="cityArea.id"
                                        x-bind:data-numad="cityArea.advertising_count"
                                            x-text="cityArea.name_{{ app()->getLocale() }}"></option>
                                </template>
                            </optgroup>
                        </template>
                    </select>
                </div>
                <div class="col-3 col-lg-4 my_search">
                    <select class="input select_input" x-model="selectedType">
                        <option value="-2"  @click="selectedTypeObject = null;">{{ __('all') }}</option>
                        <option value="-1"  @click="selectedTypeObject = null;" disabled selected hidden>{{ __('property_type') }}</option>
                        <template x-for="typeItem in types">
                            <option :value="typeItem.id" x-text="typeItem.title_{{ app()->getLocale() }}"></option>
                        </template>
                    </select>
                </div>
                <div class="col-3 col-lg-2 my_search mob_hide">
                    <button class="btn btn_lg" @click="advertise = [];page=1;totalPage=1;totalAdvertise=0;search();"
                            style="padding-right: 0 !important;padding-left: 0 !important;text-align: center;">
                        {{__('search')}}
                    </button>
                </div>
                <div class="col-3 col-lg-2 my_search desk_hide text-center">
                    <button class="btn btn_lg" @click="advertise = [];page=1;totalPage=1;totalAdvertise=0;search();"
                            style="padding: 4px !important;margin-top: 7px !important;width: auto;text-align: center;">
                        {{__('search')}}
                    </button>
                </div>
            </div>

            @if (request()->is(app()->getLocale().'/required') and false)
                <div class="row">
                    <div class="col-12">
                        <a href="{{route('site.advertising.createRFR', app()->getLocale())}}"
                           class="btn btn_lg">{{ __('request_a_property') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

