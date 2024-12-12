@can('edit_product')
<a href="{{route('admin.inventory.products.edit',$product['id'])}}" class="btn btn-primary btn-sm">
  <i class="fa fa-edit"></i>
</a>
@endcan

@can('delete_product')
<form method="POST" action="{{route('admin.inventory.products.destroy',$product['id'])}}" class="d-inline">
  <input type="hidden" name="_method" value="delete">
  <button type="submit" class="btn btn-danger btn-sm delete_product">
      <i class="fa fa-trash"></i>
  </button>
</form>
@endcan