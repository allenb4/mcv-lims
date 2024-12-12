@can('edit_package')
    <a class="btn btn-primary btn-sm" href="{{route('admin.packages.edit',$package['id'])}}">
        <i class="fa fa-edit" aria-hidden="true"></i>
    </a>
@endcan


@can('delete_package')
    <form method="POST" action="{{route('admin.packages.destroy',$package['id'])}}" class="d-inline">
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-danger btn-sm delete_package">
            <i class="fa fa-trash"></i>
        </button>
    </form>
@endcan

