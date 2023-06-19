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
{{--    @include('flash::message')--}}

    <section class="content">
        <div class="row">
            <form role="form" action="{{route('admin.histories')}}" method="get" enctype="multipart/form-data">
                <input type='hidden' name='_token' value='{{Session::token()}}'>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label"> @lang('messages.month') </label>
                                <select name="month" class="form-control" required>
                                    @for($i = 1; $i <= 12 ; $i++)
                                        <option value="{{$i}}" {{$i == $month ? 'selected' : ''}}> {{$i}} </option>
                                    @endfor
                                </select>
                                @if ($errors->has('month'))
                                    <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('month') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label"> @lang('messages.year') </label>
                                <select name="year" class="form-control" required>
                                    @for($i = 2022; $i <= \Carbon\Carbon::now()->format('Y') ; $i++)
                                        <option value="{{$i}}" {{$i == $year ? 'selected' : ''}}> {{$i}} </option>
                                    @endfor
                                </select>
                                @if ($errors->has('year'))
                                    <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('year') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <br>
                            <button type="submit" class="btn btn-primary">@lang('messages.show')</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{number_format((float)($month_total_amount - $tax_values), 2, '.', '')}}
                        </h5>

                        <p>@lang('messages.month_total_amount')</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-calculator"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{number_format((float)$tax_values, 2, '.', '')}}
                        </h5>

                        <p>@lang('messages.month_total_taxes')</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-calculator"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{$subscribed_restaurants}}
                        </h5>

                        <p>عدد المطاعم المشتركة</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{$renewed_restaurants}}
                        </h5>

                        <p>عدد المطاعم المجددة</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{$subscribed_branches}}
                        </h5>

                        <p>عدد الفروع المشتركة</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{$renewed_branches}}
                        </h5>

                        <p>عدد الفروع المجددة</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{$registered_services}}
                        </h5>

                        <p>عدد الخدمات المشتركة</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-cogs"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-5">
                <!-- small box -->
                <div class="small-box bg-silver">
                    <div class="inner">
                        <h5>
                            {{$renewed_services}}
                        </h5>

                        <p>عدد الخدمات المجددة</p>
                    </div>
                    <div class="icon" style="color: black">
                        <i class="fa fa-cogs"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
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
                                {{--                                <th> @lang('messages.package') </th>--}}
                                <th> @lang('messages.price') </th>
                                <th> @lang('messages.tax') </th>
                                <th> @lang('messages.total') </th>
                                <th> @lang('messages.payment_way') </th>
                                <th> @lang('messages.verification') </th>
                                <th> @lang('messages.details') </th>
                                <th> @lang('messages.date') </th>
                                <th> @lang('messages.operations') </th>
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
                                    {{--                                    <td> --}}
                                    {{--                                        @if($history->type != 'service')--}}
                                    {{--                                        {{app()->getLocale() == 'ar' ? $history->package->name_ar : $history->package->name_en}}--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </td>--}}
                                    <td>
                                        {{number_format((float)($history->paid_amount - $history->tax_value), 2, '.', '')}}
                                    </td>
                                    <td>
                                        {{number_format((float)$history->tax_value, 2, '.', '')}}
                                    </td>
                                    <td>
                                        {{number_format((float)$history->paid_amount, 2, '.', '')}}
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
                                    <td>
                                        <a class="delete_data btn btn-danger" data="{{ $history->id }}" data_name="{{app()->getLocale() == 'ar' ? $history->restaurant->name_ar : $history->restaurant->name_en}}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
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

                    window.location.href = "{{ url('/') }}" + "/admin/histories/delete/" + id;

                });

            });
        });
    </script>
@endsection
