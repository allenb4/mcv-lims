@can('edit_purchase')
<a href="{{route('admin.inventory.purchases.edit',$purchase['id'])}}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit"></i>
</a>
@endcan

@can('delete_purchase')
<form method="POST" action="{{route('admin.inventory.purchases.destroy',$purchase['id'])}}" class="d-inline">
  <input type="hidden" name="_method" value="delete">
  <button type="submit" class="btn btn-danger btn-sm delete_purchase">
      <i class="fa fa-trash"></i>
  </button>
</form>
@endcan