<section>
    <div class="container">
        <div class="mt-10">
            <div class="row">
                <div class="col-5 col-lg-6 my_search mr10" >
                    <select x-init="
      $($refs.selectField).select2().on('change', function (e) {
        selectedCity = Array.from(e.target.options).filter(option => option.selected).map(option => option.value);
      });
	" x-ref="selectField"  class="input select_input multiple-select2" multiple  >
{{--                        <option @click="selectedCityObject = null;">{{__('all')}}</option>--}}
                        <template x-for="city in areas" >
                            <optgroup :label="city.name_{{ app()->getLocale() }}">
                                <template x-for="cityArea in city.areas">
                                    <option :value="cityArea.id" x-text="cityArea.name_{{ app()->getLocale() }}"></option>
                                </template>
                            </optgroup>
                        </template>
                    </select>
                </div>
                <div class="col-3 col-lg-4 my_search">
                    <select class="input select_input" x-model="selectedType">
                        <option @click="selectedTypeObject = null;">{{__('all')}}</option>
                        <template x-for="typeItem in types">
                            <option :value="typeItem.id" x-text="typeItem.title_{{ app()->getLocale() }}"></option>
                        </template>
                    </select>
                </div>
                <div class="col-3 col-lg-2 my_search"><button class="btn btn_lg" @click="advertise = [];page=1;totalPage=1;totalAdvertise=0;search();">{{__('search')}}</button></div>
            </div>
        </div>
    </div>
</section>

