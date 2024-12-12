@can('edit_supplier')
<a href="{{route('admin.inventory.suppliers.edit',$supplier['id'])}}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit"></i>
</a>
@endcan

@can('delete_supplier')
<form method="POST" action="{{route('admin.inventory.suppliers.destroy',$supplier['id'])}}" class="d-inline">
  <input type="hidden" name="_method" value="delete">
  <button type="submit" class="btn btn-danger btn-sm delete_supplier">
      <i class="fa fa-trash"></i>
  </button>
</form>
@endcan