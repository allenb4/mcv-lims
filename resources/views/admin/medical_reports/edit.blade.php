@extends('layouts.app')

@section('title')
{{__('Edit medical report')}}
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="fa fa-flag"></i>
          {{__('Medical reports')}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Home')}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('admin.medical_reports.index')}}">{{__('Medical reports')}}</a></li>
          <li class="breadcrumb-item active">{{__('Edit medical report')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
@can('view_medical_report')
<div class="row">
  <div class="col-lg-12">

    <a href="{{route('admin.medical_reports.show',$group['id'])}}" class="btn btn-danger float-right mb-3">
      <i class="fa fa-file-pdf"></i> {{__('Print Report')}}
    </a>

    <button type="button" class="btn btn-info float-right mb-3 mr-1" data-toggle="modal" data-target="#patient_modal">
      <i class="fas fa-user-injured"></i> {{__('Patient info')}}
    </button>

    @can('sign_medical_report')
        <a class="btn btn-success float-right mb-3 mr-1" href="{{route('admin.medical_reports.sign',$group['id'])}}">
            <i class="fas fa-signature" aria-hidden="true"></i>
            {{__('Sign Report')}} 
        </a>
    @endcan

    <a @if(isset($previous)) href="{{route('admin.medical_reports.edit',$previous['id'])}}" @endif class="btn btn-info @if(!isset($previous)) disabled @endif">
      <i class="fa fa-backward mr-2"></i> 
      {{__('Previous')}}
    </a>
    <a @if(isset($next)) href="{{route('admin.medical_reports.edit',$next['id'])}}" @endif class="btn btn-success @if(!isset($next)) disabled @endif">
        {{__('Next')}}
        <i class="fa fa-forward ml-2"></i> 
    </a>
    
  </div>
</div>
@endcan

<form action="{{route('admin.medical_reports.upload_report',$group['id'])}}" method="POST" enctype="multipart/form-data">
  <div class="card card-primary card-outline collapsed-card">
    <div class="card-header">
        <h5 class="card-title">
          @if($group['uploaded_report'])
            <i class="fa fa-check-double text-success"></i>
          @endif
          {{__('Upload report')}}
        </h5>     
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <label>
            {{__('You can upload a pdf file as the report')}}
          </label>
          <div class="form-group">
            <div class="input-group">
              <div class="custom-file">
                <input type="file" name="report" accept="application/pdf" class="custom-file-input" id="report" required>
                <label class="custom-file-label" for="report">{{__('Report')}}</label>
              </div>
              @if($group['uploaded_report'])
              <div class="input-group-append">
                <span class="input-group-text" id="">
                  <a href="{{$group['report_pdf']}}" target="_blank">
                    <i class="fa fa-file-pdf"></i>
                  </a>
                </span>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">
        <i class="fa fa-check"></i>
        {{__('Upload')}}
      </button>
    </div>
  </div>
</form>

<!-- tests -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">{{__('Tests')}}</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    @if(count($group['all_tests']))
    <div class="card card-primary card-tabs">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="taps">
          @foreach($group['all_tests'] as $test)
          <li class="nav-item">
            <a class="nav-link text-capitalize" href="#test_{{$test['id']}}" data-toggle="tab">@if($test['done']) <i class="fa fa-check text-success"></i> @endif {{$test['test']['name']}}</a>
          </li>
          @endforeach
        </ul>
      </div><!-- /.card-header -->
      <div class="card-body p-0">
        <div class="tab-content">
          @foreach($group['all_tests'] as $test)
          <div class="tab-pane overflow-auto" id="test_{{$test['id']}}">
            <form action="{{route('admin.medical_reports.update',$test['id'])}}" method="POST">
              @csrf
              @method('put')
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th width="200px">{{__('Name')}}</th>
                    <th width="100px" class="text-center">{{__('Unit')}}</th>
                    <th width="400px" class="text-center">{{__('Reference Range')}}</th>
                    <th width="200px" class="text-center">{{__('Result')}}</th>
                    <th class="text-center" style="width:200px">{{__('Status')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($test['results'] as $result)
                    @if(isset($result['component']))
                      @if($result['component']['title'])
                        <tr>
                          <td colspan="5">
                            <b>{{$result['component']['name']}}</b>
                          </td>
                        </tr>
                      @else
                        <tr>
                          <td>{{$result['component']['name']}}</td>
                          <td class="text-center">{{$result['component']['unit']}}</td>
                          <td class="text-center">
                            @if(isset($result['component'])&&count($result['component']['reference_ranges']))
                            <div class="card card-primary card-outline collapsed-card">
                              <div class="card-header">
                                <h5 class="card-title">
                                 {{__('Reference ranges')}} 
                                </h5>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                    </button>
                                </div>
                              </div>
                              <div class="card-body p-0">
                                <table class="table table-striped table-bordered">
                                  <thead>
                                    <tr>
                                      <th>{{__('Gender')}}</th>
                                      <th>{{__('Age')}}</th>
                                      <th>{{__('Critical low')}}</th>
                                      <th>{{__('Normal')}}</th>
                                      <th>{{__('Critical high')}}</th>
                                    </tr>
                                  </thead>
                                  @foreach($result['component']['reference_ranges'] as $reference_range)
                                  <tr>
                                    <td>
                                      {{__($reference_range['gender'])}}
                                    </td>
                                    <td>
                                      {{__($reference_range['age_from'])}} : {{$reference_range['age_to']}} {{__($reference_range['age_unit'])}}
                                    </td>
                                    <td>
                                      {{$reference_range['critical_low_from']}}
                                    </td>
                                    <td>
                                      {{$reference_range['normal_from']}} : {{$reference_range['normal_to']}}
                                    </td>
                                    <td>
                                      {{$reference_range['critical_high_from']}}
                                    </td>
                                  </tr>
                                  @endforeach
                                </table>
                              </div>
                            </div>
                            @endif
                            {!! $result['component']['reference_range'] !!}
                          </td>
                          <td>
                            @if($result['component']['type']=='text')
                              <input type="text" name="result[{{$result['id']}}][result]" class="form-control test_result" value="{{$result['result']}}" 
                                @if(!empty($result->reference_range())) 
                                    normal_from="{{$result->reference_range()->normal_from}}" 
                                    normal_to="{{$result->reference_range()->normal_to}}" 
                                    critical_high_from="{{$result->reference_range()->critical_high_from}}" 
                                    critical_low_from="{{$result->reference_range()->critical_low_from}}" 
                                @endif
                              >
                            @else
                              <select name="result[{{$result['id']}}][result]" class="form-control select_result test_result" 
                                @if(!empty($result->reference_range())) 
                                  normal_from="{{$result->reference_range()->normal_from}}" 
                                  normal_to="{{$result->reference_range()->normal_to}}" 
                                  critical_high_from="{{$result->reference_range()->critical_high_from}}" 
                                  critical_low_from="{{$result->reference_range()->critical_low_from}}" 
                                @endif
                              >
                                <option value="" value="" disabled selected>{{__('Select result')}}</option>
                                @foreach($result['component']['options'] as $option)
                                  <option value="{{$option['name']}}" @if($option['name']==$result['result']) selected @endif>{{$option['name']}}</option>
                                @endforeach
                                <!-- Deleted option -->
                                @if(!$result['component']['options']->contains('name',$result['result']))
                                  <option value="{{$result['result']}}" selected>{{$result['result']}}</option>
                                @endif
                                <!-- \Deleted option -->
                              </select>
                            @endif
                          </td>
                          <td style="width:10px" class="text-center">
                            @if($result['component']['status'])
                              <select name="result[{{$result['id']}}][status]" class="form-control select_result status">
                                <option value="" value="" disabled selected>{{__('Select status')}}</option>
                                <option value="Critical high" @if($result['status']=='Critical high') selected @endif>{{__('Critical high')}}</option>
                                <option value="High" @if($result['status']=='High') selected @endif>{{__('High')}}</option>
                                <option value="Normal" @if($result['status']=='Normal') selected @endif>{{__('Normal')}}</option>
                                <option value="Low" @if($result['status']=='Low') selected @endif>{{__('Low')}}</option>
                                <option value="Critical low" @if($result['status']=='Critical low') selected @endif>{{__('Critical low')}}</option>
                                <!-- New status -->
                                @if(!empty($result['status'])&&!in_array($result['status'],['High','Normal','Low','Critical high','Critical low']))
                                  <option value="{{$result['status']}}" selected>{{$result['status']}}</option>
                                @endif
                                <!-- \New status -->
                              </select>
                            @endif
                          </td>
                        </tr>
                      @endif
                    @endif
                  @endforeach
                  <tr>
                    <td colspan="5">
                      <textarea name="comment" id="" cols="30" rows="3" placeholder="{{__('Comment')}}" class="form-control comment">{{$test['comment']}}</textarea>
                      <select id="select_comment_test_{{$test['id']}}" class="form-control select_comment">
                        <option value="" disabled selected>{{__('Select comment')}}</option>
                        @foreach($test['test']['comments'] as $comment)
                          <option value="{{$comment['comment']}}">{{$comment['comment']}}</option>
                        @endforeach
                      </select>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5">
                      <button class="btn btn-primary"><i class="fa fa-check"></i> {{__('Save')}}</button>
                    </td>
                  </tr>
                </tfoot>
              </table>

            </form>
          </div>
          @endforeach
          <!-- /.tab-pane -->

        </div>
        <!-- /.tab-content -->
      </div><!-- /.card-body -->
    </div>
    @else 
     <!-- check  tests selected -->
       <h6 class="text-center">
          {{__('No data available')}}
       </h6>
      <!-- End check  tests selected -->
    @endif
   
  </div>
  <!-- /.card-body -->
</div>
<!-- End tests -->

<!-- Cultures -->
@php
  $antibiotic_count=0; 
@endphp
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">{{__('Cultures')}}</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
    </div>
  </div>
  <div class="card-body">
    @if(count($group['all_cultures']))
      <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
          <ul class="nav nav-tabs" id="taps">
            @foreach($group['all_cultures'] as $culture)
            <li class="nav-item">
              <a class="nav-link text-capitalize" href="#culture_{{$culture['id']}}" data-toggle="tab">@if($culture['done']) <i class="fa fa-check text-success"></i> @endif {{$culture['culture']['name']}}</a>
            </li>
            @endforeach
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            @foreach($group['all_cultures'] as $culture)
            <div class="tab-pane" id="culture_{{$culture['id']}}">
              <form method="POST" action="{{route('admin.medical_reports.update_culture',$culture['id'])}}" class="culture_form">
                @csrf
                <div class="row">
                  @foreach($culture['culture_options'] as $culture_option)
                      @if(isset($culture_option['culture_option']))
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="culture_option_{{$culture_option['id']}}">{{$culture_option['culture_option']['value']}}</label>
                            <select class="form-control select2" name="culture_options[{{$culture_option['id']}}]" id="culture_option_{{$culture_option['id']}}">
                              <option value="" selected>{{__('none')}}</option>
                              @foreach($culture_option['culture_option']['childs'] as $option)
                                <option value="{{$option['value']}}" @if($option['value']==$culture_option['value']) selected @endif)>{{$option['value']}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      @endif
                  @endforeach
                </div>

                <div class="card card-primary">
                  <div class="card-header">
                    <h5 class="card-title">
                      {{__('Antibiotics')}}
                    </h5>
                  </div>
                  <div class="card-body p-0">
                    <div class="row">
                      <div class="col-lg-12 overflow-auto">
                          <table class="table table-striped table-bordered m-0">
                            <thead>
                              <tr>
                                <th width="">{{__('Antibiotic')}}</th>
                                <th width="200px">{{__('Sensitivity')}}</th>
                                <th width="20px">
                                  <button type="button" class="btn btn-primary btn-sm"
                                    onclick="add_antibiotic('{{$select_antibiotics}}',this)">
                                    <i class="fa fa-plus"></i>
                                  </button>
                                </th>
                              </tr>
                            </thead>
                            <tbody class="antibiotics">
                              @foreach($culture['antibiotics'] as $antibiotic)
                                @php
                                  $antibiotic_count++; 
                                @endphp
                              <tr>
                                <td>
                                  <select class="form-control antibiotic" name="antibiotic[{{$antibiotic_count}}][antibiotic]" required>
                                    <option value="" disabled selected>{{__('Select Antibiotic')}}</option>
                                    @foreach($select_antibiotics as $select_antibiotic)
                                    <option value="{{$select_antibiotic['id']}}"
                                      @if($select_antibiotic['id']==$antibiotic['antibiotic_id']) selected @endif>
                                      {{$select_antibiotic['name']}}</option>
                                    @endforeach
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="antibiotic[{{$antibiotic_count}}][sensitivity]" required>
                                    <option value="" disabled selected>{{__('Select Sensitivity')}}</option>
                                    <option value="{{__('High')}}" @if($antibiotic['sensitivity']==__('High')) selected @endif>{{__('High')}}
                                    </option>
                                    <option value="{{__('Moderate')}}" @if($antibiotic['sensitivity']==__('Moderate')) selected @endif>{{__('Moderate')}}
                                    </option>
                                    <option value="{{__('Resident')}}" @if($antibiotic['sensitivity']==__('Resident')) selected @endif>{{__('Resident')}}
                                    </option>
                                  </select>
                                </td>
                                <td>
                                  <button type="button" class="btn btn-danger btn-sm delete_row">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="3">
                                  <textarea class="form-control comment" name="comment" id="" cols="30" rows="3" placeholder="{{__('Comment')}}">{{$culture['comment']}}</textarea>
                                  <select id="select_comment_culture_{{$culture['id']}}" class="form-control select_comment">
                                    <option value="" disabled selected>{{__('Select comment')}}</option>
                                    @foreach($culture['culture']['comments'] as $comment)
                                      <option value="{{$comment['comment']}}">{{$comment['comment']}}</option>
                                    @endforeach
                                  </select>
                                </td>
                              </tr>

                            </tfoot>
                          </table>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <td colspan="3">
                      <button class="btn btn-primary"><i class="fa fa-check"></i> {{__('Save')}}</button>
                    </td>
                  </div>
                </div>
              </form>
            </div>
            @endforeach
            <!-- /.tab-pane -->
          </div>
        </div>
      </div>
    @else 
      <!-- Check Cultures Selected -->
      <h6 class="text-center">
        {{__('No data available')}}
      </h6>
      <!-- End Check Cultures Selected -->
    @endif
  </div>
</div>

<!-- antibiotic count -->
<input type="hidden" id="antibiotic_count" value="{{$antibiotic_count}}">
<!-- End Cultures-->

<input type="hidden" id="patient_id" value="{{$group['patient_id']}}">


@can('view_medical_report')
<div class="row">
  <div class="col-lg-12">

    <a href="{{route('admin.medical_reports.show',$group['id'])}}" class="btn btn-danger float-right mb-3">
      <i class="fa fa-file-pdf"></i> {{__('Print Report')}}
    </a>

    <button type="button" class="btn btn-info float-right mb-3 mr-1" data-toggle="modal" data-target="#patient_modal">
      <i class="fas fa-user-injured"></i> {{__('Patient info')}}
    </button>

    @can('sign_medical_report')
        <a class="btn btn-success float-right mb-3 mr-1" href="{{route('admin.medical_reports.sign',$group['id'])}}">
            <i class="fas fa-signature" aria-hidden="true"></i>
            {{__('Sign Report')}} 
        </a>
    @endcan

    <a @if(isset($previous)) href="{{route('admin.medical_reports.edit',$previous['id'])}}" @endif class="btn btn-info @if(!isset($previous)) disabled @endif">
      <i class="fa fa-backward mr-2"></i> 
      {{__('Previous')}}
    </a>
    <a @if(isset($next)) href="{{route('admin.medical_reports.edit',$next['id'])}}" @endif class="btn btn-success @if(!isset($next)) disabled @endif">
        {{__('Next')}}
        <i class="fa fa-forward ml-2"></i> 
    </a>
  </div>
</div>
@endcan

@include('admin.medical_reports._patient_modal')

@endsection
@section('scripts')
<script src="{{url('js/admin/medical_reports.js')}}"></script>
@endsection