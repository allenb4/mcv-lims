@can('edit_transfer')
<a href="{{route('admin.inventory.transfers.edit',$transfer['id'])}}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit"></i>
</a>
@endcan

@can('delete_transfer')
<form method="POST" action="{{route('admin.inventory.transfers.destroy',$transfer['id'])}}" class="d-inline">
  <input type="hidden" name="_method" value="delete">
  <button type="submit" class="btn btn-danger btn-sm delete_transfer">
    <i class="fa fa-trash"></i>
  </button>
</form>
@endcan