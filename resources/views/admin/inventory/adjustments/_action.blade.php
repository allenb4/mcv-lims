@can('edit_adjustment')
<a href="{{route('admin.inventory.adjustments.edit',$adjustment['id'])}}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit"></i>
</a>
@endcan

@can('delete_adjustment')
<form method="POST" action="{{route('admin.inventory.adjustments.destroy',$adjustment['id'])}}" class="d-inline">
  <input type="hidden" name="_method" value="delete">
  <button type="submit" class="btn btn-danger btn-sm delete_adjustment">
      <i class="fa fa-trash"></i>
  </button>
</form>
@endcan