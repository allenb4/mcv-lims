    <!-- Patient details -->
    <div class="row patient_info">

        <div class="col-lg-12">
            <div class="form-group">
                <label for="patient_id">{{__('Select Patient')}}</label>
                @can('create_patient')
                    <button type="button" class="btn btn-warning btn-sm add_patient float-right"  data-toggle="modal" data-target="#patient_modal">
                        <i class="fa fa-exclamation-triangle"></i>  {{__('Not Listed ?')}}
                    </button>
                @endcan
                <select id="patient_id" name="patient_id" class="form-control" required>
                    @if(isset($visit)&&isset($visit['patient']))
                        <option value="{{$visit['patient']['id']}}" selected>{{$visit['patient']['name']}}</option>
                    @endif
                </select>
            </div>
        </div>            

        <div class="col-lg-4">
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-user"></i>
                    </span>
                    </div>
                    <input type="text" class="form-control" placeholder="{{__('Patient Name')}}" name="name" id="name" @if(isset($visit)&&isset($visit['patient'])) value="{{$visit['patient']['name']}}"  @endif disabled required>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-phone-alt"></i>
                    </span>
                    </div>
                    <input type="text" class="form-control" placeholder="{{__('Phone number')}}" name="phone" id="phone" @if(isset($visit)&&isset($visit['patient'])) value="{{$visit['patient']['phone']}}"  @endif disabled required>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-mars"></i>
                        </span>
                        </div>
                        <select class="form-control" name="gender" placeholder="{{__('Gender')}}" id="gender" @if(isset($visit)&&isset($visit['patient']))  @endif disabled required>
                            <option value="" disabled selected>{{__('Select Gender')}}</option>
                            <option value="male"  @if(isset($visit)&&$visit['patient']['gender']=='male') selected @endif>{{__('Male')}}</option>
                            <option value="female"  @if(isset($visit)&&$visit['patient']['gender']=='female') selected @endif>{{__('Female')}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{__('Address')}}" name="address" id="address" @if(isset($visit)&&isset($visit['patient'])) value="{{$visit['patient']['address']}}"  @endif disabled required>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="form-group">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-envelope"></i>
                        </span>
                        </div>
                        <input type="email" class="form-control" id="email" placeholder="{{__('Email')}}" name="email" required @if(isset($visit)&&isset($visit['patient'])) value="{{$visit['patient']['email']}}"  @endif disabled>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-baby"></i>
                        </span>
                        </div>
                        <input type="text" class="form-control datepicker" id="dob" placeholder="{{__('Date of birth')}}" name="dob" required @if(isset($visit)&&isset($visit['patient'])) value="{{$visit['patient']['dob']}}"  @endif style="z-index: 1000!important" disabled>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- \Patient details -->

    <!-- Visit details -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h5 class="card-title">
                        {{__('Visit details')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        </div>
                                        <input type="text" class="form-control flatpickr" id="visit_date" placeholder="{{__('Visit Date')}}" name="visit_date" required @if(isset($visit)) value="{{$visit['visit_date']}}" @endif style="z-index: 1000!important" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        </div>
                                        <input type="text" class="form-control" id="visit_date" placeholder="{{__('Visit address')}}" name="visit_address" @if(isset($visit)) value="{{$visit['visit_address']}}" @endif required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="tests">{{__('Tests')}}</label>
                                <select name="tests[]" id="select_tests" class="form-control" multiple>
                                    @if(isset($visit))
                                        @foreach($visit['tests'] as $test)
                                            <option value="{{$test['testable_id']}}" selected>{{$test['test']['name']}}</option>
                                        @endforeach 
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="cultures">{{__('Cultures')}}</label>
                                <select name="cultures[]" id="select_cultures" class="form-control" multiple>
                                    @if(isset($visit))
                                        @foreach($visit['cultures'] as $culture)
                                            <option value="{{$culture['testable_id']}}" selected>{{$culture['culture']['name']}}</option>
                                        @endforeach 
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="packages">{{__('Packages')}}</label>
                                <select name="packages[]" id="select_packages" class="form-control" multiple>
                                    @if(isset($visit))
                                        @foreach($visit['packages'] as $package)
                                            <option value="{{$package['testable_id']}}" selected>{{$package['package']['name']}}</option>
                                        @endforeach 
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- \Visit details -->

    <!-- Location -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h5 class="card-title">
                        <i  class="fas fa-map-marked-alt nav-icon"></i>
                        {{__('Location on map')}}
                    </h5>
                </div>
                
                <div class="card-body p-0">
                    <input
                    id="pac-input"
                    class="form-control"
                    type="text"
                    placeholder="{{__('Search for address')}}"
                    class="form-control"
                    />
                    <input type="hidden" name="lat" id="visit_lat" @if(isset($visit)) value="{{$visit['lat']}}" @endif>
                    <input type="hidden" name="lng" id="visit_lng" @if(isset($visit)) value="{{$visit['lng']}}" @endif>
                    <input type="hidden" name="zoom_level" id="zoom_level" @if(isset($visit)) value="{{$visit['zoom_level']}}" @endif>
                    <div id="map" style="min-height:500px"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- \Location -->

    <!-- Attachment -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h5 class="card-title">
                        <i  class="fas fa-file-pdf nav-icon"></i>
                        {{__('Attachment')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputFile">
                            {{__('Attachment Image')}} ({{__('optional')}})
                            @if(!empty($visit)&&!empty($visit['attach']))
                                <a href="{{url('uploads/visits/'.$visit['attach'])}}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="attach" accept="image/*" class="custom-file-input" id="attachment">
                                <label class="custom-file-label" for="attachment">{{__('Choose file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- \Attachment -->
    
