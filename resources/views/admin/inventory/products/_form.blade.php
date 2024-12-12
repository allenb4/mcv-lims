<div class="row">

    <div class="col-lg-4">
        <div class="form-group">
            <label for="name">{{__('Name')}}</label>
            <input type="text" class="form-control" name="name" id="name" @if(isset($product))
                value="{{$product->name}}" @else value="{{old('name')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="sku">{{__('SKU')}}</label>
            <input type="text" class="form-control" name="sku" id="sku" @if(isset($product))
                value="{{$product->sku}}" @else value="{{old('sku')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group">
            <label for="description">{{__('Description')}}</label>
            <textarea name="description" class="form-control" id="" cols="2" rows="2">@if(isset($supllier)){{$product['description']}}@else{{old('description')}}@endif</textarea>
        </div>
    </div>
   
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary">
            <div class="card-header">
                <h5 class="card-title">
                    {{__('Branches')}}
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>{{__('Branch')}}</th>
                            <th width="150px">{{__('Initial quantity')}}</th>
                            <th width="150px">{{__('Stock alert')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($product))
                            @foreach($product['branches'] as $branch)
                                <tr>
                                    <td>
                                        {{$branch['branch']['name']}}
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="branch[{{$branch['branch_id']}}][initial_quantity]" id="branch_initial_quantity_{{$branch['branch_id']}}" value="{{$branch['initial_quantity']}}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"  name="branch[{{$branch['branch_id']}}][alert_quantity]" id="branch_alert_quantity_{{$branch['branch_id']}}" value="{{$branch['alert_quantity']}}" required>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($product_branches as $branch)
                                <tr>
                                    <td>
                                        {{$branch['name']}}
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"  name="branch[{{$branch['id']}}][initial_quantity]" id="branch_initial_quantity_{{$branch['id']}}" value="0" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"  name="branch[{{$branch['id']}}][alert_quantity]" id="branch_alert_quantity_{{$branch['id']}}" value="0" required>
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

