@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.admins')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    @include('flash::message')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.admins')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('admin/admins')}}"></a>
                            @lang('messages.admins')
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>
                                
                                <th> @lang('messages.name') </th>
                                <th> @lang('messages.email') </th>
                                <th> @lang('messages.phone_number') </th>
                                <th>الصلاحية</th>
                                <th>@lang('messages.created_at')</th>
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $array = []; ?>
                            @foreach( $data as $value )
                                <tr>
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td class="no_dec">{{ $value->name }}</td>
                                    <td><a href="mailto:{{ $value->email }}"> {{ $value->email }} </a></td>
                                    <td><a href="del:{{ $value->phone }}"> {{ $value->phone }} </a></td>
                                    <td>
                                        @if($value->role == 'admin')
                                            <span class="badge badge-secondary"> مدير </span>
                                        @elseif($value->role == 'sales')
                                            <span class="badge badge-success"> مبيعات </span>
                                        @elseif($value->role == 'developer')
                                            <span class="badge badge-danger"> مطور </span>
                                        @endif
                                    </td>
                                    <td> {{ $value->created_at->format('Y-m-d g:i A') }} </td>
                                    <td>
                                        <a class="btn btn-info" href="{{ url('/admin/admins/' . $value->id . '/edit') }}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        <a class="delete_data btn btn-danger" data="{{ $value->id }}" data_name="{{ $value->name }}">
                                            <i class="fa fa-times"></i> @lang('messages.delete')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

@endsection

@section('scripts')

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
    <script>
        $( document ).ready(function () {
            $('body').on('click', '.delete_data', function() {
                var id = $(this).attr('data');
                var swal_text = 'حذف ' + $(this).attr('data_name');
                var swal_title = 'هل أنت متأكد من الحذف ؟';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق"
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/admin_delete/" + id;

                });

            });
        });
    </script>
@endsection
