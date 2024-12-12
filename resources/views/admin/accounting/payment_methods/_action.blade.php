@can('edit_payment_method')
    <a class="btn btn-primary btn-sm" href="{{route('admin.payment_methods.edit',$payment_method['id'])}}">
        <i class="fa fa-edit" aria-hidden="true"></i>
    </a>
@endcan

@can('delete_payment_method')
    <form method="POST" action="{{route('admin.payment_methods.destroy',$payment_method['id'])}}" class="d-inline">
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-danger btn-sm delete_payment_method">
            <i class="fa fa-trash"></i>
        </button>
    </form>
@endcan