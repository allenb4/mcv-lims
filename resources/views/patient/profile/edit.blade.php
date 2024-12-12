@extends('layouts.app')

@section('title')
{{__('Profile')}}
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{__('Profile')}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('patient.index')}}">{{__('Home')}}</a></li>
          <li class="breadcrumb-item active">{{__('Profile')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">{{__('Edit Profile')}}</h3>
  </div>
  <!-- /.card-header -->
  <form method="POST" action="{{route('patient.profile.update')}}" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
      <div class="row">

    
        <div class="col-lg-2">
    
            <div class="card card-primary">
                <div class="card-header">
                    <h5 class="text-center m-0 p-0">
                        {{__('Avatar')}}
                    </h5>
                </div>
                <div class="card-footer m-0 p-0 pt-3">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar" class="custom-file-input" id="avatar">
                                    <label class="custom-file-label">{{__('Choose avatar')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body m-0 p-0">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <a href="@if(!empty($patient['avatar'])){{url('uploads/patient-avatar/'.$patient['avatar'])}}@else{{url('img/avatar.png')}}@endif" data-toggle="lightbox" data-title="Avatar">
                                <img src="@if(!empty($patient['avatar'])){{url('uploads/patient-avatar/'.$patient['avatar'])}}@else{{url('img/avatar.png')}}@endif"  class="img-thumbnail" id="patient_avatar" alt="">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger btn-sm float-right" id="delete_avatar" style="width:100%">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                
            </div>
    
        </div>
    
        <div class="col-lg-10">
    
            <div class="row">
    
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa fa-user"></i>
                            </span>
                            </div>
                            <input type="text" class="form-control" placeholder="{{__('Patient Name')}}" name="name" id="name" @if(isset($patient)) value="{{$patient->name}}" @elseif(old('name')) value="{{old('name')}}" @endif required>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-globe"></i>
                                </span>
                                </div>
                                <select class="form-control" name="country_id" id="country_id">
                                    <option value="" disabled selected>{{__('Select nationality')}}</option>
                                    @if(isset($patient)&&isset($patient['country']))
                                        <option value="{{$patient['country_id']}}" selected>{{$patient['country']['nationality']}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4">
                    <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-id-card"></i>
                        </span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{__('National ID')}}" name="national_id" id="national_id" @if(isset($patient)) value="{{$patient->national_id}}" @elseif(old('national_id')) value="{{old('national_id')}}" @endif>
                    </div>
                    </div>
                </div>
    
                <div class="col-lg-4">
                    <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-passport"></i>
                        </span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{__('Passport no')}}" name="passport_no" id="passport_no" @if(isset($patient)) value="{{$patient->passport_no}}" @elseif(old('passport_no')) value="{{old('passport_no')}}" @endif>
                    </div>
                    </div>
                </div>
    
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fas fa-envelope"></i>
                            </span>
                            </div>
                            <input type="email" class="form-control" placeholder="{{__('Email Address')}}" name="email" id="email" @if(isset($patient)) value="{{$patient->email}}" @elseif(old('email')) value="{{old('email')}}" @endif>
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
                            <input type="text" class="form-control" placeholder="{{__('Phone number')}}" name="phone" id="phone"  @if(isset($patient)) value="{{$patient->phone}}" @elseif(old('phone')) value="{{old('phone')}}" @endif>
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
                                <select class="form-control" name="gender" placeholder="{{__('Gender')}}" id="gender" required>
                                    <option value="" disabled selected>{{__('Select Gender')}}</option>
                                    <option value="male"  @if(isset($patient)&&$patient['gender']=='male') selected @elseif(old('gender')=='male') selected  @endif>{{__('Male')}}</option>
                                    <option value="female"  @if(isset($patient)&&$patient['gender']=='female') selected @elseif(old('gender')=='female') selected @endif>{{__('Female')}}</option>
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
                                    <i class="fas fa-baby"></i>
                                </span>
                                </div>
                                <input type="text" class="form-control datepicker" placeholder="{{__('Date of birth')}}" name="dob" id="dob" required @if(isset($patient)) value="{{$patient['dob']}}" @elseif(old('dob')) value="{{old('dob')}}" @endif readonly>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6 pr-0">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-baby"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" name="age" id="age" placeholder="{{__('Age')}}" @if(!isset($patient)&&old('age')) value="{{old('age')}}" @endif required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6 col-md-6 pl-0">
                            <div class="input-group mb-3">
                                <select class="form-control" name="age_unit" id="age_unit" required>
                                    <option value="" disabled selected>{{__('Select age unit')}}</option>
                                    <option value="years" @if(!isset($patient)&&old('age_unit')=='years') selected @endif>{{__('Years')}}</option>
                                    <option value="months" @if(!isset($patient)&&old('age_unit')=='months') selected @endif>{{__('Months')}}</option>
                                    <option value="days" @if(!isset($patient)&&old('age_unit')=='days') selected @endif>{{__('Days')}}</option>
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
                                <input type="text" class="form-control" placeholder="{{__('Address')}}" name="address" id="address" @if(isset($patient)) value="{{$patient->address}}" @elseif(old('address')) value="{{old('address')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    
        </div>
    
    </div>
    
    

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">
        <i class="fa fa-check"></i> {{__('Save')}}
      </button>
    </div>
  </form>

</div>

@endsection
@section('scripts')
  <script src="{{url('js/patient/profile.js')}}"></script>
@endsection