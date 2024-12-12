
(function($){
      
  "use strict";

  var date=new Date();
  var current_year=date.getFullYear();

  //active
  $('#dashboard').addClass('active');

  //datatable
  $('.datatable').DataTable();

  //change status
  $(document).on('click','label',function(){
    var id=$(this).prev('input').attr('visit-id');
    $.ajax({
        type:'post',
        url:ajax_url("change_visit_status/"+id),
        success:function(message)
        {
            toastr.success(message);
        }
    })
  });

  
  $('#filter_income').datepicker( {
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'mm-yy',
    yearRange:"1900:"+current_year,
    onClose: function(dateText, inst) { 
        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(year, month, 1));
        $(this).trigger('change');
    },
  });
  
  //general statistics
  if(can_view_general_statistics)
  {
    filter_statistics();
  }

  //income chart
  if(can_view_income_statistics)
  {
    income_chart_statistics();
  }

  //best packages chart
  if(can_view_best_income_packages)
  {
    best_packages();
  }

  //best tests chart
  if(can_view_best_income_tests)
  {
    best_tests();
  }
  
  //best cultures chart
  if(can_view_best_income_cultures)
  {
    best_cultures();
  }
  
  //filter general statistics
  $(document).on('change','#filter_statistics',function(){
    filter_statistics();
  });

  //filter income by date
  $(document).on('change','#filter_income',function(){
    income_chart_statistics();
  });

  //filter income by branch
  $(document).on('change','#filter_income_branch',function(){
    income_chart_statistics();
  });

  //filter best packages by  date
  $(document).on('change','#filter_best_package_date',function(){
    best_packages();
  });

  //filter best packages by branch
  $(document).on('change','#filter_best_package_branch',function(){
    best_packages();
  });

  //filter best tests by  date
  $(document).on('change','#filter_best_test_date',function(){
    best_tests();
  });

  //filter best tests by branch
  $(document).on('change','#filter_best_test_branch',function(){
    best_tests();
  });

  //filter best cultures by  date
  $(document).on('change','#filter_best_culture_date',function(){
    best_cultures();
  });

  //filter best cultures by branch
  $(document).on('change','#filter_best_culture_branch',function(){
    best_cultures();
  });

  //get online users
  if(can_view_online_admins)
  {
    get_online_admins();
  }

  //get online patients
  if(can_view_online_patients)
  {
    get_online_patients();
  }

  setInterval(function(){ 
    if(can_view_online_admins)
    {
      get_online_admins();
    }
    if(can_view_online_patients)
    {
      get_online_patients();
    }
  }, 30000);

})(jQuery);


//get online admins
function get_online_admins()
{
  $.ajax({
    url:ajax_url('online_admins'),
    success:function(admins)
    {
      if(admins.length==0)
      {
        $('.online_admins_list').html(`
        <li class="item text-center">
          <p class="text-danger">`+trans("No admins online")+`</p>
        </li>
        `);
      }
      else{
        var html='';
        admins.forEach(admin => {
          html+=`<li class="item">
                  <a href="`+url('admin/users/'+admin.id+'/edit')+`" class="text-white">
                    <i class="fas fa-check-circle text-success"></i> <p class="d-inline">`+admin.name+`</p>
                  </a>
                  </li>`;
        });
        $('.online_admins_list').html(html);
      }

      $('.online_admins_count').text(admins.length);
      
    }
  });
}


//get online admins
function get_online_patients()
{
  $.ajax({
    url:ajax_url('online_patients'),
    success:function(patients)
    {
      if(patients.length==0)
      {
        $('.online_patients_list').html(`
        <li class="item text-center">
          <p class="text-danger">`+trans("No patients online")+`</p>
        </li>
        `);
      }
      else{
        var html='';
        patients.forEach(patient => {
          html+=`<li class="item">
                    <a href="`+url('admin/patients/'+patient.id+'/edit')+`" class="text-white">
                      <i class="fas fa-check-circle text-success"></i> <p class="d-inline">`+patient.name+`</p>
                    </a>
                  </li>`;
        });
        $('.online_patients_list').html(html);
      }

      $('.online_patients_count').text(patients.length);
      
    }
  });
}

