@extends('welcome')
@section('title', 'Khóa học')
@section('css')
    <style>
        .made-by {
            text-align: center;
            padding-top: 50px;
            color: #896746;
        }

        .file-wrapper {
            width: 200px;
            height: 200px;
            border: 10px solid gray;
            position: relative;
            margin: auto;
            margin-top: 50px;
        }

        .file-wrapper:after {
            content: '+';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            width: max-content;
            height: max-content;
            display: block;
            max-height: 85px;
            font-size: 70px;
            font-weight: bolder;
            color: gray;
        }

        .file-wrapper:before {
            content: 'CẬP NHẬT HÌNH ẢNH';
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            bottom: 35px;
            width: max-content;
            height: max-content;
            font-size: 0.75em;
            color: gray;
        }

        .file-wrapper:hover:after {
            font-size: 73px;
        }

        .file-wrapper .close-btn {
            display: none;
        }

        input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 99999;
            cursor: pointer;
        }

        .file-set {
            background-size: cover;
            background-repeat: no-repeat;
            color: transparent;
            padding: 10px;
            border-width: 0px;
        }

        .file-set:hover {
            transition: all 0.5s ease-out;
            filter: brightness(110%);
        }

        .file-set:before {
            color: transparent;
        }

        .file-set:after {
            color: transparent;
        }

        .file-set .close-btn {
            position: absolute;
            width: 35px;
            height: 35px;
            display: block;
            background: #000;
            color: #fff;
            top: 0;
            right: 0;
            font-size: 25px;
            text-align: center;
            line-height: 1.5;
            cursor: pointer;
            opacity: 0.8;
        }

        .file-set>input {
            pointer-events: none;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#staticBackdrop">
                            Thêm mới
                        </button>
                    </div>
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Hình ảnh</th>
                                <th>Tên khóa học</th>
                                <th>Giáo viên phụ trách</th>

                                <th>Số buổi học</th>
                                <th>Loại khóa học</th>
                                <th>Giá tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-center"><i class="fa fa-asterisk"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $key => $course)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img id="imagePreview" 
                                        src="{{ $course->images ? $course->images : 'https://png.pngtree.com/png-vector/20200310/ourmid/pngtree-online-education-training-course-design-concept-vector-illustration-png-image_2158408.jpg' }}"
                                        alt="Xem trước hình ảnh"
                                        style="max-width: 50px; max-height: 50px;  border: 1px solid #ddd; padding: 10px;">
                                    </td>
                                    <td>
                                        
                                        {{ $course->name }}
                                    </td>
                                    <td>{{ $course->User->name }}</td>

                                    <td>{{ $course->number_of_lessons }}</td>
                                    <td>{{ $course->course_type == 1 ? 'Cơ bản' : 'Nâng cao' }}</td>
                                    <td>{{ number_format($course->price) }} VNĐ</td>
                                    <td>
                                        @if ($course->status == 1)
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-danger">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- <a href="{{ route('course.destroy', $course->id) }}" class="btn btn-danger p-1"><i
                                                class="fa fa-trash"></i></a> --}}

                                            <button type="button" class="btn btn-danger p-1" data-toggle="modal"
                                                data-target="#delete{{ $course->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <button type="button" class="btn btn-info p-1" data-toggle="modal"
                                            data-target="#exampleModal{{ $course->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="{{ route('course.edit', $course->id) }}" class="btn btn-success p-1 mt-2 ">
                                            <i class="fa fa-cogs"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="delete{{ $course->id }}" tabindex="-1" aria-labelledby="delete{{ $course->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Xóa khóa học</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p >Bạn có chắc muốn xóa khóa học <b class="text-danger">{{ $course->name }}</b>  không...!</p>
                                            </div>
                                            <div class="modal-footer">
                                            
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <form action="{{  route('course.destroy', $course->id) }}" method="GET">
                                                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                                                </form>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModal{{ $course->id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Cập nhật khóa học</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('course.update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body"
                                                    style=" height:75vh;  overflow-y: auto; overflow-x: hidden;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="hidden" name="id"
                                                                value="{{ $course->id }}">
                                                            <div class="form-group">    
                                                                <input type="file" id="imageUpload" name="images" accept="image/*" class="form-control" onchange="previewImage(event)">
                                                            </div>
                                                            <div class="form-group ">
                                                                <label for="imagePreview">Tải ảnh lên:</label>
                                                                <div id="imagePreviewWrapper" style="margin-top: 10px;">
                                                                    <img id="imagePreview" src="{{ $course->images ? $course->images : 'https://png.pngtree.com/png-vector/20200310/ourmid/pngtree-online-education-training-course-design-concept-vector-illustration-png-image_2158408.jpg' }}"
                                                                        alt="Xem trước hình ảnh"
                                                                        style="max-width: 200px; max-height: 200px;  border: 1px solid #ddd; padding: 10px; {{ $course->images ? 'display: block;' : 'display: none;'}}">
                                                                </div>
                                                            </div>
                                                           
                                                            <div style="position: relative; z-index:999999999;">
                                                                <div class="form-group position-relative" >
                                                                    <label for="status">Tên khóa học</label>
                                                                    <input type="text" class="form-control" name="name"
                                                                        value="{{ $course->name }}" required>
                                                                </div>
                                                                <div class="form-group position-relative" >
                                                                    <label for="course_type">Loại</label>
                                                                    <select name="course_type" id=""
                                                                        class="form-control" required>
                                                                        <option value="1"
                                                                            {{ $course->course_type == 1 ? 'selected' : '' }}>
                                                                            Cơ bản</option>
                                                                        <option value="2"
                                                                            {{ $course->course_type == 2 ? 'selected' : '' }}>
                                                                            Nâng cao</option>
                                                                    </select>
                                                                </div>
                                                                <div  class="form-group position-relative" >
                                                                    <label for="course_type">Giáo viên phụ trách</label>
                                                                    <select name="user_id" id="" class="form-control"
                                                                        required>
                                                                        @foreach ($user as $u)
                                                                            <option value="{{ $u->id }}"
                                                                                {{ $course->user_id == $u->id ? 'selected' : '' }}>
                                                                                {{ $u->name }} - {{ $u->email }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group position-relative" >
                                                                    <label for="status">Giá tiền</label>
                                                                    <input type="text" class="form-control" name="price"
                                                                        onkeyup="Change(this)"
                                                                        value="{{ number_format($course->price) }}" required>
                                                                </div>
                                                                <div class="form-group position-relative" >
                                                                    <label for="status">Số buổi</label>
                                                                    <input type="text" class="form-control"
                                                                        name="number_of_lessons"
                                                                        value="{{ $course->number_of_lessons }}" disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="status">Ghi chú</label>
                                                                    <textarea name="description" class="form-control" id="" cols="30" rows="10"
                                                                        value="{{ $course->description }}">{{ $course->description }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="status">Trạng thái</label>
                                                                    <select name="status" id=""
                                                                        class="form-control" required>
                                                                        <option value="1"
                                                                            {{ $course->status == 1 ? 'selected' : '' }}>Hoạt
                                                                            động</option>
                                                                        <option value="2"
                                                                            {{ $course->status == 2 ? 'selected' : '' }}>Không
                                                                            hoạt động</option>
                                                                    </select>
                                                            
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Đóng</button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Thêm khóa học</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body" style=" height:75vh;  overflow-y: auto; overflow-x: hidden;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="file-wrapper">
                                                    <input type="file" name="images" accept="image/*" />
                                                    <div class="close-btn">×</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Tên khóa học</label>
                                                    <input type="text" class="form-control" name="name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="course_type">Loại</label>
                                                    <select name="course_type" id="" class="form-control"
                                                        required>
                                                        <option value="1">Cơ bản</option>
                                                        <option value="2">Nâng cao</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="course_type">Giáo viên phụ trách</label>
                                                    <select name="user_id" id="" class="form-control" required>
                                                        @foreach ($user as $u)
                                                            <option value="{{ $u->id }}">{{ $u->name }} -
                                                                {{ $u->email }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Giá tiền</label>
                                                    <input type="text" class="form-control" name="price"
                                                        onkeyup="Change(this)" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Số buổi</label>
                                                    <input type="number" class="form-control" name="number_of_lessons" min="0"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Ghi chú</label>
                                                    <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Trạng thái</label>
                                                    <select name="status" id="" class="form-control" required>
                                                        <option value="1">Hoạt động</option>
                                                        <option value="2">Không hoạt động</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Đóng</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#table_id').dataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ độ dài trang",
                info: "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",

            }
        });
    </script>
    <script>
        CKEDITOR.replace('summernote', {
            height: 300,


        });
    </script>
    <script>
        function Change(element) {
            let value = $(element).val();
            value = value.replace(/[^0-9]/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(element).val(value);
        }
    </script>
    <script>
        $('input[name="images"]').on('change', function() {
            readURL(this, $('.file-wrapper')); //Change the image
        });

        $('.close-btn').on('click', function() { //Unset the image
            let file = $('input[name="images"]');
            $('.file-wrapper').css('background-image', 'unset');
            $('.file-wrapper').removeClass('file-set');
            file.replaceWith(file = file.clone(true));
        });

        //FILE
        function readURL(input, obj) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    obj.css('background-image', 'url(' + e.target.result + ')');
                    obj.addClass('file-set');
                }
                reader.readAsDataURL(input.files[0]);
            }
        };

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            const previewWrapper = document.getElementById('imagePreviewWrapper');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // Đặt ảnh tải trước
                    preview.style.display = 'block'; // Hiển thị ảnh
                };
                reader.readAsDataURL(file); // Đọc file dưới dạng URL
            } else {
                preview.src = ''; // Xóa ảnh nếu không chọn file
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
