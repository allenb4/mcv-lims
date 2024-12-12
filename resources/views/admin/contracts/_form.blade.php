<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="title">{{__('Title')}}</label>
            <input type="text" class="form-control" name="title" placeholder="{{__('Contract title')}}" id="title"
                @if(isset($contract)) value="{{$contract->title}}" @endif required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="description">{{__('Description')}}</label>
            <textarea name="description" id="description" cols="30" rows="10"
                class="form-control">@if(!empty($contract)){{$contract['description']}}@endif</textarea>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-header">
        <h5 class="card-title">
            {{__('Discount')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">{{ __('Discount type') }}</label>
                    <ul class="list-style-none">
                        <li>
                            <input type="radio" class="discount_type" name="discount_type" value="1" id="percentage" class="d-inline" @if(isset($contract)&&$contract['discount_type']==1) checked @endif required> 
                            <label for="percentage" class="d-inline">{{ __('Percentage') }}</label>
                            <div class="form-group @if(!isset($contract)||$contract['discount_type']==2) d-none @endif">
                                <input type="number" class="form-control" name="discount_percentage" id="discount_percentage" placeholder="{{ __('Contract discount %') }}" min="0" max="100" @if (isset($contract)) value="{{ $contract->discount_percentage }}" @else value="0" @endif required>
                            </div>
                        </li>
                        <li>
                            <input type="radio" class="discount_type" name="discount_type" value="2" id="discount" class="d-inline" @if(isset($contract)&&$contract['discount_type']==2||!isset($contract)) checked @endif required>
                            <label for="discount" class="d-inline">{{ __('Custom price') }}</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{__('Tests')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="tests_table">
                            <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th width="130px">{{__('Price')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($contract))
                                    @foreach($contract['tests'] as $test)
                                    <tr>
                                        <td>
                                            {{$test['priceable']['name']}}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price" name="tests[{{$test['priceable']['id']}}]" value="{{$test['price']}}" price="{{$test['priceable']['price']}}" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                @foreach($tests as $test)
                                <tr>
                                    <td>
                                        {{$test['name']}}
                                    </td>
                                    <td>
                                        <input type="number" class="form-control price" name="tests[{{$test['id']}}]" value="{{$test['price']}}" price="{{$test['price']}}" required>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{__('Cultures')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="cultures_table">
                            <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th width="130px">{{__('Price')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($contract))
                                    @foreach($contract['cultures'] as $culture)
                                    <tr>
                                        <td>
                                            {{$culture['priceable']['name']}}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price" name="cultures[{{$culture['priceable']['id']}}]" value="{{$culture['price']}}" price="{{$culture['priceable']['price']}}" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    @foreach($cultures as $culture)
                                    <tr>
                                        <td>
                                            {{$culture['name']}}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price" name="cultures[{{$culture['id']}}]" value="{{$culture['price']}}" price="{{$culture['price']}}" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{__('Packages')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="packages_table">
                            <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th width="130px">{{__('Price')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($contract))
                                    @foreach($contract['packages'] as $package)
                                    <tr>
                                        <td>
                                            {{$package['priceable']['name']}}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price" name="packages[{{$package['priceable']['id']}}]" value="{{$package['price']}}" price="{{$package['priceable']['price']}}" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    @foreach($packages as $package)
                                    <tr>
                                        <td>
                                            {{$package['name']}}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price" name="packages[{{$package['id']}}]" value="{{$package['price']}}" price="{{$package['price']}}" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



