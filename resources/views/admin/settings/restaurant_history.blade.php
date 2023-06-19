@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.histories')
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
                    <h1>@lang('messages.histories')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin.histories')}}"></a>
                            @lang('messages.histories')
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
                                <th> @lang('messages.restaurant') </th>
                                <th> @lang('messages.price') </th>
                                <th> @lang('messages.payment_way') </th>
                                <th> @lang('messages.verification') </th>
                                <th> @lang('messages.details') </th>
                                <th> @lang('messages.date') </th>
                                {{--                                <th> @lang('messages.operations') </th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($histories as $history)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td> {{app()->getLocale() == 'ar' ? $history->restaurant->name_ar : $history->restaurant->name_en}} </td>
                                    <td>
                                        {{$history->paid_amount}}
                                    </td>
                                    <td>
                                        @if($history->payment_type == 'bank')
                                            {{trans('messages.bank_transfer')}}
                                        @elseif($history->payment_type == 'online')
                                            @lang('messages.online')
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->payment_type == 'bank')
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                    data-target="#modal-info-{{$history->id}}">
                                                <i class="fa fa-eye"></i>
                                                @lang('messages.show')
                                            </button>
                                            <div class="modal fade" id="modal-info-{{$history->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-info">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                @lang('messages.transfer_photo')
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img
                                                                src="{{asset('/uploads/transfers/' . $history->transfer_photo)}}"
                                                                width="475" height="400">
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-outline-light"
                                                                    data-dismiss="modal">
                                                                @lang('messages.close')
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        @else
                                            @lang('messages.operation_no') {{$history->invoice_id}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$history->details}}
                                    </td>
                                    <td>
                                        {{$history->operation_date->format('Y-m-d')}}
                                    </td>
                                    {{--                                    <td>--}}

                                    {{--                                        <a class="btn btn-info" href="{{route('subscription.confirm_status' ,[$history->id,'confirm'] )}}">--}}
                                    {{--                                            <i class="fa fa-calculator"></i> @lang('messages.confirm')--}}
                                    {{--                                        </a>--}}
                                    {{--                                        <a class="btn btn-danger" href="{{route('subscription.confirm_status' , [$history->id,'cancel'])}}">--}}
                                    {{--                                            <i class="fa fa-key"></i> @lang('messages.cancel')--}}
                                    {{--                                        </a>--}}

                                    {{--                                    </td>--}}
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
    {{$histories->links()}}
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
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
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
            $('body').on('click', '.delete_data', function () {
                var id = $(this).attr('data');
                var swal_text = '{{trans('messages.delete')}} ' + $(this).attr('data_name');
                var swal_title = "{{trans('messages.deleteSure')}}";

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "{{trans('messages.sure')}}",
                    cancelButtonText: "{{trans('messages.close')}}"
                }, function () {

                    window.location.href = "{{ url('/') }}" + "/admin/confirm_subscriptions/delete/" + id;

                });

            });
        });
    </script>
@endsection
