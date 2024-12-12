<!-- jQuery -->
<script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{url('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{url('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{url('plugins/sparklines/sparkline.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{url('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{url('plugins/moment/moment.min.js')}}"></script>
<script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{url('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('dist/js/adminlte.js')}}"></script>
<!-- DataTables -->
<script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
{{-- <script src="{{url('plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js')}}" type="text/javascript"></script> --}}
<script src="{{url('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{url('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{url('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- Toastr-->
<script src="{{ url('js/toastr.min.js')}}"></script>
<!-- Validate -->
<script src="{{url('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{url('plugins/print/jQuery.print.min.js')}}"></script>
<script src="{{url('js/jquery.classyqr.min.js')}}"></script>
<script src="{{url('js/select2.js')}}"></script>
<script src="{{url('plugins/sweet-alert/sweetalert.min.js')}}"></script>
<!-- Flatpickr -->
<script src="{{url('plugins/flatpickr/flatpickr.min.js')}}"></script>
<!-- Lightbox -->
<script src="{{url('plugins/ekko-lightbox/ekko-lightbox.js')}}"></script>

<!-- Scripts Translation -->
<script>
  var translations=`{!! session("trans") !!}`;
  function trans(key)
  {
    var trans=JSON.parse(translations);
    return (trans[key]!=null?trans[key]:key);
  }
</script>
<!-- \Scripts Translation -->

<!-- Main dashboard -->
@if(auth()->guard('admin')->check())
  <script>
    var can_view_chat=@can('view_chat') true @else false @endif;
    var can_view_visit=@can('view_visit') true @else false @endif;
    var can_view_product=@can('view_product') true @else false @endif;
    var can_view_general_statistics=@can('view_general_statistics') true @else false @endif;
    var can_view_online_admins=@can('view_online_admins') true @else false @endif;
    var can_view_online_patients=@can('view_online_patients') true @else false @endif;
    var can_view_income_statistics=@can('view_income_statistics') true @else false @endif;
    var can_view_best_income_packages=@can('view_best_income_packages') true @else false @endif;
    var can_view_best_income_tests=@can('view_best_income_tests') true @else false @endif;
    var can_view_best_income_cultures=@can('view_best_income_packages') true @else false @endif;
  </script>
  <script src="{{ url('js/admin/main.js')}}"></script>
@else 
  <script src="{{ url('js/patient/main.js')}}"></script>
@endif
<!-- \Main dashboard -->

<!-- Flash messages -->
<script>
  @if(session()->has('success'))
    toastr_success(trans("{{Session::get('success')}}"));
  @endif
  @if(Session()->has('failed')||session()->has('errors'))
    toastr_error(trans("{{Session::get('failed')}}"));
  @endif
</script>
<!-- \Flash messages -->

@yield('scripts')

<!-- Bulk actions -->
@if(auth()->guard('admin')->check())
  <script src="{{ url('js/admin/bulk_action.js')}}"></script>
@endif
<!-- \Bulk actions -->
