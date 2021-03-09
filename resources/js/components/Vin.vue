<template>
        <div class="row align-items-center justify-content-center ml-auto mr-auto" style="width: 100%">
            <transition name="fade" mode="out-in">
                <div class="ml-auto mr-auto" style="width: 100%" v-if="phone_step&&!name_step&&!vin_step">
                    <div class="col-md-6 col-lg-4 ml-auto mr-auto">
                        <div class="form_group">
                            <input type="text" placeholder="Ваш номер телефона" name="phone"
                                   v-model="phone"
                                   required="required" class="form_control phone" v-mask="'+38 (###) ###-##-##'"
                                   @keyup.enter="checkPhone()">
                            <i class="input-icon fas fa-phone"></i>
                        </div>
                        <div class="row">
                            <button class="chopcafe_btn form_btn ml-auto mr-auto" v-on:click="checkPhone()">Далее</button>
                        </div>
                    </div>
                </div>
            </transition>
            <transition name="fade" mode="out-in">
                <div class="col-md-6 col-lg-4 ml-auto mr-auto" v-if="!phone_step&&name_step&&!vin_step">
                    <div class="form_group">
                        <input type="text" placeholder="Введите имя" name="name" v-model="name"
                               class="form_control lottery-field"
                               @keyup.enter="addName()">
                        <i class="input-icon fas fa-phone"></i>
                    </div>
                    <div class="row">
                        <button class="chopcafe_btn form_btn ml-auto mr-auto" v-on:click="addName()">Далее</button>
                    </div>
                </div>
            </transition>
            <transition name="fade" mode="out-in">
                <tabs v-if="!phone_step&&!name_step&&vin_step" style="width:100%">
                    <tab name="Запрос" :selected="true">
                        <div class="row justify-content-center ml-auto mr-auto" style="width: 100%">

                            <div class="col-md-4 mr-4 ml-auto column">
                                <div class="mb-4">
                                    <label class="control-label" for="vincode">* VIN-код / № кузова</label>
                                    <input id="vincode" name="vincode" v-model="vincode" type="text" placeholder="Введите номер" class="form-control input-md" required>
                                    <span class="help-block">например: VF1BJ0J0B28221999</span>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="year">Год выпуска</label>
                                    <select id="year" name="year" v-model="year" class="form-control" required>
                                        <option v-for="y in years" :value="y">{{y}} год</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="month">Месяц</label>
                                    <select id="month" name="month" v-model="month" class="form-control" required>
                                        <option v-for="m in months">{{m}}</option>
                                        <!-- @foreach($month as $m)
                                            <option value="1">{{$m}}</option>
                                        @endforeach -->
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="category">Категория</label>
                                    <select id="category" name="category" v-model="category" class="form-control" v-on:change="changeCategory()" required>
                                        <option value="-1">не выбрано</option>
                                        <option v-for="c in categories" :value="c">{{c.name}}</option>
                                        <!-- @foreach($rio->getCategories() as $cat)
                                            <option value="{{$cat["value"]}}">{{$cat["name"]}}</option>
                                        @endforeach -->
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="carmake">Марка</label>
                                    <div class="spinner-border spinner-border-sm text-danger" v-if="status=='loading'" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <select id="carmake" name="carmake" v-model="mark" class="form-control" v-on:change="changeMark()" :disabled="category==''||category==-1 || status=='loading'" required>
                                        <option value="-1">не выбрано</option>
                                        <option v-for="ma in marks" :value="ma">{{ma.name}}</option>

                                        <!-- @foreach($rio->getMarks() as $mark)
                                            <option value="{{$mark["value"]}}">{{$mark["name"]}}</option>
                                        @endforeach -->
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="model">Модель</label>
                                    <div class="spinner-border spinner-border-sm text-danger" v-if="status=='loading'" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <select id="model" name="model" v-model="model" class="form-control" required :disabled="category==''||category==-1||mark==''||mark==-1||status=='loading'">
                                        <option value="-1">не выбрано</option>
                                        <option v-for="mod in models" :value="mod">{{mod.name}}</option>
                                        <!-- @foreach($rio->getModels(2,9) as $model)
                                            <option value="{{$model["value"]}}">{{$model["name"]}}</option>
                                        @endforeach -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 ml-4 mr-auto column">

                                <div class="mb-4">
                                    <label class="control-label" for="volume">Объем двигателя, см3</label>
                                    <input id="volume" name="volume" v-model="volume" type="text" placeholder="Объем двигателя авто"
                                           class="form-control input-md">
                                    <span class="help-block">например: 250 см3</span>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="power">Мощность, л.с.</label>
                                    <input id="power" name="power" v-model="power" type="text" placeholder="Мощность двигателя" class="form-control input-md">
                                    <span class="help-block">например: 120 л.с.</span>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="additional_info">Дополнительная информация</label>
                                    <textarea class="form-control" id="additional_info" name="additional_info" v-model="additional_info"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="parts_info">* Какие запчасти Вас интересуют?</label>
                                    <textarea class="form-control" id="parts_info" name="parts_info" v-model="parts_info" required></textarea>
                                </div>

                            </div>

                        </div>
                        <div class="row justify-content-center mt-3 mb-3 ml-auto mr-auto" style="width: 100%">
                            <h3 class="text-center" style="color:white; z-index: 1">Дополнительные параметры</h3>
                        </div>
                        <div class="row justify-content-center ml-auto mr-auto" style="width: 100%">
                            <div class="col-md-4 mr-4 ml-auto column">

                                    <div class="mb-4">
                                        <label class="control-label" for="cylinders">Цилиндры</label>
                                        <input id="cylinders" v-model="cylinders" name="cylinders" type="text" placeholder="" class="form-control input-md">
                                    </div>

                                    <div class="mb-4">
                                        <label class="control-label" for="engine_type">Тип/буквы двигателя</label>
                                        <input id="engine_type" v-model="engine_type" type="text" placeholder="" class="form-control input-md">

                                    </div>

                                    <div class="mb-4">
                                        <label class="control-label" for="valves">Клапанов</label>
                                        <input id="valves" v-model="valves" type="text" placeholder="" class="form-control input-md">

                                    </div>

                                    <div class="mb-4">
                                        <label class="control-label" for="body_type">Тип кузова</label>
                                        <select id="body_type" v-model="body_type" class="form-control" :disabled="category==''||category==-1">
                                            <option value="-1">не выбрано</option>
                                            <option v-for="b in bodyStyles" :value="b">{{b.name}}</option>
                                            <!--                                            @foreach($rio->getBodyStyles() as $body)-->
                                            <!--                                            <option value="{{$body["value"]}}">{{$body["name"]}}</option>-->
                                            <!--                                            @endforeach-->
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="control-label" for="number_of_doors">Число дверей</label>
                                        <select id="number_of_doors" v-model="number_of_doors" class="form-control">
                                            <option value="0">не выбрано</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6>">6 и более</option>
                                        </select>
                                    </div>

                            </div>
                            <div class="col-md-4 ml-4 mr-auto column">
                                <div class="mb-4">
                                    <label class="control-label" for="drive">Привод</label>
                                    <select id="drive" v-model="drive" class="form-control" :disabled="category==''||category==-1">
                                        <option value="-1">не выбрано</option>
                                        <option v-for="d in driverType" :value="d">{{d.name}}</option>
                                        <!--                                            @foreach($rio->getDriverType() as $drive)-->
                                        <!--                                            <option value="{{$drive["value"]}}">{{$drive["name"]}}</option>-->
                                        <!--                                            @endforeach-->
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="control-label" >Руль</label>
                                    <div class="">
                                        <label class="radio-inline" for="steering_wheel-0">
                                            <input type="radio" v-model="steering_wheel" id="steering_wheel-0" value="слева" checked="checked">
                                            слева
                                        </label>
                                        <label class="radio-inline" for="steering_wheel-1">
                                            <input type="radio" v-model="steering_wheel" id="steering_wheel-1" value="справа">
                                            справа
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="control-label">Опции комплектации</label>
                                    <div class="">
                                        <label class="checkbox-inline" for="equipment-0">
                                            <input type="checkbox" v-model="equipment" id="equipment-0" value="ABS">
                                            ABS
                                        </label>
                                        <label class="checkbox-inline" for="equipment-1">
                                            <input type="checkbox" v-model="equipment" id="equipment-1" value="ESP">
                                            ESP
                                        </label>
                                        <label class="checkbox-inline" for="equipment-2">
                                            <input type="checkbox" v-model="equipment" id="equipment-2" value="УР">
                                            УР
                                        </label>
                                        <label class="checkbox-inline" for="equipment-3">
                                            <input type="checkbox" v-model="equipment" id="equipment-3" value="Кондиционер">
                                            Кондиционер
                                        </label>
                                        <label class="checkbox-inline" for="equipment-4">
                                            <input type="checkbox" v-model="equipment" id="equipment-4" value="Катализатор">
                                            Катализатор
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="gearbox_type">Тип кпп</label>
                                    <select id="gearbox_type" v-model="gearbox_type" class="form-control" :disabled="category==''||category==-1">
                                        <option v-for="g in gearboxes" :value="g">{{g.name}}</option>
                                        <!--                                            @foreach($rio->getGearboxes() as $gear)-->
                                        <!--                                            <option value="{{$gear["value"]}}">{{$gear["name"]}}</option>-->
                                        <!--                                            @endforeach-->
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="gearbox_number">Номер кпп</label>
                                    <input id="gearbox_number" v-model="gearbox_number" type="text" placeholder="" class="form-control input-md">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center ml-auto mr-auto" style="width: 100%">
                            <button class="chopcafe_btn form_btn ml-auto mr-auto" v-on:click="sendVinRequest()">Отправить</button>
                        </div>
                    </tab>
                    <tab name="Поиск шин" v-if="vinrequests.length>0" >
                        <div class="row justify-content-center mb-4" style="width: 100%">
                            <div class="col-md-4 ml-auto mr-auto">
                                <label class="control-label" for="tyre_width">Vin-code</label>
                                <select v-model="vinrequest_id" id="vinrequest_id" class="form-control">
                                    <option v-for="vin in vincodes" :value="vin">{{vin.vincode}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row justify-content-center ml-auto mr-auto" style="width: 100%">

                            <div class="col-md-4 mr-4 ml-auto column">

                                <div class="mb-4">
                                    <label class="control-label" for="tyre_width">Ширина покрышки</label>
                                    <select id="tyre_width" v-model="tyre_width" class="form-control">
                                        <option value="-1">не выбрана</option>
                                        <option value="125" >125</option>
                                        <option value="135" >135</option>
                                        <option value="145" >145</option>
                                        <option value="155" >155</option>
                                        <option value="165" >165</option>
                                        <option value="175" >175</option>
                                        <option value="185" >185</option>
                                        <option value="195" >195</option>
                                        <option value="205" >205</option>
                                        <option value="215" >215</option>
                                        <option value="225" >225</option>
                                        <option value="235" >235</option>
                                        <option value="245" >245</option>
                                        <option value="255" >255</option>
                                        <option value="265" >265</option>
                                        <option value="275" >275</option>
                                        <option value="285" >285</option>
                                        <option value="295" >295</option>
                                        <option value="305" >305</option>
                                        <option value="315" >315</option>
                                        <option value="325" >325</option>
                                        <option value="335" >335</option>
                                        <option value="345" >345</option>
                                        <option value="355" >355</option>
                                        <option value="365" >365</option>
                                        <option value="375" >375</option>
                                        <option value="385" >385</option>
                                        <option value="395" >395</option>
                                        <option value="405" >405</option>
                                        <option value="455" >455</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="tyre_height">Высота покрышки</label>
                                    <select id="tyre_height" v-model="tyre_height" class="form-control">
                                        <option value="-1">не выбрана</option>
                                        <option value="25" >25</option>
                                        <option value="30" >30</option>
                                        <option value="35" >35</option>
                                        <option value="40" >40</option>
                                        <option value="45" >45</option>
                                        <option value="50" >50</option>
                                        <option value="55" >55</option>
                                        <option value="60" >60</option>
                                        <option value="65" >65</option>
                                        <option value="70" >70</option>
                                        <option value="75" >75</option>
                                        <option value="80" >80</option>
                                        <option value="85" >85</option>
                                        <option value="90" >90</option>
                                        <option value="95" >95</option>
                                        <option value="105" >105</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="diameter">Диаметр</label>
                                    <select id="diameter" v-model="tyre_diameter" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="12" >12</option>
                                        <option value="13" >13</option>
                                        <option value="14" >14</option>
                                        <option value="15" >15</option>
                                        <option value="16" >16</option>
                                        <option value="16.5" >16.5</option>
                                        <option value="17" >17</option>
                                        <option value="18" >18</option>
                                        <option value="19" >19</option>
                                        <option value="20" >20</option>
                                        <option value="21" >21</option>
                                        <option value="22" >22</option>
                                        <option value="23" >23</option>
                                        <option value="24" >24</option>
                                        <option value="25" >25</option>
                                        <option value="26" >26</option>
                                        <option value="28" >28</option>
                                        <option value="30" >30</option>

                                    </select>
                                </div>

                            </div>

                            <div class="col-md-4 ml-4 mr-auto column">
                                <div class="mb-4">
                                    <label class="control-label" for="tyre_type">Тип</label>
                                    <select id="tyre_type" v-model="tyre_type" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="бескамерные" >бескамерные</option>
                                        <option value="камерные" >камерные</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="seasonality">Сезонность</label>
                                    <select id="seasonality" v-model="seasonality" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="всесезонные" >всесезонные</option>
                                        <option value="зимние" >зимние</option>
                                        <option value="летние" >летние</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="manufacturer">Производитель</label>
                                    <select id="manufacturer" v-model="tyre_manufacturer" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="ACCELERA" >ACCELERA</option>
                                        <option value="ACHILLES" >ACHILLES</option>
                                        <option value="ADERENZA" >ADERENZA</option>
                                        <option value="AEOLUS" >AEOLUS</option>
                                        <option value="ALTENZO" >ALTENZO</option>
                                        <option value="AMERICA" >AMERICA</option>
                                        <option value="AMTEL" >AMTEL</option>
                                        <option value="ANTARES" >ANTARES</option>
                                        <option value="APOLLO" >APOLLO</option>
                                        <option value="APOLLO TYRES" >APOLLO TYRES</option>
                                        <option value="ARCTIC CLAW" >ARCTIC CLAW</option>
                                        <option value="ATTURO" >ATTURO</option>
                                        <option value="AURORA TIRE" >AURORA TIRE</option>
                                        <option value="AUSTONE" >AUSTONE</option>
                                        <option value="AUTOGUARD" >AUTOGUARD</option>
                                        <option value="AVON" >AVON</option>
                                        <option value="BARUM" >BARUM</option>
                                        <option value="BFGOODRICH" >BFGOODRICH</option>
                                        <option value="BIG O TIRES" >BIG O TIRES</option>
                                        <option value="BLACKSTONE" >BLACKSTONE</option>
                                        <option value="BRASA" >BRASA</option>
                                        <option value="BRIDGESTONE" >BRIDGESTONE</option>
                                        <option value="CAPITOL" >CAPITOL</option>
                                        <option value="CEAT" >CEAT</option>
                                        <option value="CONTINENTAL" >CONTINENTAL</option>
                                        <option value="CONTYRE" >CONTYRE</option>
                                        <option value="COOPER" >COOPER</option>
                                        <option value="COOPER CHENGSHAN" >COOPER CHENGSHAN</option>
                                        <option value="CORDIANT" >CORDIANT</option>
                                        <option value="CORDOVAN" >CORDOVAN</option>
                                        <option value="DAEWOO" >DAEWOO</option>
                                        <option value="DAYTON" >DAYTON</option>
                                        <option value="DEAN TIRES" >DEAN TIRES</option>
                                        <option value="DEBICA" >DEBICA</option>
                                        <option value="DELINTE" >DELINTE</option>
                                        <option value="DEXTERO" >DEXTERO</option>
                                        <option value="DIAMONDBACK" >DIAMONDBACK</option>
                                        <option value="DICK CEPEK" >DICK CEPEK</option>
                                        <option value="DIPLOMAT" >DIPLOMAT</option>
                                        <option value="DMACK" >DMACK</option>
                                        <option value="DUNLOP" >DUNLOP</option>
                                        <option value="DURUN" >DURUN</option>
                                        <option value="EFFIPLUS" >EFFIPLUS</option>
                                        <option value="ELDORADO TIRE" >ELDORADO TIRE</option>
                                        <option value="ESA-TECAR" >ESA-TECAR</option>
                                        <option value="EVERGREEN" >EVERGREEN</option>
                                        <option value="FALKEN" >FALKEN</option>
                                        <option value="FATE" >FATE</option>
                                        <option value="FEDERAL" >FEDERAL</option>
                                        <option value="FEDIMA" >FEDIMA</option>
                                        <option value="FENIX" >FENIX</option>
                                        <option value="FIRESTONE" >FIRESTONE</option>
                                        <option value="FORTIO" >FORTIO</option>
                                        <option value="FORTUNA" >FORTUNA</option>
                                        <option value="FULDA" >FULDA</option>
                                        <option value="FULLRUN" >FULLRUN</option>
                                        <option value="FULLWAY" >FULLWAY</option>
                                        <option value="FUZION" >FUZION</option>
                                        <option value="GENERAL TIRE" >GENERAL TIRE</option>
                                        <option value="GISLAVED" >GISLAVED</option>
                                        <option value="GOFORM" >GOFORM</option>
                                        <option value="GOLDWAY" >GOLDWAY</option>
                                        <option value="GOODRIDE" >GOODRIDE</option>
                                        <option value="GOODYEAR" >GOODYEAR</option>
                                        <option value="GREENDIAMOND" >GREENDIAMOND</option>
                                        <option value="GREMAX" >GREMAX</option>
                                        <option value="GT RADIAL" >GT RADIAL</option>
                                        <option value="HAIDA GROUP" >HAIDA GROUP</option>
                                        <option value="HANKOOK" >HANKOOK</option>
                                        <option value="HEADWAY" >HEADWAY</option>
                                        <option value="HERCULES" >HERCULES</option>
                                        <option value="HIFLY" >HIFLY</option>
                                        <option value="IMPERIAL" >IMPERIAL</option>
                                        <option value="INFINITY TYRES" >INFINITY TYRES</option>
                                        <option value="INSA TURBO" >INSA TURBO</option>
                                        <option value="INTERCO" >INTERCO</option>
                                        <option value="INTERSTATE" >INTERSTATE</option>
                                        <option value="IRONMAN" >IRONMAN</option>
                                        <option value="JETZON TIRE" >JETZON TIRE</option>
                                        <option value="JINYU" >JINYU</option>
                                        <option value="KELLY" >KELLY</option>
                                        <option value="KENDA" >KENDA</option>
                                        <option value="KENEX" >KENEX</option>
                                        <option value="KINFOREST" >KINFOREST</option>
                                        <option value="KING MEILER" >KING MEILER</option>
                                        <option value="KINGSTAR" >KINGSTAR</option>
                                        <option value="KLEBER" >KLEBER</option>
                                        <option value="KORMORAN" >KORMORAN</option>
                                        <option value="KUMHO" >KUMHO</option>
                                        <option value="LANDSAIL" >LANDSAIL</option>
                                        <option value="LASSA" >LASSA</option>
                                        <option value="LINGLONG" >LINGLONG</option>
                                        <option value="MABOR" >MABOR</option>
                                        <option value="MALOYA" >MALOYA</option>
                                        <option value="MARANGONI" >MARANGONI</option>
                                        <option value="MARSHAL" >MARSHAL</option>
                                        <option value="MASTERCRAFT" >MASTERCRAFT</option>
                                        <option value="MATADOR" >MATADOR</option>
                                        <option value="MAXTREK" >MAXTREK</option>
                                        <option value="MAXXIS" >MAXXIS</option>
                                        <option value="MAYRUN" >MAYRUN</option>
                                        <option value="MEDEO" >MEDEO</option>
                                        <option value="MENTOR" >MENTOR</option>
                                        <option value="MICHELIN" >MICHELIN</option>
                                        <option value="MICKEY THOMPSON" >MICKEY THOMPSON</option>
                                        <option value="MILESTONE" >MILESTONE</option>
                                        <option value="MINERVA" >MINERVA</option>
                                        <option value="MOTOMASTER" >MOTOMASTER</option>
                                        <option value="MULTI-MILE" >MULTI-MILE</option>
                                        <option value="NANKANG" >NANKANG</option>
                                        <option value="NEUTON" >NEUTON</option>
                                        <option value="NEXEN" >NEXEN</option>
                                        <option value="NITTO" >NITTO</option>
                                        <option value="NOKIAN" >NOKIAN</option>
                                        <option value="NORDMAN" >NORDMAN</option>
                                        <option value="NOVEX" >NOVEX</option>
                                        <option value="OVATION TYRES" >OVATION TYRES</option>
                                        <option value="PACE" >PACE</option>
                                        <option value="PETLAS" >PETLAS</option>
                                        <option value="PIRELLI" >PIRELLI</option>
                                        <option value="PLATIN" >PLATIN</option>
                                        <option value="PNEUMANT" >PNEUMANT</option>
                                        <option value="POINT S" >POINT S</option>
                                        <option value="PREMIORRI" >PREMIORRI</option>
                                        <option value="PRO COMP" >PRO COMP</option>
                                        <option value="RAPID" >RAPID</option>
                                        <option value="REGAL" >REGAL</option>
                                        <option value="RIKEN" >RIKEN</option>
                                        <option value="ROADSTONE" >ROADSTONE</option>
                                        <option value="ROCKSTONE" >ROCKSTONE</option>
                                        <option value="ROSAVA" >ROSAVA</option>
                                        <option value="ROTALLA" >ROTALLA</option>
                                        <option value="ROTEX" >ROTEX</option>
                                        <option value="SAGITAR" >SAGITAR</option>
                                        <option value="SAILUN" >SAILUN</option>
                                        <option value="SAVA" >SAVA</option>
                                        <option value="SEMPERIT" >SEMPERIT</option>
                                        <option value="SILVERSTONE" >SILVERSTONE</option>
                                        <option value="SIME TYRES" >SIME TYRES</option>
                                        <option value="SIMEX" >SIMEX</option>
                                        <option value="SONAR" >SONAR</option>
                                        <option value="SONNY" >SONNY</option>
                                        <option value="SPORTIVA" >SPORTIVA</option>
                                        <option value="STARFIRE" >STARFIRE</option>
                                        <option value="SUMITOMO" >SUMITOMO</option>
                                        <option value="SUMO" >SUMO</option>
                                        <option value="SUNITRAC" >SUNITRAC</option>
                                        <option value="SUNNY" >SUNNY</option>
                                        <option value="SUNTEK" >SUNTEK</option>
                                        <option value="SYRON" >SYRON</option>
                                        <option value="TELSTAR TIRE" >TELSTAR TIRE</option>
                                        <option value="THUNDERER" >THUNDERER</option>
                                        <option value="TIGAR" >TIGAR</option>
                                        <option value="TOYO" >TOYO</option>
                                        <option value="TRACMAX" >TRACMAX</option>
                                        <option value="TRAYAL" >TRAYAL</option>
                                        <option value="TRI ACE" >TRI ACE</option>
                                        <option value="TRIANGLE GROUP" >TRIANGLE GROUP</option>
                                        <option value="TUNGA" >TUNGA</option>
                                        <option value="UNIROYAL" >UNIROYAL</option>
                                        <option value="VALLEYSTONE" >VALLEYSTONE</option>
                                        <option value="VIATTI" >VIATTI</option>
                                        <option value="VICTORUN" >VICTORUN</option>
                                        <option value="VIKING" >VIKING</option>
                                        <option value="VREDESTEIN" >VREDESTEIN</option>
                                        <option value="VSP" >VSP</option>
                                        <option value="WANLI" >WANLI</option>
                                        <option value="WESTLAKE" >WESTLAKE</option>
                                        <option value="WESTLAKE TYRES" >WESTLAKE TYRES</option>
                                        <option value="WINTER TACT" >WINTER TACT</option>
                                        <option value="YOKOHAMA" >YOKOHAMA</option>
                                        <option value="ZEETEX" >ZEETEX</option>
                                        <option value="ZETA" >ZETA</option>
                                        <option value="АЛТАЙСКИЙ ШИННЫЙ КОМБИНАТ" >АЛТАЙСКИЙ ШИННЫЙ КОМБИНАТ</option>
                                        <option value="БЕЛШИНА" >БЕЛШИНА</option>
                                        <option value="ВОЛТАЙР" >ВОЛТАЙР</option>
                                        <option value="ДНЕПРОШИНА" >ДНЕПРОШИНА</option>
                                        <option value="КШЗ" >КШЗ</option>
                                        <option value="МАСТЕР-СПОРТ" >МАСТЕР-СПОРТ</option>
                                        <option value="МШЗ" >МШЗ</option>
                                        <option value="НИЖНЕКАМСКШИНА" >НИЖНЕКАМСКШИНА</option>
                                        <option value="ОМСКШИНА" >ОМСКШИНА</option>
                                        <option value="УРАЛШИНА" >УРАЛШИНА</option>
                                        <option value="ЯШЗ" >ЯШЗ</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row justify-content-center ml-auto mr-auto">
                            <button class="chopcafe_btn form_btn ml-auto mr-auto" v-on:click="sendTyresRequest()">Отправить</button>
                        </div>
                    </tab>
                    <tab name="Поиск дисков" v-if="vinrequests.length>0">
<!--                        <div class="row justify-content-center ml-auto mr-auto" style="width: 100%">-->

<!--&lt;!&ndash;                                <label class="col-md-6 control-label" for="tyre_width">Vin-code</label>&ndash;&gt;-->
<!--                                <div class="col-md-6">-->

<!--                            </div>-->
<!--                        </div>-->

                        <div class="row justify-content-center ml-auto mr-auto" style="width: 100%">
                            <div class="col-md-4 mr-4 ml-auto column">

                                <div class="mb-4">
                                    <label class="control-label" for="tyre_width">Vin-code</label>
                                    <select v-model="disk_vinrequest_id" id="disk_vinrequest_id" class="form-control">
                                        <option v-for="vin in vincodes" :value="vin">{{vin.vincode}}</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="disc_width">Ширина диска</label>
                                    <select id="disc_width" v-model="disc_width" class="form-control">
                                        <option value="-1">не выбрана</option>
                                        <option value="3" >3</option>
                                        <option value="3.5" >3.5</option>
                                        <option value="4" >4</option>
                                        <option value="4.5" >4.5</option>
                                        <option value="5" >5</option>
                                        <option value="5.25" >5.25</option>
                                        <option value="5.5" >5.5</option>
                                        <option value="5.51" >5.51</option>
                                        <option value="6" >6</option>
                                        <option value="6.5" >6.5</option>
                                        <option value="6.75" >6.75</option>
                                        <option value="7" >7</option>
                                        <option value="7.25" >7.25</option>
                                        <option value="7.5" >7.5</option>
                                        <option value="8" >8</option>
                                        <option value="8.25" >8.25</option>
                                        <option value="8.5" >8.5</option>
                                        <option value="8.75" >8.75</option>
                                        <option value="9" >9</option>
                                        <option value="9.5" >9.5</option>
                                        <option value="9.9" >9.9</option>
                                        <option value="10" >10</option>
                                        <option value="10.25" >10.25</option>
                                        <option value="10.5" >10.5</option>
                                        <option value="10.75" >10.75</option>
                                        <option value="11" >11</option>
                                        <option value="11.25" >11.25</option>
                                        <option value="11.5" >11.5</option>
                                        <option value="11.75" >11.75</option>
                                        <option value="12" >12</option>
                                        <option value="13" >13</option>
                                        <option value="14" >14</option>

                                    </select>

                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="disk_diameter">Диаметр</label>
                                    <select id="disk_diameter" v-model="disk_diameter" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="12" >12</option>
                                        <option value="13" >13</option>
                                        <option value="14" >14</option>
                                        <option value="15" >15</option>
                                        <option value="16" >16</option>
                                        <option value="17" >17</option>
                                        <option value="17.5" >17.5</option>
                                        <option value="18" >18</option>
                                        <option value="19" >19</option>
                                        <option value="19.5" >19.5</option>
                                        <option value="20" >20</option>
                                        <option value="21" >21</option>
                                        <option value="22" >22</option>
                                        <option value="22.5" >22.5</option>
                                        <option value="23" >23</option>
                                        <option value="24" >24</option>
                                        <option value="25" >25</option>
                                        <option value="26" >26</option>
                                        <option value="28" >28</option>


                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="radius">Вылет</label>
                                    <select id="radius" v-model="radius" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="1" >1</option>
                                        <option value="2" >2</option>
                                        <option value="3" >3</option>
                                        <option value="4" >4</option>
                                        <option value="5" >5</option>
                                        <option value="6" >6</option>
                                        <option value="7" >7</option>
                                        <option value="8" >8</option>
                                        <option value="8.60" >8.60</option>
                                        <option value="9" >9</option>
                                        <option value="10" >10</option>
                                        <option value="11" >11</option>
                                        <option value="12" >12</option>
                                        <option value="12.50" >12.50</option>
                                        <option value="13" >13</option>
                                        <option value="14" >14</option>
                                        <option value="15" >15</option>
                                        <option value="15.50" >15.50</option>
                                        <option value="16" >16</option>
                                        <option value="17" >17</option>
                                        <option value="18" >18</option>
                                        <option value="19" >19</option>
                                        <option value="20" >20</option>
                                        <option value="20.50" >20.50</option>
                                        <option value="21" >21</option>
                                        <option value="22" >22</option>
                                        <option value="23" >23</option>
                                        <option value="23.50" >23.50</option>
                                        <option value="24" >24</option>
                                        <option value="25" >25</option>
                                        <option value="26" >26</option>
                                        <option value="27" >27</option>
                                        <option value="27.30" >27.30</option>
                                        <option value="28" >28</option>
                                        <option value="28.50" >28.50</option>
                                        <option value="29" >29</option>
                                        <option value="30" >30</option>
                                        <option value="30.50" >30.50</option>
                                        <option value="31" >31</option>
                                        <option value="31.50" >31.50</option>
                                        <option value="32" >32</option>
                                        <option value="33" >33</option>
                                        <option value="34" >34</option>
                                        <option value="34.50" >34.50</option>
                                        <option value="35" >35</option>
                                        <option value="36" >36</option>
                                        <option value="36.50" >36.50</option>
                                        <option value="37" >37</option>
                                        <option value="37.50" >37.50</option>
                                        <option value="38" >38</option>
                                        <option value="38.80" >38.80</option>
                                        <option value="39" >39</option>
                                        <option value="39.50" >39.50</option>
                                        <option value="39.80" >39.80</option>
                                        <option value="40" >40</option>
                                        <option value="40.50" >40.50</option>
                                        <option value="40.75" >40.75</option>
                                        <option value="41" >41</option>
                                        <option value="41.30" >41.30</option>
                                        <option value="41.50" >41.50</option>
                                        <option value="42" >42</option>
                                        <option value="43" >43</option>
                                        <option value="43.50" >43.50</option>
                                        <option value="43.80" >43.80</option>
                                        <option value="44" >44</option>
                                        <option value="45" >45</option>
                                        <option value="45.10" >45.10</option>
                                        <option value="45.20" >45.20</option>
                                        <option value="45.50" >45.50</option>
                                        <option value="45.70" >45.70</option>
                                        <option value="46" >46</option>
                                        <option value="47" >47</option>
                                        <option value="47.50" >47.50</option>
                                        <option value="48" >48</option>
                                        <option value="48.50" >48.50</option>
                                        <option value="49" >49</option>
                                        <option value="49.50" >49.50</option>
                                        <option value="50" >50</option>
                                        <option value="50.50" >50.50</option>
                                        <option value="50.80" >50.80</option>
                                        <option value="51" >51</option>
                                        <option value="52" >52</option>
                                        <option value="52.20" >52.20</option>
                                        <option value="52.50" >52.50</option>
                                        <option value="52.60" >52.60</option>
                                        <option value="53" >53</option>
                                        <option value="54" >54</option>
                                        <option value="54.10" >54.10</option>
                                        <option value="55" >55</option>
                                        <option value="56" >56</option>
                                        <option value="56.10" >56.10</option>
                                        <option value="56.40" >56.40</option>
                                        <option value="56.60" >56.60</option>
                                        <option value="57" >57</option>
                                        <option value="57.10" >57.10</option>
                                        <option value="58" >58</option>
                                        <option value="58.60" >58.60</option>
                                        <option value="59" >59</option>
                                        <option value="60" >60</option>
                                        <option value="60.10" >60.10</option>
                                        <option value="62" >62</option>
                                        <option value="63" >63</option>
                                        <option value="63.30" >63.30</option>
                                        <option value="63.40" >63.40</option>
                                        <option value="64" >64</option>
                                        <option value="65" >65</option>
                                        <option value="65.10" >65.10</option>
                                        <option value="66" >66</option>
                                        <option value="66.60" >66.60</option>
                                        <option value="67" >67</option>
                                        <option value="67.10" >67.10</option>
                                        <option value="68" >68</option>
                                        <option value="69.10" >69.10</option>
                                        <option value="70" >70</option>
                                        <option value="71.60" >71.60</option>
                                        <option value="72" >72</option>
                                        <option value="72.20" >72.20</option>
                                        <option value="72.60" >72.60</option>
                                        <option value="73.10" >73.10</option>
                                        <option value="73.20" >73.20</option>
                                        <option value="74.10" >74.10</option>
                                        <option value="75" >75</option>
                                        <option value="83" >83</option>
                                        <option value="87.10" >87.10</option>
                                        <option value="99.90" >99.90</option>
                                        <option value="102" >102</option>
                                        <option value="105" >105</option>
                                        <option value="105.50" >105.50</option>
                                        <option value="106" >106</option>
                                        <option value="107" >107</option>
                                        <option value="108" >108</option>
                                        <option value="110" >110</option>
                                        <option value="110.10" >110.10</option>
                                        <option value="112" >112</option>
                                        <option value="114" >114</option>
                                        <option value="115" >115</option>
                                        <option value="120" >120</option>
                                        <option value="121.50" >121.50</option>
                                        <option value="122" >122</option>
                                        <option value="122.50" >122.50</option>
                                        <option value="123" >123</option>
                                        <option value="125" >125</option>
                                        <option value="126" >126</option>
                                        <option value="128" >128</option>
                                        <option value="128.50" >128.50</option>
                                        <option value="129" >129</option>
                                        <option value="132" >132</option>
                                        <option value="132.50" >132.50</option>
                                        <option value="134" >134</option>
                                        <option value="135" >135</option>
                                        <option value="135.50" >135.50</option>
                                        <option value="140" >140</option>
                                        <option value="142" >142</option>
                                        <option value="143" >143</option>
                                        <option value="147" >147</option>
                                        <option value="149" >149</option>
                                        <option value="150" >150</option>
                                        <option value="152" >152</option>
                                        <option value="153" >153</option>
                                        <option value="156" >156</option>
                                        <option value="157" >157</option>
                                        <option value="161" >161</option>
                                        <option value="163" >163</option>
                                        <option value="165" >165</option>
                                        <option value="175" >175</option>
                                        <option value="220" >220</option>

                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="control-label" for="holes">Отверстий</label>
                                    <select id="holes" v-model="holes" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="3" >3</option>
                                        <option value="4" >4</option>
                                        <option value="5" >5</option>
                                        <option value="6" >6</option>
                                        <option value="8" >8</option>
                                        <option value="9" >9</option>
                                        <option value="10" >10</option>

                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4 ml-4 mr-auto column">

                                <div class="mb-4">
                                    <label class="control-label" for="pcd">PCD</label>
                                    <select id="pcd" v-model="pcd" class="form-control">
                                        <option value="-1">не выбрано</option>
                                        <option value="98" >98</option>
                                        <option value="100" >100</option>
                                        <option value="105" >105</option>
                                        <option value="107.95" >107.95</option>
                                        <option value="108" >108</option>
                                        <option value="110" >110</option>
                                        <option value="112" >112</option>
                                        <option value="114" >114</option>
                                        <option value="114.3" >114.3</option>
                                        <option value="115" >115</option>
                                        <option value="118" >118</option>
                                        <option value="120" >120</option>
                                        <option value="120.6" >120.6</option>
                                        <option value="120.65" >120.65</option>
                                        <option value="120.7" >120.7</option>
                                        <option value="125" >125</option>
                                        <option value="127" >127</option>
                                        <option value="130" >130</option>
                                        <option value="130.65" >130.65</option>
                                        <option value="135" >135</option>
                                        <option value="139" >139</option>
                                        <option value="139.1" >139.1</option>
                                        <option value="139.7" >139.7</option>
                                        <option value="150" >150</option>
                                        <option value="160" >160</option>
                                        <option value="161" >161</option>
                                        <option value="165" >165</option>
                                        <option value="165.1" >165.1</option>
                                        <option value="170" >170</option>
                                        <option value="175" >175</option>
                                        <option value="180" >180</option>
                                        <option value="189" >189</option>
                                        <option value="190" >190</option>
                                        <option value="199" >199</option>
                                        <option value="200" >200</option>
                                        <option value="205" >205</option>
                                        <option value="208" >208</option>
                                        <option value="210" >210</option>
                                        <option value="220" >220</option>
                                        <option value="222.25" >222.25</option>
                                        <option value="222.5" >222.5</option>
                                        <option value="225" >225</option>
                                        <option value="245" >245</option>
                                        <option value="256" >256</option>
                                        <option value="275" >275</option>
                                        <option value="285" >285</option>
                                        <option value="285.75" >285.75</option>
                                        <option value="335" >335</option>

                                    </select>
                                </div>

                                <div class="mb-4">
                                        <label class="control-label" for="dco">ДЦО</label>
                                        <select id="dco" v-model="dco" class="form-control">
                                            <option value="-1">не выбрано</option>
                                            <option value="10" >10</option>
                                            <option value="15" >15</option>
                                            <option value="24" >24</option>
                                            <option value="35" >35</option>
                                            <option value="37" >37</option>
                                            <option value="37.1" >37.1</option>
                                            <option value="37.5" >37.5</option>
                                            <option value="38" >38</option>
                                            <option value="39" >39</option>
                                            <option value="40" >40</option>
                                            <option value="42" >42</option>
                                            <option value="43" >43</option>
                                            <option value="45" >45</option>
                                            <option value="46" >46</option>
                                            <option value="50" >50</option>
                                            <option value="51.4" >51.4</option>
                                            <option value="52" >52</option>
                                            <option value="52.5" >52.5</option>
                                            <option value="54" >54</option>
                                            <option value="54.1" >54.1</option>
                                            <option value="54.5" >54.5</option>
                                            <option value="55" >55</option>
                                            <option value="55.1" >55.1</option>
                                            <option value="55.3" >55.3</option>
                                            <option value="55.5" >55.5</option>
                                            <option value="56" >56</option>
                                            <option value="56.1" >56.1</option>
                                            <option value="56.4" >56.4</option>
                                            <option value="56.5" >56.5</option>
                                            <option value="56.56" >56.56</option>
                                            <option value="56.6" >56.6</option>
                                            <option value="56.62" >56.62</option>
                                            <option value="56.7" >56.7</option>
                                            <option value="56.8" >56.8</option>
                                            <option value="57" >57</option>
                                            <option value="57.06" >57.06</option>
                                            <option value="57.1" >57.1</option>
                                            <option value="57.32" >57.32</option>
                                            <option value="57.6" >57.6</option>
                                            <option value="58" >58</option>
                                            <option value="58.06" >58.06</option>
                                            <option value="58.1" >58.1</option>
                                            <option value="58.5" >58.5</option>
                                            <option value="58.55" >58.55</option>
                                            <option value="58.6" >58.6</option>
                                            <option value="58.8" >58.8</option>
                                            <option value="59" >59</option>
                                            <option value="59.1" >59.1</option>
                                            <option value="59.5" >59.5</option>
                                            <option value="59.6" >59.6</option>
                                            <option value="60" >60</option>
                                            <option value="60.1" >60.1</option>
                                            <option value="60.2" >60.2</option>
                                            <option value="60.4" >60.4</option>
                                            <option value="60.5" >60.5</option>
                                            <option value="60.8" >60.8</option>
                                            <option value="61" >61</option>
                                            <option value="61.1" >61.1</option>
                                            <option value="61.9" >61.9</option>
                                            <option value="63" >63</option>
                                            <option value="63.1" >63.1</option>
                                            <option value="63.3" >63.3</option>
                                            <option value="63.34" >63.34</option>
                                            <option value="63.35" >63.35</option>
                                            <option value="63.4" >63.4</option>
                                            <option value="63.5" >63.5</option>
                                            <option value="63.6" >63.6</option>
                                            <option value="64" >64</option>
                                            <option value="64.1" >64.1</option>
                                            <option value="64.5" >64.5</option>
                                            <option value="65" >65</option>
                                            <option value="65.05" >65.05</option>
                                            <option value="65.06" >65.06</option>
                                            <option value="65.1" >65.1</option>
                                            <option value="65.2" >65.2</option>
                                            <option value="65.5" >65.5</option>
                                            <option value="65.56" >65.56</option>
                                            <option value="65.6" >65.6</option>
                                            <option value="66" >66</option>
                                            <option value="66.1" >66.1</option>
                                            <option value="66.2" >66.2</option>
                                            <option value="66.3" >66.3</option>
                                            <option value="66.4" >66.4</option>
                                            <option value="66.45" >66.45</option>
                                            <option value="66.46" >66.46</option>
                                            <option value="66.5" >66.5</option>
                                            <option value="66.56" >66.56</option>
                                            <option value="66.6" >66.6</option>
                                            <option value="66.9" >66.9</option>
                                            <option value="66.92" >66.92</option>
                                            <option value="67" >67</option>
                                            <option value="67.04" >67.04</option>
                                            <option value="67.1" >67.1</option>
                                            <option value="67.13" >67.13</option>
                                            <option value="67.14" >67.14</option>
                                            <option value="67.2" >67.2</option>
                                            <option value="67.7" >67.7</option>
                                            <option value="68" >68</option>
                                            <option value="68.1" >68.1</option>
                                            <option value="69" >69</option>
                                            <option value="69.1" >69.1</option>
                                            <option value="69.2" >69.2</option>
                                            <option value="69.3" >69.3</option>
                                            <option value="69.6" >69.6</option>
                                            <option value="70" >70</option>
                                            <option value="70.02" >70.02</option>
                                            <option value="70.1" >70.1</option>
                                            <option value="70.2" >70.2</option>
                                            <option value="70.27" >70.27</option>
                                            <option value="70.28" >70.28</option>
                                            <option value="70.3" >70.3</option>
                                            <option value="70.4" >70.4</option>
                                            <option value="70.5" >70.5</option>
                                            <option value="70.6" >70.6</option>
                                            <option value="70.64" >70.64</option>
                                            <option value="70.7" >70.7</option>
                                            <option value="70.8" >70.8</option>
                                            <option value="71" >71</option>
                                            <option value="71.1" >71.1</option>
                                            <option value="71.3" >71.3</option>
                                            <option value="71.4" >71.4</option>
                                            <option value="71.5" >71.5</option>
                                            <option value="71.56" >71.56</option>
                                            <option value="71.58" >71.58</option>
                                            <option value="71.6" >71.6</option>
                                            <option value="71.69" >71.69</option>
                                            <option value="72" >72</option>
                                            <option value="72.1" >72.1</option>
                                            <option value="72.2" >72.2</option>
                                            <option value="72.27" >72.27</option>
                                            <option value="72.3" >72.3</option>
                                            <option value="72.4" >72.4</option>
                                            <option value="72.5" >72.5</option>
                                            <option value="72.56" >72.56</option>
                                            <option value="72.6" >72.6</option>
                                            <option value="72.62" >72.62</option>
                                            <option value="72.69" >72.69</option>
                                            <option value="72.7" >72.7</option>
                                            <option value="73" >73</option>
                                            <option value="73.06" >73.06</option>
                                            <option value="73.1" >73.1</option>
                                            <option value="73.13" >73.13</option>
                                            <option value="73.14" >73.14</option>
                                            <option value="73.2" >73.2</option>
                                            <option value="73.6" >73.6</option>
                                            <option value="73.7" >73.7</option>
                                            <option value="73.8" >73.8</option>
                                            <option value="73.9" >73.9</option>
                                            <option value="74" >74</option>
                                            <option value="74.1" >74.1</option>
                                            <option value="74.2" >74.2</option>
                                            <option value="74.4" >74.4</option>
                                            <option value="74.5" >74.5</option>
                                            <option value="75" >75</option>
                                            <option value="75.1" >75.1</option>
                                            <option value="76" >76</option>
                                            <option value="76.1" >76.1</option>
                                            <option value="76.5" >76.5</option>
                                            <option value="76.9" >76.9</option>
                                            <option value="77" >77</option>
                                            <option value="77.77" >77.77</option>
                                            <option value="77.8" >77.8</option>
                                            <option value="77.9" >77.9</option>
                                            <option value="78" >78</option>
                                            <option value="78.09" >78.09</option>
                                            <option value="78.1" >78.1</option>
                                            <option value="78.2" >78.2</option>
                                            <option value="78.3" >78.3</option>
                                            <option value="79" >79</option>
                                            <option value="79.1" >79.1</option>
                                            <option value="79.5" >79.5</option>
                                            <option value="79.6" >79.6</option>
                                            <option value="80" >80</option>
                                            <option value="80.1" >80.1</option>
                                            <option value="82" >82</option>
                                            <option value="83" >83</option>
                                            <option value="83.3" >83.3</option>
                                            <option value="83.7" >83.7</option>
                                            <option value="84" >84</option>
                                            <option value="84.1" >84.1</option>
                                            <option value="84.2" >84.2</option>
                                            <option value="85" >85</option>
                                            <option value="85.1" >85.1</option>
                                            <option value="85.3" >85.3</option>
                                            <option value="85.6" >85.6</option>
                                            <option value="86.8" >86.8</option>
                                            <option value="86.87" >86.87</option>
                                            <option value="86.89" >86.89</option>
                                            <option value="87" >87</option>
                                            <option value="87.1" >87.1</option>
                                            <option value="87.2" >87.2</option>
                                            <option value="87.5" >87.5</option>
                                            <option value="89" >89</option>
                                            <option value="89.1" >89.1</option>
                                            <option value="92.1" >92.1</option>
                                            <option value="92.3" >92.3</option>
                                            <option value="92.5" >92.5</option>
                                            <option value="92.6" >92.6</option>
                                            <option value="92.7" >92.7</option>
                                            <option value="93" >93</option>
                                            <option value="93.1" >93.1</option>
                                            <option value="93.3" >93.3</option>
                                            <option value="93.7" >93.7</option>
                                            <option value="95" >95</option>
                                            <option value="95.1" >95.1</option>
                                            <option value="95.3" >95.3</option>
                                            <option value="95.35" >95.35</option>
                                            <option value="95.4" >95.4</option>
                                            <option value="95.5" >95.5</option>
                                            <option value="95.6" >95.6</option>
                                            <option value="96" >96</option>
                                            <option value="98" >98</option>
                                            <option value="98.1" >98.1</option>
                                            <option value="98.5" >98.5</option>
                                            <option value="98.6" >98.6</option>
                                            <option value="99.9" >99.9</option>
                                            <option value="100" >100</option>
                                            <option value="100.1" >100.1</option>
                                            <option value="100.2" >100.2</option>
                                            <option value="100.3" >100.3</option>
                                            <option value="100.5" >100.5</option>
                                            <option value="101" >101</option>
                                            <option value="101.5" >101.5</option>
                                            <option value="101.9" >101.9</option>
                                            <option value="102" >102</option>
                                            <option value="103" >103</option>
                                            <option value="104.1" >104.1</option>
                                            <option value="106" >106</option>
                                            <option value="106.1" >106.1</option>
                                            <option value="106.2" >106.2</option>
                                            <option value="106.25" >106.25</option>
                                            <option value="106.3" >106.3</option>
                                            <option value="106.5" >106.5</option>
                                            <option value="106.6" >106.6</option>
                                            <option value="106.9" >106.9</option>
                                            <option value="107" >107</option>
                                            <option value="107.1" >107.1</option>
                                            <option value="107.5" >107.5</option>
                                            <option value="107.6" >107.6</option>
                                            <option value="107.95" >107.95</option>
                                            <option value="108" >108</option>
                                            <option value="108.1" >108.1</option>
                                            <option value="108.2" >108.2</option>
                                            <option value="108.3" >108.3</option>
                                            <option value="108.4" >108.4</option>
                                            <option value="108.5" >108.5</option>
                                            <option value="108.6" >108.6</option>
                                            <option value="108.7" >108.7</option>
                                            <option value="109" >109</option>
                                            <option value="109.5" >109.5</option>
                                            <option value="109.7" >109.7</option>
                                            <option value="109.8" >109.8</option>
                                            <option value="110" >110</option>
                                            <option value="110.1" >110.1</option>
                                            <option value="110.16" >110.16</option>
                                            <option value="110.2" >110.2</option>
                                            <option value="110.3" >110.3</option>
                                            <option value="110.5" >110.5</option>
                                            <option value="110.6" >110.6</option>
                                            <option value="111" >111</option>
                                            <option value="111.1" >111.1</option>
                                            <option value="111.2" >111.2</option>
                                            <option value="111.5" >111.5</option>
                                            <option value="111.6" >111.6</option>
                                            <option value="112" >112</option>
                                            <option value="112.1" >112.1</option>
                                            <option value="112.2" >112.2</option>
                                            <option value="112.5" >112.5</option>
                                            <option value="112.75" >112.75</option>
                                            <option value="113" >113</option>
                                            <option value="113.1" >113.1</option>
                                            <option value="114" >114</option>
                                            <option value="114.3" >114.3</option>
                                            <option value="116.7" >116.7</option>
                                            <option value="116.9" >116.9</option>
                                            <option value="118" >118</option>
                                            <option value="120" >120</option>
                                            <option value="122.5" >122.5</option>
                                            <option value="124" >124</option>
                                            <option value="125" >125</option>
                                            <option value="125.1" >125.1</option>
                                            <option value="127" >127</option>
                                            <option value="130" >130</option>
                                            <option value="130.1" >130.1</option>
                                            <option value="130.2" >130.2</option>
                                            <option value="130.8" >130.8</option>
                                            <option value="130.81" >130.81</option>
                                            <option value="131" >131</option>
                                            <option value="138.8" >138.8</option>
                                            <option value="139" >139</option>
                                            <option value="139.7" >139.7</option>
                                            <option value="141" >141</option>
                                            <option value="142.1" >142.1</option>
                                            <option value="150" >150</option>
                                            <option value="160" >160</option>
                                            <option value="161" >161</option>
                                            <option value="164" >164</option>
                                            <option value="168" >168</option>
                                            <option value="176" >176</option>
                                            <option value="202" >202</option>
                                            <option value="220" >220</option>
                                            <option value="221" >221</option>
                                            <option value="228" >228</option>
                                            <option value="281" >281</option>
                                            <option value="561" >561</option>
                                            <option value="701" >701</option>
                                            <option value="716" >716</option>

                                        </select>
                                    </div>

                                <div class="mb-4">
                                        <label class="control-label" for="type">Тип</label>
                                        <select id="type" v-model="type" class="form-control">
                                            <option value="-1">не выбрано</option>
                                            <option value="кованые" >кованые</option>
                                            <option value="литые" >литые</option>
                                            <option value="сборные" >сборные</option>
                                            <option value="штампованные" >штампованные</option>

                                        </select>
                                    </div>

                                <div class="mb-4">
                                        <label class="control-label" for="color">Цвет</label>
                                        <select id="color" v-model="color" class="form-control">
                                            <option value="-1">не выбрано</option>
                                            <option value="белый" >белый</option>
                                            <option value="белый+красный" >белый+красный</option>
                                            <option value="белый+синий" >белый+синий</option>
                                            <option value="графит" >графит</option>
                                            <option value="желтый" >желтый</option>
                                            <option value="зеленый" >зеленый</option>
                                            <option value="золотистый" >золотистый</option>
                                            <option value="коричневый" >коричневый</option>
                                            <option value="красный" >красный</option>
                                            <option value="оранжевый" >оранжевый</option>
                                            <option value="серебристый" >серебристый</option>
                                            <option value="серебристый+бронзовый" >серебристый+бронзовый</option>
                                            <option value="серебристый+графит" >серебристый+графит</option>
                                            <option value="серебристый+красный" >серебристый+красный</option>
                                            <option value="серебристый+синий" >серебристый+синий</option>
                                            <option value="серебристый+черный" >серебристый+черный</option>
                                            <option value="синий" >синий</option>
                                            <option value="хром" >хром</option>
                                            <option value="черный" >черный</option>
                                            <option value="черный+белый" >черный+белый</option>
                                            <option value="черный+желтый" >черный+желтый</option>
                                            <option value="черный+красный" >черный+красный</option>
                                            <option value="черный+оранжевый" >черный+оранжевый</option>
                                            <option value="черный+синий" >черный+синий</option>

                                        </select>
                                    </div>

                                <div class="mb-4">
                                        <label class="control-label" for="disk_manufacturer">Производитель</label>
                                        <select id="disk_manufacturer" v-model="disk_manufacturer" class="form-control">
                                            <option value="-1">не выбрано</option>
                                            <option value="AC SCHNITZER" >AC SCHNITZER</option>
                                            <option value="ACE" >ACE</option>
                                            <option value="ADORA" >ADORA</option>
                                            <option value="ADVAN" >ADVAN</option>
                                            <option value="ADVANTI" >ADVANTI</option>
                                            <option value="AEZ" >AEZ</option>
                                            <option value="AGFORGED" >AGFORGED</option>
                                            <option value="AITL" >AITL</option>
                                            <option value="AKITA" >AKITA</option>
                                            <option value="ALBA" >ALBA</option>
                                            <option value="ALCASTA" >ALCASTA</option>
                                            <option value="ALEKS" >ALEKS</option>
                                            <option value="ALESSIO" >ALESSIO</option>
                                            <option value="ALEX" >ALEX</option>
                                            <option value="ALLANTE" >ALLANTE</option>
                                            <option value="ALLIED WHEEL" >ALLIED WHEEL</option>
                                            <option value="ALLTECH" >ALLTECH</option>
                                            <option value="ALSTER" >ALSTER</option>
                                            <option value="ALUCHROM" >ALUCHROM</option>
                                            <option value="ALUTEC" >ALUTEC</option>
                                            <option value="AMATI" >AMATI</option>
                                            <option value="AMERICAN RACING" >AMERICAN RACING</option>
                                            <option value="ANTERA" >ANTERA</option>
                                            <option value="ANZIO WHEELS" >ANZIO WHEELS</option>
                                            <option value="APOLLO" >APOLLO</option>
                                            <option value="ARAYS" >ARAYS</option>
                                            <option value="ARCASTING" >ARCASTING</option>
                                            <option value="ARMY" >ARMY</option>
                                            <option value="ARTEC" >ARTEC</option>
                                            <option value="ASA WHEELS" >ASA WHEELS</option>
                                            <option value="ASANTI" >ASANTI</option>
                                            <option value="ASW" >ASW</option>
                                            <option value="ATP" >ATP</option>
                                            <option value="ATS" >ATS</option>
                                            <option value="ATS EXCLUSIVE LINE" >ATS EXCLUSIVE LINE</option>
                                            <option value="AUGUST" >AUGUST</option>
                                            <option value="AVARUS" >AVARUS</option>
                                            <option value="AVENUE" >AVENUE</option>
                                            <option value="AVT" >AVT</option>
                                            <option value="AVUS" >AVUS</option>
                                            <option value="AWS" >AWS</option>
                                            <option value="AZ" >AZ</option>
                                            <option value="AZEV" >AZEV</option>
                                            <option value="BACCARAT" >BACCARAT</option>
                                            <option value="BANTAJ" >BANTAJ</option>
                                            <option value="BANZAI" >BANZAI</option>
                                            <option value="BAOSH" >BAOSH</option>
                                            <option value="BARRACUDA" >BARRACUDA</option>
                                            <option value="BAZO" >BAZO</option>
                                            <option value="BBS" >BBS</option>
                                            <option value="BEYERN" >BEYERN</option>
                                            <option value="BINNO" >BINNO</option>
                                            <option value="BLACK RHINO" >BLACK RHINO</option>
                                            <option value="BLADE" >BLADE</option>
                                            <option value="BLAQUE" >BLAQUE</option>
                                            <option value="BLAQUE DIAMOND" >BLAQUE DIAMOND</option>
                                            <option value="BORBET" >BORBET</option>
                                            <option value="BRABUS" >BRABUS</option>
                                            <option value="BROCK" >BROCK</option>
                                            <option value="BSA" >BSA</option>
                                            <option value="BTS" >BTS</option>
                                            <option value="BWR" >BWR</option>
                                            <option value="CAM" >CAM</option>
                                            <option value="CARACTERE" >CARACTERE</option>
                                            <option value="CARRE" >CARRE</option>
                                            <option value="CARWEL" >CARWEL</option>
                                            <option value="CATTIVO" >CATTIVO</option>
                                            <option value="CATWILD" >CATWILD</option>
                                            <option value="CEC WHEELS" >CEC WHEELS</option>
                                            <option value="CMS" >CMS</option>
                                            <option value="COMPOMOTIVE" >COMPOMOTIVE</option>
                                            <option value="CORNICHE SPORTS WHEELS" >CORNICHE SPORTS WHEELS</option>
                                            <option value="COVENTRY" >COVENTRY</option>
                                            <option value="CRISTA" >CRISTA</option>
                                            <option value="CROMODORA" >CROMODORA</option>
                                            <option value="CROSS STREET" >CROSS STREET</option>
                                            <option value="CW FAHRZEUGTECHNIK" >CW FAHRZEUGTECHNIK</option>
                                            <option value="D&P" >D&P</option>
                                            <option value="DBL-G" >DBL-G</option>
                                            <option value="DBV" >DBV</option>
                                            <option value="DELTA DL" >DELTA DL</option>
                                            <option value="DELUXE WHEELS" >DELUXE WHEELS</option>
                                            <option value="DETATA" >DETATA</option>
                                            <option value="DEVINO" >DEVINO</option>
                                            <option value="DEZENT" >DEZENT</option>
                                            <option value="DIABLO WHEELS" >DIABLO WHEELS</option>
                                            <option value="DIAL" >DIAL</option>
                                            <option value="DIAMO" >DIAMO</option>
                                            <option value="DISLA" >DISLA</option>
                                            <option value="DJ WHEELS" >DJ WHEELS</option>
                                            <option value="DOTZ" >DOTZ</option>
                                            <option value="DRIV" >DRIV</option>
                                            <option value="DROPSTARS" >DROPSTARS</option>
                                            <option value="DVINCI" >DVINCI</option>
                                            <option value="ENKEI" >ENKEI</option>
                                            <option value="ENSURE" >ENSURE</option>
                                            <option value="ENZO" >ENZO</option>
                                            <option value="ETABETA" >ETABETA</option>
                                            <option value="EURODISK" >EURODISK</option>
                                            <option value="EUROSPORT" >EUROSPORT</option>
                                            <option value="F1" >F1</option>
                                            <option value="FALKEN" >FALKEN</option>
                                            <option value="FLY" >FLY</option>
                                            <option value="FONDMETAL" >FONDMETAL</option>
                                            <option value="FOR WHEELS" >FOR WHEELS</option>
                                            <option value="FORGIATO" >FORGIATO</option>
                                            <option value="FORSAGE" >FORSAGE</option>
                                            <option value="FR DESIGN" >FR DESIGN</option>
                                            <option value="FREEMOTION" >FREEMOTION</option>
                                            <option value="FUJIBOND" >FUJIBOND</option>
                                            <option value="FUTEK" >FUTEK</option>
                                            <option value="GIANELLE" >GIANELLE</option>
                                            <option value="GIANNA" >GIANNA</option>
                                            <option value="GIOVANNA" >GIOVANNA</option>
                                            <option value="GR" >GR</option>
                                            <option value="GRAM LIGHTS" >GRAM LIGHTS</option>
                                            <option value="GSI" >GSI</option>
                                            <option value="HAYES LEMMERZ" >HAYES LEMMERZ</option>
                                            <option value="HDS" >HDS</option>
                                            <option value="HELO" >HELO</option>
                                            <option value="HI-TECH" >HI-TECH</option>
                                            <option value="HIPNOTIC WHEELS" >HIPNOTIC WHEELS</option>
                                            <option value="IFREE" >IFREE</option>
                                            <option value="II CRAVE ALLOYS" >II CRAVE ALLOYS</option>
                                            <option value="IJITSU" >IJITSU</option>
                                            <option value="IKON WHEELS" >IKON WHEELS</option>
                                            <option value="INCUBUS" >INCUBUS</option>
                                            <option value="INCURVE WHEELS" >INCURVE WHEELS</option>
                                            <option value="INTRA-EXIP" >INTRA-EXIP</option>
                                            <option value="ITP" >ITP</option>
                                            <option value="IWHEELZ" >IWHEELZ</option>
                                            <option value="J&L RACING" >J&L RACING</option>
                                            <option value="JANTSA" >JANTSA</option>
                                            <option value="JD" >JD</option>
                                            <option value="JT" >JT</option>
                                            <option value="K&K" >K&K</option>
                                            <option value="KAHN" >KAHN</option>
                                            <option value="KESKIN TUNING" >KESKIN TUNING</option>
                                            <option value="KFZ" >KFZ</option>
                                            <option value="KMC" >KMC</option>
                                            <option value="KOKO KUTURE" >KOKO KUTURE</option>
                                            <option value="KONIG" >KONIG</option>
                                            <option value="KORMETAL" >KORMETAL</option>
                                            <option value="KOSEI" >KOSEI</option>
                                            <option value="KRONPRINZ" >KRONPRINZ</option>
                                            <option value="KWM" >KWM</option>
                                            <option value="KYOWA" >KYOWA</option>
                                            <option value="KYOWA RACING" >KYOWA RACING</option>
                                            <option value="L.A. CONNECTION" >L.A. CONNECTION</option>
                                            <option value="LAREX" >LAREX</option>
                                            <option value="LAWU" >LAWU</option>
                                            <option value="LEAGUE" >LEAGUE</option>
                                            <option value="LEGEARTIS" >LEGEARTIS</option>
                                            <option value="LENSO" >LENSO</option>
                                            <option value="LEXANI" >LEXANI</option>
                                            <option value="LF DICK" >LF DICK</option>
                                            <option value="LORENSO" >LORENSO</option>
                                            <option value="LORENZO" >LORENZO</option>
                                            <option value="LS WHEELS" >LS WHEELS</option>
                                            <option value="LUMARAI" >LUMARAI</option>
                                            <option value="M&K" >M&K</option>
                                            <option value="MAGLINE" >MAGLINE</option>
                                            <option value="MAGNETTO WHEELS" >MAGNETTO WHEELS</option>
                                            <option value="MAK" >MAK</option>
                                            <option value="MAMBA" >MAMBA</option>
                                            <option value="MAMBA OFF ROAD" >MAMBA OFF ROAD</option>
                                            <option value="MANDRUS" >MANDRUS</option>
                                            <option value="MARCELLO" >MARCELLO</option>
                                            <option value="MAXX WHEELS" >MAXX WHEELS</option>
                                            <option value="MAZZI" >MAZZI</option>
                                            <option value="MEFRO" >MEFRO</option>
                                            <option value="MI-TECH" >MI-TECH</option>
                                            <option value="MICKEY THOMPSON" >MICKEY THOMPSON</option>
                                            <option value="MIM" >MIM</option>
                                            <option value="MKW" >MKW</option>
                                            <option value="MKW OFF-ROAD" >MKW OFF-ROAD</option>
                                            <option value="MML" >MML</option>
                                            <option value="MODULAR SOCIETY" >MODULAR SOCIETY</option>
                                            <option value="MOMO" >MOMO</option>
                                            <option value="MONTE FIORE" >MONTE FIORE</option>
                                            <option value="MOTEGI RACING" >MOTEGI RACING</option>
                                            <option value="MOTO METAL" >MOTO METAL</option>
                                            <option value="MPW" >MPW</option>
                                            <option value="MS MOTORSPORTS" >MS MOTORSPORTS</option>
                                            <option value="MSW" >MSW</option>
                                            <option value="NAKAYAMA" >NAKAYAMA</option>
                                            <option value="NEXT" >NEXT</option>
                                            <option value="NINGBO" >NINGBO</option>
                                            <option value="NITRO" >NITRO</option>
                                            <option value="NOLOGO" >NOLOGO</option>
                                            <option value="NORDWAY" >NORDWAY</option>
                                            <option value="NP-WHEELS" >NP-WHEELS</option>
                                            <option value="NZ WHEELS" >NZ WHEELS</option>
                                            <option value="OFF-ROAD-WHEELS" >OFF-ROAD-WHEELS</option>
                                            <option value="OXIGIN" >OXIGIN</option>
                                            <option value="OZ RACING" >OZ RACING</option>
                                            <option value="P&W" >P&W</option>
                                            <option value="PANTHER" >PANTHER</option>
                                            <option value="PDW WHEELS" >PDW WHEELS</option>
                                            <option value="PLATINUM" >PLATINUM</option>
                                            <option value="PRIMO" >PRIMO</option>
                                            <option value="PRO COMP" >PRO COMP</option>
                                            <option value="PROMA" >PROMA</option>
                                            <option value="PTW" >PTW</option>
                                            <option value="R-STEEL" >R-STEEL</option>
                                            <option value="R-TEX" >R-TEX</option>
                                            <option value="RACE READY" >RACE READY</option>
                                            <option value="RACING SPARCO" >RACING SPARCO</option>
                                            <option value="RACING WHEELS" >RACING WHEELS</option>
                                            <option value="RADIUS" >RADIUS</option>
                                            <option value="RAPID" >RAPID</option>
                                            <option value="RC DESIGN" >RC DESIGN</option>
                                            <option value="RED WHEEL" >RED WHEEL</option>
                                            <option value="REDBOURNE" >REDBOURNE</option>
                                            <option value="REPLAY" >REPLAY</option>
                                            <option value="REPLICA" >REPLICA</option>
                                            <option value="REPLIKEY" >REPLIKEY</option>
                                            <option value="RIAL" >RIAL</option>
                                            <option value="ROMAGNA RUOTE" >ROMAGNA RUOTE</option>
                                            <option value="RONAL" >RONAL</option>
                                            <option value="RONDELL" >RONDELL</option>
                                            <option value="RONER" >RONER</option>
                                            <option value="ROSSO" >ROSSO</option>
                                            <option value="ROYAL WHEELS" >ROYAL WHEELS</option>
                                            <option value="RS WHEELS" >RS WHEELS</option>
                                            <option value="RUSSTEC" >RUSSTEC</option>
                                            <option value="SAKURA WHEELS" >SAKURA WHEELS</option>
                                            <option value="SANFOX" >SANFOX</option>
                                            <option value="SANT" >SANT</option>
                                            <option value="SCHMIDT" >SCHMIDT</option>
                                            <option value="SEBRING" >SEBRING</option>
                                            <option value="SEYEN" >SEYEN</option>
                                            <option value="SH" >SH</option>
                                            <option value="SHARK" >SHARK</option>
                                            <option value="SL" >SL</option>
                                            <option value="SLIK" >SLIK</option>
                                            <option value="SMC" >SMC</option>
                                            <option value="SODI WHEELS" >SODI WHEELS</option>
                                            <option value="SPARCO WHEELS" >SPARCO WHEELS</option>
                                            <option value="SPEEDLINE CORSE" >SPEEDLINE CORSE</option>
                                            <option value="SPORTMAX RACING" >SPORTMAX RACING</option>
                                            <option value="SPORTWAY" >SPORTWAY</option>
                                            <option value="SRD TUNING" >SRD TUNING</option>
                                            <option value="SSW" >SSW</option>
                                            <option value="STARK" >STARK</option>
                                            <option value="STEEL WHEELS" >STEEL WHEELS</option>
                                            <option value="STILAUTO" >STILAUTO</option>
                                            <option value="STONEWELL" >STONEWELL</option>
                                            <option value="STORM WHEELS" >STORM WHEELS</option>
                                            <option value="STRUT" >STRUT</option>
                                            <option value="STYL" >STYL</option>
                                            <option value="SW" >SW</option>
                                            <option value="SWIGER" >SWIGER</option>
                                            <option value="SWORD" >SWORD</option>
                                            <option value="TAILONG" >TAILONG</option>
                                            <option value="TANSY WHEELS" >TANSY WHEELS</option>
                                            <option value="TEAM DYNAMICS" >TEAM DYNAMICS</option>
                                            <option value="TECH-LINE" >TECH-LINE</option>
                                            <option value="TEZZEN" >TEZZEN</option>
                                            <option value="TGR" >TGR</option>
                                            <option value="TGRACING" >TGRACING</option>
                                            <option value="TIS" >TIS</option>
                                            <option value="TOMASON" >TOMASON</option>
                                            <option value="TOORA" >TOORA</option>
                                            <option value="TR DESIGN" >TR DESIGN</option>
                                            <option value="TREBL" >TREBL</option>
                                            <option value="TRW" >TRW</option>
                                            <option value="TSW" >TSW</option>
                                            <option value="TUNZZO" >TUNZZO</option>
                                            <option value="U.S. WHEEL" >U.S. WHEEL</option>
                                            <option value="URBAN RACING" >URBAN RACING</option>
                                            <option value="VALBREM" >VALBREM</option>
                                            <option value="VALENTE" >VALENTE</option>
                                            <option value="VAULT" >VAULT</option>
                                            <option value="VCT" >VCT</option>
                                            <option value="VELOCHE" >VELOCHE</option>
                                            <option value="VERDE" >VERDE</option>
                                            <option value="VERTINI" >VERTINI</option>
                                            <option value="VIANOR" >VIANOR</option>
                                            <option value="VICTOR EQUIPMENT" >VICTOR EQUIPMENT</option>
                                            <option value="VINDETA" >VINDETA</option>
                                            <option value="VMR WHEELS" >VMR WHEELS</option>
                                            <option value="VOLK RACING" >VOLK RACING</option>
                                            <option value="VOLVO" >VOLVO</option>
                                            <option value="VORXTEC" >VORXTEC</option>
                                            <option value="WALPAX" >WALPAX</option>
                                            <option value="WHEELWORLD" >WHEELWORLD</option>
                                            <option value="WIGER" >WIGER</option>
                                            <option value="WM" >WM</option>
                                            <option value="WOLF" >WOLF</option>
                                            <option value="WOLF WHEELS" >WOLF WHEELS</option>
                                            <option value="WORK" >WORK</option>
                                            <option value="WRC" >WRC</option>
                                            <option value="X'TRIKE" >X'TRIKE</option>
                                            <option value="X7" >X7</option>
                                            <option value="XD SERIES" >XD SERIES</option>
                                            <option value="XINFA" >XINFA</option>
                                            <option value="XXIO" >XXIO</option>
                                            <option value="YAMATO" >YAMATO</option>
                                            <option value="YOKATTA" >YOKATTA</option>
                                            <option value="YOKATTA RAYS" >YOKATTA RAYS</option>
                                            <option value="YOKOHAMA" >YOKOHAMA</option>
                                            <option value="YST" >YST</option>
                                            <option value="YUELING WHEELS" >YUELING WHEELS</option>
                                            <option value="ZD WHEELS" >ZD WHEELS</option>
                                            <option value="ZENT" >ZENT</option>
                                            <option value="ZEPP" >ZEPP</option>
                                            <option value="ZINIK" >ZINIK</option>
                                            <option value="ZORAT WHEELS" >ZORAT WHEELS</option>
                                            <option value="ZORMER" >ZORMER</option>
                                            <option value="ZRACING" >ZRACING</option>
                                            <option value="ZUMBO WHEELS" >ZUMBO WHEELS</option>
                                            <option value="ВИКОМ" >ВИКОМ</option>
                                            <option value="ВСМПО" >ВСМПО</option>
                                            <option value="ГАЗ" >ГАЗ</option>
                                            <option value="ИЖМАШ" >ИЖМАШ</option>
                                            <option value="КАМАЗАВТОТЕХНИКА" >КАМАЗАВТОТЕХНИКА</option>
                                            <option value="КРАМЗ" >КРАМЗ</option>
                                            <option value="КРЕМЕНЧУГСКИЙ КОЛЁСНЫЙ ЗАВОД" >КРЕМЕНЧУГСКИЙ КОЛЁСНЫЙ ЗАВОД</option>
                                            <option value="КУЛЗ" >КУЛЗ</option>
                                            <option value="КУМЗ" >КУМЗ</option>
                                            <option value="МАГАЛТЕК" >МАГАЛТЕК</option>
                                            <option value="МЕГАЛЮМ" >МЕГАЛЮМ</option>
                                            <option value="СКАД" >СКАД</option>
                                            <option value="СМЗ" >СМЗ</option>
                                            <option value="СМК" >СМК</option>
                                            <option value="СТОКРАТ" >СТОКРАТ</option>
                                            <option value="ТЗСК" >ТЗСК</option>
                                            <option value="4 RACING" >4 RACING</option>
                                            <option value="4GO" >4GO</option>
                                            <option value="57 MOTORSPORT" >57 MOTORSPORT</option>

                                        </select>
                                    </div>

                            </div>
                        </div>

                        <div class="row justify-content-center ml-auto mr-auto">
                            <button class="chopcafe_btn form_btn ml-auto mr-auto" v-on:click="sendDisksRequest()">Отправить</button>
                        </div>

                    </tab>
                    <tab name="История запросов" v-if="vinrequests.length>0">
                        <history title="История запросов"
                             :columns="columns"
                             :rows="vinrequests">
                            <th slot="thead-tr">
                                Действия
                            </th>
                            <template slot="tbody-tr" slot-scope="props">
                                <td>
                                    <button class="btn"
                                            @click="deleteItem(props.row)">
                                        <i class="material-icons white-text">delete</i>
                                    </button>
<!--                                    <button class="btn"-->
<!--                                            @click="deleteItem(props.row)">-->
<!--                                        <i class="fas fa-eye white-text"></i>-->
<!--                                    </button>-->
                                </td>
                            </template>
                        </history>
                    </tab>
                </tabs>
            </transition>
        </div>
</template>
<script>

    export default {
    data: () => ({
        phone:'',
        name: '',
        user_id:'',
        phone_step: true,
        name_step: false,
        vin_step: false,
        // vinrequest_created:false,
        vincode:'',
        year: '',
        month: '',
        category: '',
        mark: '',
        model: '',
        volume: '',
        power: '',
        additional_info: '',
        parts_info: '',
        years:[],
        months: [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        cylinders:'',
        engine_type:'',
        valves:'',
        body_type:'',
        number_of_doors:'',
        drive:'',
        steering_wheel:'',
        equipment:[],
        gearbox_type:'',
        gearbox_number:'',
        // vinrequests:[],

        vinrequest_id:'',
        tyre_width:'',
        tyre_height:'',
        tyre_diameter:'',
        tyre_type:'',
        tyre_manufacturer:'',
        seasonality:'',

        disk_vinrequest_id:'',
        disc_width:'',
        disk_diameter:'',
        radius:'',
        holes:'',
        pcd:'',
        dco:'',
        type:'',
        color:'',
        disk_manufacturer:'',

        perPage: ['10', '25', '50'],
        columns: [
            {
                label: 'Дата',
                field: 'created_at',
            },
            {
                label: 'VIN-код',
                field: 'vincode',
            },
            // {
            //     label: 'Запросов на шины',
            //     field: this.tyres.length,
            // },
            // {
            //     label: 'Запросов на диски',
            //     field: this.disks.length,
            // },

        ]


    }),
    mounted() {
        this.$store.dispatch('getCategories');
        this.$store.dispatch('getFuelType');
        var date = new Date();
        var year = date.getFullYear();
        for (let i = year; i>1982; i--) {
            this.years.push(i);
        }
        // this.$store.dispatch('getBodyStyles', 1);

    },
    computed:
    {
        categories() {
            return this.$store.getters.categories;
        },
        bodyStyles() {
            return this.$store.getters.bodyStyles;
        },
        marks() {
            return this.$store.getters.marks;
        },
        models() {
            return this.$store.getters.models;
        },
        gearboxes() {
            return this.$store.getters.gearboxes;
        },
        driverType() {
            return this.$store.getters.driverType;
        },
        fuelType() {
            return this.$store.getters.fuelType
        },
        autoOptions() {
            return this.$store.getters.autoOptions;
        },
        status() {
            return this.$store.getters.status;
        },
        vinrequests() {
            return this.$store.getters.vinrequests;
        },
        vincodes() {
            return this.$store.getters.vincodes;
        },
        vin_status() {
            return this.$store.getters.vin_status;
        },
        tyres() {
            return this.$store.getters.tyres;
        },
        disks() {
            return this.$store.getters.disks;
        },
    },
    methods: {
        async checkPhone() {
            if(this.phone == ''){
                var message = 'Введите номер телефона'
                this.sendMessage(message);
            }
            else {
                const resp = await axios.get('checkPhone/' + this.phone)
                    .then((response) => {
                        this.user_id = response.data.user_id;
                        if (response.data.status == 'old_user') {
                            this.phone_step = false
                            this.name_step = false
                            this.vin_step = true

                            // this.$store.dispatch('setVinCodes', response.data.);
                            this.$store.dispatch('getVinRequests', this.user_id);

                        } else {
                            this.phone_step = false
                            this.name_step = true
                            this.vin_step = false
                        }
                    })
            }
        },
        async addName() {
            if(this.name == ''){
                var message = 'Введите ваше имя';
                var type = 'error';;
                this.sendMessage(message,type);
            }
            else {
                const resp = await axios.post('addName', {name: this.name, phone: this.phone})
                    .then((response) => {
                        if (response.data.status == 'success') {
                            this.phone_step = false
                            this.name_step = false
                            this.vin_step = true
                        } else {
                            this.phone_step = true
                            this.name_step = false
                            this.vin_step = false
                            var message = 'Произошла ошибка. Попробуйте снова.';
                            var type = 'error';
                            this.sendMessage(message,type)
                        }
                    })
            }
        },
        sendVinRequest() {
            var data = {
                user_id: this.user_id,
                vincode:this.vincode,
                year: this.year,
                month: this.month,
                category: this.category.name,
                carmake: this.mark.name,
                model: this.model.name,
                volume: this.volume,
                power: this.power,
                additional_info: this.additional_info,
                parts_info: this.parts_info,
                cylinders:this.cylinders,
                engine_type:this.engine_type,
                valves:this.valves,
                body_type:this.body_type.name,
                number_of_doors:this.number_of_doors,
                drive:this.drive.name,
                steering_wheel:this.steering_wheel,
                equipment:this.equipment.toString(),
                gearbox_type: this.gearbox_type.name,
                gearbox_number:this.gearbox_number,
            };
            try {
                if(this.vincode != ''&& this.parts_info !='') {
                    this.$store.dispatch('addVinRequest', data);
                    // this.$store.dispatch('addVinCode', this.vincode);
                    axios.post('sendVinRequest', data)
                        .then((response) => {
                                if (response.data.status == 'success') {
                                    // this.vin_step = false
                                    // this.vinrequest_created = true;
                                    var message = 'Ваш запрос успешно отправлен';
                                    var type = 'success';
                                    this.sendMessage(message, type);

                                    this.vincode = '';
                                    this.year = '';
                                    this.month = '';
                                    this.category = '';
                                    this.mark= '';
                                    this.model= '';
                                    this.volume = '';
                                    this.power = '';
                                    this.additional_info = '';
                                    this.parts_info = '';
                                    this.cylinders = '';
                                    this.engine_type = '';
                                    this.valves = '';
                                    this.body_type = '';
                                    this.number_of_doors = '';
                                    this.drive = '';
                                    this.steering_wheel = '';
                                    this.equipment = [];
                                    this.gearbox_type = '';
                                    this.gearbox_number = '';
                                } else {
                                    console.log(this.vin_status)
                                    var message = 'Произошла ошибка. Попробуйте снова.';
                                    var type = 'error';
                                    this.sendMessage(message, type)
                                }
                            })

                } else {
                    var message = 'Произошла ошибка. Попробуйте снова. Убедитесь, что заполнили все обязательные поля.';
                    var type = 'error';
                    this.sendMessage(message, type)
                }

            }
            catch (e) {
                var message = 'Произошла ошибка. Попробуйте снова.';
                var type = 'error';
                this.sendMessage(message, type)
            }
        },
        async sendTyresRequest() {
            var data = {
                user_id: this.user_id,
                vincode: this.vinrequest_id.vincode,
                vinrequest_id: this.vinrequest_id.id,
                tyre_width: this.tyre_width,
                tyre_height: this.tyre_height,
                diameter: this.tyre_diameter,
                tyre_type: this.tyre_type,
                manufacturer: this.tyre_manufacturer,
                seasonality: this.seasonality,
            };
            this.$store.dispatch('addTyresRequest', data);
            const resp = await axios.post('sendTyresRequest', data)
                .then((response) => {
                    if (response.data.status === 'success') {
                        var message = 'Запрос принят. Ожидайте нашего звонка.'
                        var type = 'success'
                        this.sendMessage(message, type)
                    }
                })
        },
        async sendDisksRequest() {
            var data = {
                user_id: this.user_id,
                vincode: this.vinrequest_id.vincode,
                vinrequest_id: this.vinrequest_id.id,
                disc_width: this.disc_width,
                diameter: this.disk_diameter,
                radius: this.radius,
                holes: this.holes,
                pcd: this.pcd,
                dco: this.dco,
                type: this. type,
                color: this.color,
                manufacturer: this.disk_manufacturer,
            };
            this.$store.dispatch('addDisksRequest', data);
            const resp = await axios.post('sendDisksRequest', data)
                .then((response) => {
                    if (response.data.status === 'success') {
                        var message = 'Запрос принят. Ожидайте нашего звонка.'
                        var type = 'success'
                        this.sendMessage(message, type)
                    }
                })
        },
        sendMessage(message, type) {
            this.$notify({
                group: 'info',
                type: type,
                title: 'Оповещение АВТОДОН',
                text: message
            });
        },
        changeCategory() {
            // this.bodyStyle;
            this.mark = '';
            this.model='';
            this.$store.dispatch('getBodyStyles', this.category.value);
            this.$store.dispatch('getMarks', this.category.value);
            // this.$store.dispatch('getModels', category);
            this.$store.dispatch('getGearboxes', this.category.value);
            this.$store.dispatch('getDriverType', this.category.value);
            this.$store.dispatch('getAutoOptions', this.category.value);
        },
        changeMark() {
            this.model='';
            var payload = {
                category_id: this.category.value,
                mark_id: this.mark.value,
            }
            this.$store.dispatch('getModels', payload);
        },
        deleteItem(row) {
            // this.vinrequests.splice(this.vinrequests.indexOf(row), 1);
            this.$store.dispatch('deleteVinRequest', row);
        }
    },

  }
</script>
<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
        opacity: 0;
    }
    .input-icon{
        position: absolute;
        right: 35px;
        top: 18px;
        color:#dc3545;
    }
    /*.column {*/
    /*    display: block;*/
    /*}*/
    @media (max-width: 768px) {
        .column{
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }
</style>