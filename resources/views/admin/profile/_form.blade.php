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
                        <a href="@if(!empty(auth()->guard('admin')->user()->avatar)){{url('uploads/user-avatar/'.auth()->guard('admin')->user()->avatar)}}@else{{url('img/avatar.png')}}@endif" data-toggle="lightbox" data-title="Avatar">
                            <img src="@if(!empty(auth()->guard('admin')->user()->avatar)){{url('uploads/user-avatar/'.auth()->guard('admin')->user()->avatar)}}@else{{url('img/avatar.png')}}@endif"  class="img-thumbnail" id="patient_avatar" alt="">
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
            <div class="col-lg-12">
                <div class="input-group form-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          <i class="fa fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="{{__('Name')}}" name="name" value="{{auth()->guard('admin')->user()->name}}" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group form-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control" placeholder="{{__('Email Address')}}" name="email" value="{{auth()->guard('admin')->user()->email}}"  required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group form-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                            <i class="fa fa-key" aria-hidden="true"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="{{__('Password')}}" name="password" id="password">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group form-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                            <i class="fa fa-key" aria-hidden="true"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="{{__('Password Confirmation')}}" name="password_confirmation" id="password_confirmation">
                </div>
            </div>
        </div>
        
        @can('sign_medical_report')
        <div class="row">
            <div class="col-lg-10">
                    
                <div class="input-group form-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                              <i class="fas fa-signature" aria-hidden="true"></i>
                        </span>
                      </div>
                    <div class="custom-file">
                        <input type="file" accept="image/*" class="custom-file-input" id="exampleInputFile" name="signature">
                        <label class="custom-file-label" for="exampleInputFile">{{__('Choose your signature')}}</label>
                    </div>
                </div>
                    
            </div>
            <div class="col-lg-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5 class="card-title" style="text-align: center!important;float: unset;">
                            {{__('Signature')}}
                        </h5>
                    </div>
                    <div class="card-body p-1">
                        <a href="@if(!empty(auth()->guard('admin')->user()->signature)){{url('uploads/signature/'.auth()->guard('admin')->user()->signature)}}@else{{url('img/no-image.png')}}@endif" data-toggle="lightbox" data-title="Signature">
                            <img src="@if(!empty(auth()->guard('admin')->user()->signature)){{url('uploads/signature/'.auth()->guard('admin')->user()->signature)}}@else{{url('img/no-image.png')}}@endif"  class="img-thumbnail" id="user_signature" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>



