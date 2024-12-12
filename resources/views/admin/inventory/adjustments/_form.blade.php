<div class="row">

    <div class="col-lg-3">
        <div class="form-group">
            <label for="date">{{__('Date')}}</label>
            <input type="text" class="form-control datepicker" name="date" id="date" @if(isset($adjustment)) value="{{$adjustment['date']}}" @elseif(old('date')) value="{{old('date')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="branch_id">{{__('Branch')}}</label>
            <select name="branch_id" class="form-control" id="branch_id" required>
                @if(isset($adjustment)&&isset($adjustment['branch']))
                    <option value="{{$adjustment['branch_id']}}" selected>{{$adjustment['branch']['name']}}</option>
                @elseif(old('branch_id'))
                    @php 
                        $branch=\App\Models\Branch::find(old('branch_id'));
                    @endphp
                    <option value="{{$branch['id']}}" selected>{{$branch['name']}}</option>
                @endif
            </select>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title">
                    {{__('Products')}}
                </h5>
                <button type="button" class="btn btn-primary add_product float-right">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-badjustmented" id="products_table">
                    <thead>
                        <tr>
                            <th>{{__('Product')}}</th>
                            <th width="200px">{{__('Quantity')}}</th>
                            <th width="200px">{{__('Type')}}</th>
                            <th width="200px">{{__('Note')}}</th>
                            <th width="10px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $products_count=0;
                        @endphp
                        @if(isset($adjustment))
                            @foreach($adjustment['products'] as $product)
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select name="products[{{$products_count}}][id]" id="product_name_{{$products_count}}" class="form-control product_id" required>
                                            <option value="{{$product['product_id']}}" selected>{{$product['product']['name']}}</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" name="products[{{$products_count}}][quantity]" class="form-control quantity" min="0"  id="product_quantity_{{$products_count}}" value="{{$product['quantity']}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="products[{{$products_count}}][type]" id="product_type_{{$products_count}}" class="form-control" required>
                                            <option value="" disabled selected>{{__('Select type')}}</option>
                                            <option value="1" @if($product['type']==1) selected @endif>{{__('In')}}</option>
                                            <option value="2" @if($product['type']==2) selected @endif>{{__('Out')}}</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <textarea name="products[{{$products_count}}][note]" class="form-control" id="product_note_{{$products_count}}" cols="2" rows="2" placeholder="{{__('Note')}}">{{$product['note']}}</textarea>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete_product">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @php 
                                $products_count++;
                            @endphp
                            @endforeach
                        @elseif(old('products'))
                            @foreach(old('products') as $product)
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select name="products[{{$products_count}}][id]" class="form-control product_id" id="product_name_{{$products_count}}" required>
                                            @php 
                                                $original_product=\App\Models\Product::find($product['id']);
                                            @endphp
                                            <option value="{{$original_product['id']}}" selected>{{$original_product['name']}}</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" name="products[{{$products_count}}][quantity]" class="form-control quantity" min="0"  id="product_quantity_{{$products_count}}" value="{{$product['quantity']}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="products[{{$products_count}}][type]" id="product_type_{{$products_count}}" class="form-control" required>
                                            <option value="" disabled selected>{{__('Select type')}}</option>
                                            <option value="1" @if($product['type']==1) selected @endif>{{__('In')}}</option>
                                            <option value="2" @if($product['type']==2) selected @endif>{{__('Out')}}</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <textarea name="products[{{$products_count}}][note]" class="form-control" id="product_note_{{$products_count}}" cols="2" rows="2" placeholder="{{__('Note')}}">{{$product['note']}}</textarea>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete_product">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @php 
                                $products_count++;
                            @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<input type="hidden" id="products_count" value="{{$products_count}}">
