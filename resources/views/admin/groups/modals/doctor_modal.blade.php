<div class="modal fade" id="doctor_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{__('Create Doctor')}}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="{{route('ajax.create_doctor')}}" method="POST" id="create_doctor">
            @csrf
            <div class="text-danger" id="doctor_modal_error"></div>
            <div class="modal-body" id="create_doctor_inputs">
                <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                       <div class="input-group mb-3">
                           <div class="input-group-prepend">
                             <span class="input-group-text" id="basic-addon1">
                                 <i class="fa fa-user"></i>
                             </span>
                           </div>
                           <input type="text" class="form-control" placeholder="{{__('Doctor Name')}}" name="name" id="name" required>
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
                               <input type="email" class="form-control" placeholder="{{__('Email Address')}}" name="email" id="email">
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
                             <input type="text" class="form-control" placeholder="{{__('Phone number')}}" name="phone" id="phone">
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
                                   <input type="text" class="form-control" placeholder="{{__('Address')}}" name="address" id="address">
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
                                     <i class="fas fa-percentage"></i>
                                   </span>
                                 </div>
                                 <input type="number" class="form-control" placeholder="{{__('Commission')}}" name="commission" id="commission" min="0" max="100" required>
                             </div>
                         </div>
                     </div>
                   </div>

                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>