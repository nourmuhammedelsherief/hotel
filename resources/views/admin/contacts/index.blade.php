@extends('admin.layouts.master')

@section('title')
    تواصل معنا
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/admin/home">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/admin/contactUs">تواصل معنا</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض تواصل معنا</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض تواصل معنا
        <small>عرض جميع تواصل معنا</small>
    </h1>
@endsection

@section('content')

    <div class="row">
        @include('flash::message')
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase"> تواصل معنا</span>
                    </div>

                </div>
                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                        <tr>
                            <th>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                    <span></span>
                                </label>
                            </th>
                            <th></th>
                            <th> الاسم </th>
                            <th> البريد الالكتروني </th>
                            <th> رقم الهاتف </th>
                            <th> الرسالة </th>
                            <th> الرد </th>
                            <th> خيارات </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0 ?>
                        @foreach($contacts as $contact)
                            <tr class="odd gradeX">
                                <td>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td><?php echo ++$i ?></td>
                                <td> {{$contact->name}} </td>
                                <td> {{$contact->email}} </td>
                                <td>
                                    {{$contact->phone}}
                                </td>
                                <td >
                                    <a href="{{ route('showContact' , $contact->id) }}">
                                        {!! substr(strip_tags($contact->message), 0, 100) !!}
                                    </a>
                                </td>

                                <td >
                                    @if($contact->reply)
                                        <a href="{{ route('showContact' , $contact->id) }}">
                                            {!! substr(strip_tags($contact->reply), 0, 100) !!}
                                        </a>
                                    @else
                                        لم يتم إرسال الرد بعد
                                    @endif
                                </td>

                                <td>
                                        <a class="delete_attribute" data="{{$contact->id}}" data_name="{{$contact->name}}" >
                                            <i class="fa fa-key"></i> مسح
                                        </a>

                                </td>


                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/datatable.js') }}"></script>
    <script src="{{ URL::asset('admin/js/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/datatables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('admin/js/table-datatables-managed.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_attribute', function() {
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
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/admin/contacts/delete/"+id;

                });

            });

        });
    </script>



@endsection
