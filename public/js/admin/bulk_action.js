var token=$('input[name=_token]').val();

(function($){

    "use strict";

    if(table!==undefined&&table!=='')
    {        
        //check all
        $(document).on('change','.check_all',function(){
            var checked=$(this).prop('checked');
            $('.bulk_checkbox').prop('checked',checked).trigger('change');
        });  

        table.on( 'draw', function () {
            $('.check_all').prop('checked',false).trigger('change');
        });

        table.on( 'search', function () {
            $('.check_all').prop('checked',false).trigger('change');
        });

        table.on('init',function(){
            var route=location.href.split('admin');
            var action=route[route.length-1];
            action=action.split('/');
            action=action[action.length-1];
            var addition_actions=``;

            if(action=='groups')
            {
                if(can_view)
                {
                    addition_actions=`
                    <a href="`+url('admin/'+action)+`/bulk/print_barcode" class="bulk_button dropdown-item" warning_message="`+trans('Are you sure to print bulk barcodes ?')+`">
                        <i class="fa fa-barcode"></i>
                        `+trans('Print barcode')+`
                    </a>
                    <a href="`+url('admin/'+action)+`/bulk/print_receipt" class="bulk_button dropdown-item" warning_message="`+trans('Are you sure to print bulk receipts ?')+`">
                        <i class="fa fa-receipt"></i>
                        `+trans('Print receipt')+`
                    </a>
                    <a href="`+url('admin/'+action)+`/bulk/print_working_paper" class="bulk_button dropdown-item" warning_message="`+trans('Are you sure to print bulk working paper ?')+`">
                        <i class="fas fa-file-word"></i>
                        `+trans('Print working paper')+`
                    </a>
                    <a href="`+url('admin/'+action)+`/bulk/send_receipt_mail" class="bulk_button dropdown-item" warning_message="`+trans('Are you sure to send bulk receipts ?')+`">
                        <i class="fas fa-envelope"></i>
                        `+trans('Send receipt')+`
                    </a>
                    `;
                }
                
            }
            else if(action=='medical_reports')
            {
                if(can_view)
                {
                    addition_actions=`
                    <a href="`+url('admin/'+action)+`/bulk/print_barcode" class="bulk_button dropdown-item" warning_message="`+trans('Are you sure to print bulk barcodes ?')+`">
                        <i class="fa fa-barcode"></i>
                        `+trans('Print barcode')+`
                    </a>
                    <a href="`+url('admin/'+action)+`/bulk/sign_report" class="bulk_button dropdown-item" warning_message="`+trans('Are you sure to sign bulk reports ?')+`">
                        <i class="fas fa-signature"></i>
                        `+trans('Sign report')+`
                    </a>
                    <a href="`+url('admin/'+action)+`/bulk/print_report" class="bulk_button dropdown-item"  warning_message="`+trans('Are you sure to print bulk reports ?')+`">
                        <i class="fa fa-print"></i>
                        `+trans('Print report')+`
                    </a>
                    <a href="`+url('admin/'+action)+`/bulk/send_report_mail" class="bulk_button dropdown-item"  warning_message="`+trans('Are you sure to send bulk reports ?')+`">
                        <i class="fa fa-envelope"></i>
                        `+trans('Send report')+`
                    </a>
                    `;
                }
            }

            var delete_action=``;
            if(can_delete)
            {
                delete_action=`
                <a href="`+url('admin/'+action)+`/bulk/delete" class="bulk_button dropdown-item text-danger" id="delete_bulk" warning_message="`+trans('Are you sure to delete bulk ?')+`">
                    <i class="fa fa-trash"></i>
                    `+trans('Delete')+`
                </a>
                `;
            }
            
            $('.table').parent('.col-sm-12').prepend(`
                <div class="row" id="bulk_action_section">
                        <div class="col-lg-12">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle bulk_action_dropdown" disabled type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cog"></i>
                                    `+trans('Bulk action')+`
                                </button>
                                
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                
                                `+addition_actions+`

                                `+delete_action+`
                                
                            </div>
                        </div>
                        <form action="" class="d-none" id="bulk_form" method="POST">
                            <input type="hidden" name="_token" value="`+token+`">
                            <div id="ids">
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });

        $(document).on('change','.bulk_checkbox',function(){
            var html=``;
            $('.bulk_checkbox:checked').each(function(){
                var item_id=$(this).val();
                html+=`<input type="hidden" name="ids[]" value="`+item_id+`">`
            });

            $(document).find('#bulk_form #ids').html(html);

            if($('#bulk_form #ids input').length>0)
            {
                $(document).find('.bulk_action_dropdown').prop('disabled',false);
            }
            else{
                $(document).find('.bulk_action_dropdown').prop('disabled',true);
            }
        });

        $(document).on('click','.bulk_button',function(e){
            e.preventDefault();
            if($('#bulk_form #ids input').length)
            {
                var action=$(this).attr('href');
                var warning_message=$(this).attr('warning_message');
                $('#bulk_form').attr('action',action);
                swal({
                    title: warning_message,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: trans("Confirm"),
                    cancelButtonText: trans("Cancel"),
                    closeOnConfirm: false
                },
                function(){
                    $(document).find('#bulk_form').submit();
                });
            }
            else{
                toastr_error(trans('You should select at least on row for bulk action'));
            }
        });
    }

    

})(jQuery);
