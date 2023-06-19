@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.clients')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.clients')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('clients.index')}}"></a>
                            @lang('messages.clients')
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <h3>
                    <a href="{{route('clients.create')}}" class="btn btn-info">
                        <i class="fa fa-plus"></i>
                        @lang('messages.add_new')
                    </a>
                </h3>
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable"
                                               data-set="#sample_1 .checkboxes"/>
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th>@lang('messages.phone_number')</th>
                                <th>@lang('messages.country')</th>
                                <th>@lang('messages.activity')</th>
                                <th>@lang('messages.city')</th>
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($users as $user)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{$user->phone_number}} </td>
                                    <td> {{app()->getLocale() == 'ar' ? $user->country->name_ar : $user->country->name_en}} </td>
                                    <td>
                                        <span class="custom-switch {{$user->active == 'true' ? 'on' : 'off'}}" data-url_on="{{route('clientActivation' , [$user->id , 'false'])}}" data-url_off="{{route('clientActivation' , [$user->id , 'true'])}}">
                                            <span class="text">On</span>
                                            <span class="move"></span>
                                        </span>

                                    </td>
                                    <td>
                                        @if($user->city != null)
                                            {{app()->getLocale() == 'ar' ? $user->city->name_ar : $user->city->name_en}}
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info" href="{{route('clients.edit' , $user->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        <a class="delete_city btn btn-danger" data="{{ $user->id }}"
                                           data_name="{{ $user->name }}">
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                {{$users->links()}}
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
            $("#example1").DataTable({
                lengthMenu: [
                    [10, 25, 50 , 100, -1],
                    [10, 25, 50,  100,'All'],
                ],
            });
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
        $(document).ready(function () {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_city', function () {
                var id = $(this).attr('data');

                var swal_text = 'حذف ' + $(this).attr('data_name') + '؟';
                var swal_title = 'هل أنت متأكد من الحذف ؟';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق",
                    closeOnConfirm: false
                }, function () {

                    {{--var url = '{{ route("imageProductRemove", ":id") }}';--}}

                        {{--url = url.replace(':id', id);--}}

                        window.location.href = "{{ url('/') }}" + "/admin/clients/delete/" + id;


                });

            });

        });
    </script>

@endsection