//Draw income chart
function income_chart_statistics()
{
  var date=$('#filter_income').val();
  var date=date.split('-');
  var month=date[0];
  var year=date[1];

  var labels=[];
  
  for(i=1;i<32;i++)
  {
    if(i<10)
    {
      labels.push('0'+i+'/'+month+'/'+year);
    }
    else{
      labels.push(i+'/'+month+'/'+year);
    }
  }

  $.ajax({
    url:ajax_url('get_income_chart/'+month+'/'+year)+'?branch_id='+$('#filter_income_branch').val(),
    beforeSend:function(){
      $('.preloader').show();
      $('.loader').show();
    },
    success:function(response)
    {
        var height = '20px';

        //responsive for mobile
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
          || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
          height = '120px';
        }
        $('#income_chart').append(`
          <canvas id="income_chart_statistics"  height="`+height+`" width="80vw"></canvas>
        `);
        
        var ctx = document.getElementById('income_chart_statistics');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: response.data
            },
            options: {
                responsive:true,
                legend: {
                    labels: {
                        fontColor: response.font_color,
                        fontSize: 16
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                    yAxes: [{
                      ticks: {
                          fontColor: response.font_color
                      }
                    }],
                    xAxes: [{
                      ticks: {
                          fontColor: response.font_color
                      }
                    }]
                }
            },
            plugins: [{
              beforeInit: function(chart, options) {
                chart.legend.afterFit = function() {
                  this.height = this.height + 30;
                };
              }
            }]
        });

    },
    complete:function(){
      $('.preloader').hide();
      $('.loader').hide();
    }
  });
  
}

//statistics
function filter_statistics()
{
  var date=$('#filter_statistics').val();

  $.ajax({
      url:ajax_url('get_statistics'+'?date='+date),
      beforeSend:function(){
        $('.preloader').show();
        $('.loader').show();
      },
      success:function(data)
      {
        for (const [key, value] of Object.entries(data)) {
          $('#'+key).text(value);
        }
      },
      complete:function()
      {
        $('.preloader').hide();
        $('.loader').hide();
      }
  });
}



//best packages chart
function best_packages()
{
  var date=$('#filter_best_package_date').val();
  var branch_id=$('#filter_best_package_branch').val();
  
  $.ajax({
    url:ajax_url('get_best_income_packages')+'?date='+date+'&branch_id='+branch_id,
    success:function(response){
      $('#best_packages_chart').remove();
      $('#best_packages').append(`
        <canvas id="best_packages_chart" width="80" height="80"></canvas>
      `);
      var ctx = document.getElementById('best_packages_chart');
      var myChart = new Chart(ctx, {
          type: 'doughnut',
          data: response.data,
          options:{
            legend: {
              labels: {
                  fontColor: response.font_color,
              }
            },
          },
          plugins: [{
            beforeInit: function(chart, options) {
              chart.legend.afterFit = function() {
                this.height = this.height + 30;
              };
            }
          }]
      });
    }
  });
  
}

//best tests chart
function best_tests()
{
  var date=$('#filter_best_test_date').val();
  var branch_id=$('#filter_best_test_branch').val();
  
  $.ajax({
    url:ajax_url('get_best_income_tests')+'?date='+date+'&branch_id='+branch_id,
    success:function(response){
      $('#best_tests_chart').remove();
      $('#best_tests').append(`
        <canvas id="best_tests_chart" width="80" height="80"></canvas>
      `);

      var ctx = document.getElementById('best_tests_chart');
      var myChart = new Chart(ctx, {
          type: 'doughnut',
          data: response.data,
          options:{
            legend: {
              labels: {
                  fontColor: response.font_color,
              }
            },
          },
          plugins: [{
            beforeInit: function(chart, options) {
              chart.legend.afterFit = function() {
                this.height = this.height + 30;
              };
            }
          }]
      });
    }
  });
  
}

//best cultures chart
function best_cultures()
{
  var date=$('#filter_best_culture_date').val();
  var branch_id=$('#filter_best_culture_branch').val();
  
  $.ajax({
    url:ajax_url('get_best_income_cultures')+'?date='+date+'&branch_id='+branch_id,
    success:function(response){
      $('#best_cultures_chart').remove();
      $('#best_cultures').append(`
        <canvas id="best_cultures_chart" width="80" height="80"></canvas>
      `);

      var ctx = document.getElementById('best_cultures_chart');
      var myChart = new Chart(ctx, {
          type: 'doughnut',
          data: response.data,
          options:{
            legend: {
              labels: {
                  fontColor: response.font_color,
              }
            },
          },
          plugins: [{
            beforeInit: function(chart, options) {
              chart.legend.afterFit = function() {
                this.height = this.height + 30;
              };
            }
          }]
      });
    }
  });
  
}