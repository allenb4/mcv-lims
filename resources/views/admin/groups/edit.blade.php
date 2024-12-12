@extends('layouts.app')

@section('title')
    {{__('Edit invoice')}}
@endsection

@section('breadcrumb')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            {{__('Invoices')}}
          </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.groups.index')}}">{{__('Invoices')}}</a></li>
            <li class="breadcrumb-item active">{{__('Edit invoice')}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
@endsection

@section('content')
   
   <!-- Form -->
   <form action="{{route('admin.groups.update',$group->id)}}" method="POST" id="group_form">
        @csrf
        @method('put')
        @include('admin.groups._form')
   </form>
   <!-- \Form -->

   <!-- Models -->
   @include('admin.groups.modals.patient_modal')
   @include('admin.groups.modals.edit_patient_modal')
   @include('admin.groups.modals.doctor_modal')
   @include('admin.groups.modals.payment_method_modal')
   <!--\Models-->


@endsection

@section('scripts')
  <script src="{{url('js/admin/groups.js')}}"></script>
@endsection