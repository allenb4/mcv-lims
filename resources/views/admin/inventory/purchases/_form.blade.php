<div class="row">

    <div class="col-lg-3">
        <div class="form-group">
            <label for="date">{{__('Date')}}</label>
            <input type="text" class="form-control datepicker" name="date" id="date" @if(isset($purchase)) value="{{$purchase['date']}}" @elseif(old('date')) value="{{old('date')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="branch_id">{{__('Branch')}}</label>
            <select name="branch_id" class="form-control" id="branch_id" required>
                @if(isset($purchase)&&isset($purchase['branch']))
                    <option value="{{$purchase['branch_id']}}" selected>{{$purchase['branch']['name']}}</option>
                @elseif(old('branch_id'))
                    @php 
                        $branch=\App\Models\Branch::find(old('branch_id'));
                    @endphp
                    <option value="{{$branch['id']}}" selected>{{$branch['name']}}</option>
                @endif
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="supplier_id">{{__('Supplier')}}</label>
            <select name="supplier_id" class="form-control" id="supplier_id">
                @if(isset($purchase)&&isset($purchase['supplier']))
                    <option value="{{$purchase['supplier_id']}}" selected>{{$purchase['supplier']['name']}}</option>
                @elseif(old('supplier_id'))
                    @php 
                        $supplier=\App\Models\Supplier::find(old('supplier_id'));
                    @endphp
                    <option value="{{$supplier['id']}}" selected>{{$supplier['name']}}</option>
                @endif
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="note">{{__('Note')}}</label>
            <textarea name="note" id="note" cols="3" rows="3" class="form-control">@if(isset($purchase)){{$purchase['note']}}@elseif(old('note')){{old('note')}}@endif</textarea>
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
                <table class="table table-bpurchaseed" id="products_table">
                    <thead>
                        <tr>
                            <th>{{__('Product')}}</th>
                            <th width="200px">{{__('Unit price')}}</th>
                            <th width="200px">{{__('Quantity')}}</th>
                            <th width="200px">{{__('Total price')}}</th>
                            <th width="10px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $products_count=0;
                        @endphp
                        @if(isset($purchase))
                            @foreach($purchase['products'] as $product)
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
                                        <input type="number" name="products[{{$products_count}}][price]" class="form-control price" min="0"  id="product_price_{{$products_count}}" value="{{$product['price']}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" name="products[{{$products_count}}][quantity]" class="form-control quantity" min="0"  id="product_quantity_{{$products_count}}" value="{{$product['quantity']}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" name="products[{{$products_count}}][total_price]" class="form-control total_price" min="0"  id="product_total_price_{{$products_count}}" value="{{$product['total_price']}}" readonly required>
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
                                        <input type="number" name="products[{{$products_count}}][price]" class="form-control price" min="0"  id="product_price_{{$products_count}}" value="{{$product['price']}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" name="products[{{$products_count}}][quantity]" class="form-control quantity" min="0"  id="product_quantity_{{$products_count}}" value="{{$product['quantity']}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" name="products[{{$products_count}}][total_price]" class="form-control total_price" min="0"  id="product_total_price_{{$products_count}}" value="{{$product['total_price']}}" readonly required>
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

<!-- Payments -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">
            {{__('Payments')}}
        </h5>
        <button type="button" class="btn btn-primary d-inline float-right add_payment">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="row">
            <div class="col-lg-12 table-responsive">
                @php 
                    $payments_count=0;
                @endphp
                <table class="table table-bpurchaseed" id="payments_table">
                    <thead  >
                        <th width="30%">{{__('Date')}}</th>
                        <th width="30%">{{__('Amount')}}</th>
                        <th>{{__('Payment method')}}</th>
                        <th width="10px"></th>
                    </thead>
                    <tbody>
                        @if(isset($purchase))
                            @foreach($purchase['payments'] as $payment)
                            <tr>
                                <td>
                                    <input type="text" class="form-control new_datepicker" name="payments[{{$payments_count}}][date]" value="{{$payment['date']}}" placeholder="{{__('Date')}}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control amount" name="payments[{{$payments_count}}][amount]" value="{{$payment['amount']}}" id="" required>
                                </td>
                                <td> 
                                    <div class="form-group">
                                        <select name="payments[{{$payments_count}}][payment_method_id]" id="" class="form-control payment_method_id" required>
                                            <option value="" disabled selected>{{__('Select payment method')}}</option>
                                            <option value="{{$payment['payment_method_id']}}" selected>{{$payment['payment_method']['name']}}</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete_payment">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @php 
                                $payments_count++;
                            @endphp
                            @endforeach
                        @elseif(old('payments'))
                            @foreach(old('payments') as $payment)
                            <tr>
                                <td>
                                    <input type="text" class="form-control new_datepicker" name="payments[{{$payments_count}}][date]" value="{{$payment['date']}}" placeholder="{{__('Date')}}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control amount" name="payments[{{$payments_count}}][amount]" value="{{$payment['amount']}}" id="" required>
                                </td>
                                <td> 
                                    <div class="form-group">
                                        <select name="payments[{{$payments_count}}][payment_method_id]" id="" class="form-control payment_method_id" required>
                                            <option value="" disabled selected>{{__('Select payment method')}}</option>
                                            @php 
                                                $payment_method=\App\Models\PaymentMethod::find($payment['payment_method_id']);
                                            @endphp
                                            <option value="{{$payment_method['id']}}" selected>{{$payment_method['name']}}</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete_payment">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @php 
                                $payments_count++;
                            @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--\Payments -->

<!-- Summary -->
<div class="card card-primary">
    <div class="card-header">
        <h5 class="card-title">
            {{__('Purchase summary')}}
        </h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bpurchaseed">
            <tbody>
                <tr>
                    <th width="10px">{{__('Subtotal')}}</th>
                    <td>
                        <input type="number" class="form-control" name="subtotal" @if(isset($purchase)) value="{{$purchase['subtotal']}}" @else value="0" @endif id="subtotal" readonly required>
                    </td>
                </tr>
                <tr>
                    <th>{{__('Tax')}}</th>
                    <td>
                        <input type="number" class="form-control" name="tax" @if(isset($purchase)) value="{{$purchase['tax']}}"  @elseif(old('tax')) value="{{old('tax')}}" @else value="0" @endif id="tax" required>
                    </td>
                </tr>
                <tr>
                    <th>{{__('Total')}}</th>
                    <td>
                        <input type="number" class="form-control" name="total" @if(isset($purchase)) value="{{$purchase['total']}}" @else value="0" @endif id="total" readonly required>
                    </td>
                </tr>
                <tr>
                    <th>{{__('Paid')}}</th>
                    <td>
                        <input type="number" class="form-control" name="paid" @if(isset($purchase)) value="{{$purchase['paid']}}" @else value="0" @endif id="paid" readonly required>
                    </td>
                </tr>
                <tr>
                    <th>{{__('Due')}}</th>
                    <td>
                        <input type="number" class="form-control" name="due" @if(isset($purchase)) value="{{$purchase['due']}}" @else value="0" @endif id="due" readonly required>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- \Summary -->


<input type="hidden" id="products_count" value="{{$products_count}}">
<input type="hidden" id="payments_count" value="{{$payments_count}}">
