(function($){

    "use strict";
    
    //active
    $('#dashboard').addClass('active');

    //inialize datatable
    $("#product_alerts_table").DataTable({
        "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 filter_branch'><'col-sm-4'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-4'i><'col-sm-8'p>>",
        buttons: [
        {
            extend:    'copyHtml5',
            text:      '<i class="fas fa-copy"></i> '+trans("Copy"),
            titleAttr: 'Copy'
        },
        {
            extend:    'excelHtml5',
            text:      '<i class="fas fa-file-excel"></i> '+trans("Excel"),
            titleAttr: 'Excel'
        },
        {
            extend:    'csvHtml5',
            text:      '<i class="fas fa-file-csv"></i> '+trans("CVS"),
            titleAttr: 'CSV'
        },
        {
            extend:    'pdfHtml5',
            text:      '<i class="fas fa-file-pdf"></i> '+trans("PDF"),
            titleAttr: 'PDF'
        },
        {
            extend:    'colvis',
            text:      '<i class="fas fa-eye"></i>',
            titleAttr: 'PDF'
        }
        
        ],
        "processing": true,
        "serverSide": false,
        "bSort" : false,
        "language": {
            "sEmptyTable":     trans("No data available in table"),
            "sInfo":           trans("Showing")+" _START_ "+trans("to")+" _END_ "+trans("of")+" _TOTAL_ "+trans("records"),
            "sInfoEmpty":      trans("Showing")+" 0 "+trans("to")+" 0 "+trans("of")+" 0 "+trans("records"),
            "sInfoFiltered":   "("+trans("filtered")+" "+trans("from")+" _MAX_ "+trans("total")+" "+trans("records")+")",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     trans("Show")+" _MENU_ "+trans("records"),
            "sLoadingRecords": trans("Loading..."),
            "sProcessing":     trans("Processing..."),
            "sSearch":         trans("Search")+":",
            "sZeroRecords":    trans("No matching records found"),
            "oPaginate": {
                "sFirst":    trans("First"),
                "sLast":     trans("Last"),
                "sNext":     trans("Next"),
                "sPrevious": trans("Previous")
            },
        },
        "initComplete": function( settings, json ) {
            $.ajax({
                url:ajax_url('get_all_branches'),
                success:function(branches){
                    var html=`<select class="form-control" id="select_branch">
                        <option value="" selected>`+trans('All')+`</option> 
                    `;
                    branches.forEach(function(branch){
                        html+=`<option id="`+branch.id+`">`+branch.name+`</option>`
                    });
                    html+=`</select>`;

                    $('.filter_branch').append(html);
                }
            })
        }
    });

    $(document).on('change','#select_branch',function(){
        $(document).find('#product_alerts_table_filter input').val($(this).val()).trigger('keyup');
    });


})(jQuery);
