<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">{{__('Category')}}</label>
            <select name="category_id" class="form-control" id="category" required>
                @if(isset($test)&&isset($test['category']))
                    <option value="{{$test['category_id']}}" selected>{{$test['category']['name']}}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <label for="name">{{__('Name')}}</label>
        <input type="text" class="form-control" name="name" id="name" @if(isset($test)) value="{{$test->name}}" @endif required>
      </div> 
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <label for="shortcut">{{__('Shortcut')}}</label>
        <input type="text" class="form-control" name="shortcut" id="shortcut" @if(isset($test)) value="{{$test->shortcut}}" @endif required>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <label for="sample_type">{{__('Sample Type')}}</label>
        <input type="text" class="form-control" name="sample_type" id="sample_type" @if(isset($test)) value="{{$test->sample_type}}" @endif required>
      </div>
    </div>
    <div class="col-lg-3">
       <div class="form-group">
            <label for="price">{{__('Price')}}</label>
            <div class="input-group form-group mb-3">
                <input type="number" class="form-control" name="price" min="0" id="price" @if(isset($test)) value="{{$test->price}}" @endif required>
                <div class="input-group-append">
                <span class="input-group-text">
                    {{get_currency()}}
                </span>
                </div>
            </div>
       </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
             <label for="precautions">{{__('Precautions')}}</label>
             <textarea name="precautions" id="precautions" rows="3" class="form-control" placeholder="{{__('Precautions')}}">@if(isset($test)){{$test['precautions']}}@endif</textarea>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{__('Test Components')}}</h3>
                <ul class="list-style-none">
                    <li class="d-inline float-right">
                        <button type="button" class="btn btn-primary btn-sm add_component">
                            <i class="fa fa-plus"></i>
                            {{__('Component')}}
                        </button>
                    </li>
                    <li class="d-inline float-right mr-1">
                        <button type="button" class="btn btn-primary btn-sm  add_title">
                            <i class="fa fa-plus"></i>
                            {{__('Title')}}
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table class=" table table-striped table-bordered components">
                            <thead  >
                                <th class="text-center name">{{__('Name')}}</th>
                                <th class="text-center unit">{{__('Unit')}}</th>
                                <th class="text-center result">{{__('Result')}}</th>
                                <th class="text-center reference_ranges">{{__('Reference Range')}}</th>
                                <th class="text-center separated">{{__('Separated')}}</th>
                                <th class="text-center status">{{__('Status')}}</th>
                                <th width="10px"></th>
                            </thead>
                            <tbody class="items">
                                @php 
                                  $count=0;
                                  $count_reference_ranges=0;
                                  $count_comments=0;
                                @endphp
                                @if(isset($test))
                                    @foreach($test['components'] as $component)
                                        @php 
                                            $count++;
                                        @endphp
                                        <tr num="{{$count}}" test_id="{{$component['id']}}">
                                            @if($component['title'])
                                                <td colspan="6">
                                                    <div class="form-group">
                                                        <input type="hidden" name="component[{{$count}}][title]" value="true">
                                                        <input type="hidden" name="component[{{$count}}][id]" value="{{$component['id']}}">
                                                        <input type="text" class="form-control" name="component[{{$count}}][name]" placeholder="{{__('Name')}}" value="{{$component['name']}}" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger delete_row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @else
                                                <td>
                                                    <div class="form-group">
                                                        <input type="hidden" name="component[{{$count}}][id]" value="{{$component['id']}}">
                                                        <input type="text" class="form-control" name="component[{{$count}}][name]" placeholder="{{__('Name')}}" value="{{$component['name']}}" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="component[{{$count}}][unit]" placeholder="{{__('Unit')}}" value="{{$component['unit']}}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <ul class="p-0 list-style-none">
                                                        <li>
                                                            <input class="select_type" value="text" type="radio" name="component[{{$count}}][type]" id="text_{{$count}}" @if($component['type']=='text') checked @endif required> <label for="text_{{$count}}">{{__('Text')}}</label>
                                                        </li>
                                                        <li>
                                                            <input class="select_type" value="select" type="radio" name="component[{{$count}}][type]" id="select_{{$count}}" @if($component['type']=='select') checked @endif required> <label for="select_{{$count}}">{{__('Select')}}</label>
                                                        </li>
                                                    </ul>
                                                    <div class="options">
                                                        @if($component['type']=='select')
                                                        <table width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('Option')}}</th>
                                                                    <th width="10px" class="text-center">
                                                                        <button type="button" class="btn btn-primary btn-sm add_option">
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </head>
                                                            <tbody>
                                                            @foreach($component['options'] as $option)
                                                                <tr option_id="{{$option['id']}}">
                                                                    <td>
                                                                        <input type="text" name="component[{{$count}}][old_options][{{$option['id']}}]" value="{{$option['name']}}" class="form-control" required>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-sm text-center delete_option">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <table class="table table-bordered reference_range">
                                                        <thead>
                                                            <tr>
                                                              <th class="gender text-center">{{__('Gender')}}</th>
                                                              <th class="age_unit text-center">{{__('Age unit')}}</th>
                                                              <th class="age_from text-center">{{__('Age')}}</th>
                                                              <th class="age text-center">{{__('Critical low')}}</th>
                                                              <th class="age text-center">{{__('Normal')}}</th>
                                                              <th class="age text-center">{{__('Critical high')}}</th>
                                                              <th width="10px">
                                                                <button type="button" class="btn btn-sm btn-primary add_range">
                                                                  <i class="fa fa-plus"></i>
                                                                </button>
                                                              </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($component['reference_ranges'] as $reference_range)
                                                                @php $count_reference_ranges++ @endphp
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <select class="form-control" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][gender]" required>
                                                                            <option value="both" @if($reference_range['gender']=='both') selected @endif>{{__('Both')}}</option>
                                                                            <option value="male" @if($reference_range['gender']=='male') selected @endif>{{__('Male')}}</option>
                                                                            <option value="female" @if($reference_range['gender']=='female') selected @endif>{{__('Female')}}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <select class="form-control" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][age_unit]" required>
                                                                            <option value="day" @if($reference_range['age_unit']=='day') selected @endif>{{__('Days')}}</option>
                                                                            <option value="month" @if($reference_range['age_unit']=='month') selected @endif>{{__('Months')}}</option>
                                                                            <option value="year" @if($reference_range['age_unit']=='year') selected @endif>{{__('Years')}}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input type="number" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][age_from]" class="form-control" value="{{$reference_range['age_from']}}" required>:
                                                                        <input type="number" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][age_to]" class="form-control" value="{{$reference_range['age_to']}}" required>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input type="text" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][critical_low_from]" class="form-control" value="{{$reference_range['critical_low_from']}}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input type="text" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][normal_from]" class="form-control" value="{{$reference_range['normal_from']}}">: 
                                                                        <input type="text" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][normal_to]" class="form-control" value="{{$reference_range['normal_to']}}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input type="text" name="component[{{$count}}][reference_ranges][{{$count_reference_ranges}}][critical_high_from]" class="form-control" value="{{$reference_range['critical_high_from']}}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-danger delete_range">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="component[{{$count}}][reference_range]" placeholder="{{__('Reference Range')}}">{!!$component['reference_range']!!}</textarea>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input class="check_separated" num="{{$count}}" type="checkbox" name="component[{{$count}}][separated]" @if($component['separated']) checked @endif>
                                                    <div class="component_price">
                                                        @if($component['separated'])
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-header">
                                                                <h5 class="card-title">
                                                                {{__('Price')}}
                                                                </h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="input-group form-group mb-3">
                                                                    <input type="number" class="form-control" name="component[{{$count}}][price]" value="{{$component['price']}}" min="0" class="price" required>
                                                                    <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        {{get_currency()}}
                                                                    </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input  type="checkbox" name="component[{{$count}}][status]" @if($component['status']) checked @endif>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger delete_row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <ul class="list-style-none">
                    <li class="d-inline float-right">
                        <button type="button" class="btn btn-primary btn-sm add_component">
                            <i class="fa fa-plus"></i>
                            {{__('Component')}}
                        </button>
                    </li>
                    <li class="d-inline float-right mr-1">
                        <button type="button" class="btn btn-primary btn-sm  add_title">
                            <i class="fa fa-plus"></i>
                            {{__('Title')}}
                        </button>
                    </li>
                </ul>
            </div>
         </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary">
            <div class="card-header">
                <h5 class="card-title">
                    {{__('Result comments')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table class="table table-striped table-bordered" id="comments_table">
                            <thead>
                                <tr>
                                    <th>{{__('Comment')}}</th>
                                    <th width="10px">
                                        <button type="button" class="btn btn-primary float-right add_comment">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($test))
                                    @foreach($test['comments'] as $comment)
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <textarea name="comments[{{$count_comments}}]" class="form-control" id="" cols="30" rows="3" required>{{$comment['comment']}}</textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm delete_comment">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @php 
                                            $count_comments++;
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{__('Comment')}}</th>
                                    <th width="10px">
                                        <button type="button" class="btn btn-primary float-right add_comment">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="" id="count" value="{{$count}}"> 
<input type="hidden" name="" id="count_reference_ranges" value="{{$count_reference_ranges}}">
<input type="hidden" name="" id="count_comments" value="{{$count_comments}}">

