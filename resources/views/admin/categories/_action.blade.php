@can('edit_category')
    <a href="{{route('admin.categories.edit',$category['id'])}}" class="btn btn-primary btn-sm">
        <i class="fa fa-edit"></i>
    </a>
@endcan

@can('delete_category')
    <form method="POST" action="{{route('admin.categories.destroy',$category['id'])}}"  class="d-inline">
        <input type="hidden" name="_method" value="delete">
        <button type="submit" class="btn btn-danger btn-sm delete_category">
            <i class="fa fa-trash"></i>
        </button>
    </form>
@endcan