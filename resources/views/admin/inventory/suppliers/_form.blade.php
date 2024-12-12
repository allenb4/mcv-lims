<div class="row">

    <div class="col-lg-4">
        <div class="form-group">
            <label for="name">{{__('Name')}}</label>
            <input type="text" class="form-control" name="name" id="name" @if(isset($supplier))
                value="{{$supplier->name}}" @else value="{{old('name')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="email">{{__('Email')}}</label>
            <input type="email" class="form-control" name="email" id="email" @if(isset($supplier))
                value="{{$supplier->email}}" @else value="{{old('email')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="phone">{{__('Phone')}}</label>
            <input type="text" class="form-control" name="phone" id="phone" @if(isset($supplier))
                value="{{$supplier->phone}}" @else value="{{old('phone')}}" @endif required>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group">
            <label for="address">{{__('Address')}}</label>
            <input type="text" class="form-control" name="address" id="address" @if(isset($supplier))
                value="{{$supplier->address}}" @else value="{{old('address')}}" @endif>
        </div>
    </div>

</div>