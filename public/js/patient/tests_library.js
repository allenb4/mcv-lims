(function($){

    "use strict";

    //active
    $('#tests_library').addClass('active');

    //tests datatable
    var table_tests=$('#analyses_table').DataTable( {
      "lengthMenu": [[10, 25, 50,100,500], [10, 25, 50,100,500]],
      dom: "<'row'<'col-sm-4'l><'col-sm-4'><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4'i><'col-sm-8'p>>",
      "processing": true,
      "serverSide": true,
      "bSort" : false,
      fixedHeader: true,
        "ajax": {
          url: url("patient/get_analyses")
        },
        "columns": [
           {data:"name"},
           {data:"shortcut"},
           {data:"sample_type"},
           {data:"precautions"},
        ],
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
        }
  
    });

    //culutres datatable
    var table_cultures=$('#cultures_table').DataTable( {
      "lengthMenu": [[10, 25, 50,100,500], [10, 25, 50,100,500]],
      dom: "<'row'<'col-sm-4'l><'col-sm-4'><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4'i><'col-sm-8'p>>",
      "processing": true,
      "serverSide": true,
      "bSort" : false,
      fixedHeader: true,
        "ajax": {
          url: url("patient/get_cultures")
        },
        "columns": [
           {data:"name"},
           {data:"sample_type"},
           {data:"precautions"},
        ],
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
        }
  
    });

    //culutres datatable
    var table_packages=$('#packages_table').DataTable( {
      "lengthMenu": [[10, 25, 50,100,500], [10, 25, 50,100,500]],
      dom: "<'row'<'col-sm-4'l><'col-sm-4'><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4'i><'col-sm-8'p>>",
      "processing": true,
      "serverSide": true,
      "bSort" : false,
      fixedHeader: true,
        "ajax": {
          url: url("patient/get_packages")
        },
        "columns": [
           {data:"name"},
           {data:"shortcut"},
           {data:"precautions"},
        ],
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
        }
  
    });

})(jQuery);
