@extends('admin.layouts.master')

@section('title')
    عرض تفاصيل الرسالة
@endsection

@section('styles')
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
                <span>عرض تفاصيل الرسالة</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title">عرض تفاصيل الرسالة
        <small>عرض جميع تفاصيل الرسالة</small>
    </h1>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i>
                        {{ $contactU->name }}
                    </div>
                </div>
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-envelope"></i>
                        {{ $contactU->email }}
                    </div>
                    <div class="actions">
                        @if(!$contactU->reply)
                            <a class="btn btn-default btn-sm" data-toggle="modal" href="#reply_message">
                                <i class="fa fa-reply"></i> الرد
                            </a>
                        @endif
                        <a class="delete_attribute btn btn-default btn-sm" data="{{ $contactU->id }}" data_name="{{ $contactU->name }}">
                            <i class="fa fa-times"></i> حذف
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scroller" style="height:200px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
                        <span class="badge badge-roundless label-default" style="float: left">
                            {{ $contactU->created_at->format('Y-m-d g:i A') }}
                        </span><br/><br/>
                        <strong>محتوى الرسالة: </strong><br/><br/>
                        {{ $contactU->message }}
                        @if($contactU->reply)
                            <br><br><br>
                            <strong>الرد</strong><br/><br/>
                            {{ $contactU->reply }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="reply_message" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">لإرسال الرد عبر البريد الإلكتروني</h4>
                </div>
                <div class="modal-body">
                    <div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reply" class="col-lg-2 control-label">الرسالة</label>
                                    <div class="col-lg-9">
                                        <textarea id="message_reply" class="form-control autosizeme" rows="13" placeholder="الرسالة"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-7 col-lg-offset-3 mt-30">
                                <span style="color: red" class="msg_error hide">أدخل محتوى الرسالة لكي يتم إرسالها </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">إغلاق</button>
                    <button type="button" class="reply_message_btn btn green">إرسال</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

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

            $('body').on('click', '.reply_message_btn', function() {

                var msg_body = $('#message_reply').val();
                var receiver_email = "{{ $contactU->email }}";
                var id = "{{ $contactU->id }}";
                // console.log(id);

                if(msg_body){
                    $('.msg_error').addClass('hide');
                    $(this).attr('disabled', true);

                    $.ajax({
                        url: "{{ url('/') }}" + "/admin/contacts/reply" ,
                        type: "POST",
                        data: {_token: CSRF_TOKEN, 'msg_body': msg_body, 'receiver_email': receiver_email, 'id': id },
                    })
                        .done(function(reseived_data) {

                            var parsed_data = $.parseJSON(reseived_data);


                            console.log(parsed_data);
                            $("#reply_message").trigger({ type: "click" });
                            $(".modal-backdrop").hide();
                            if( parsed_data.message === 'done'){
                                swal({
                                    type: 'success',
                                    title: 'تم إرسال الرد بنجاح',
                                    confirmButtonClass: 'btn-success'
                                }, function() {

                                    window.location.href = "{{ url('/') }}" + "/admin/contacts/show/"+"{{ $contactU->id }}";


                                });
                            }
                            else{
                                swal(
                                    "حدث خطأ ما",
                                    "نأسف لحدوث هذا الخطأ, برجاء المحاولة لاحقًا",
                                    "error"
                                );
                            }
                        });
                }
                else{
                    $('.msg_error').removeClass('hide');
                }

            });
        });
    </script>
@endsection
