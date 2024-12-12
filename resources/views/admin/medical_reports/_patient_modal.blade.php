
<!-- Modal patient information -->
<div class="modal fade" id="patient_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Patient info') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h5 class="text-center m-0 p-0">
                                    {{__('Avatar')}}
                                </h5>
                            </div>
                            <div class="card-body m-0 p-0">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <a href="@if(isset($group)&&!empty($group['patient']['avatar'])){{url('uploads/patient-avatar/'.$group['patient']['avatar'])}}@else{{url('img/avatar.png')}}@endif" data-toggle="lightbox" data-title="Avatar">
                                            <img src="@if(isset($group)&&!empty($group['patient']['avatar'])){{url('uploads/patient-avatar/'.$group['patient']['avatar'])}}@else{{url('img/avatar.png')}}@endif"  class="img-thumbnail" id="patient_avatar" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <ul class="p-0 list-style-none">
                            <li>
                                <h5>
                                    <b>{{__('Name')}} : </b> {{$group['patient']['name']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Nationality')}} : </b> {{$group['patient']['country']['nationality']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('National ID')}} : </b> {{$group['patient']['national_id']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Passport No.')}} : </b> {{$group['patient']['passport_no']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Gender')}} : </b> {{__($group['patient']['gender'])}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Date of birth')}} : </b> {{$group['patient']['dob']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Age')}} : </b> {{$group['patient']['age']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Phone')}} : </b> {{$group['patient']['phone']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Email')}} : </b> {{$group['patient']['email']}}
                                </h5>
                            </li>
                            <li>
                                <h5>
                                    <b>{{__('Address')}} : </b> {{$group['patient']['address']}}
                                </h5>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- \Modal patient information -->
