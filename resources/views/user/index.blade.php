@extends('welcome')
@section('title', 'Người dùng')
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
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Giới tính</th>
                                <th>Chức vụ</th>
                                <th>Trạng thái</th>
                                <th class="text-center"><i class="fa fa-asterisk"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ $value->images ? $value->images : 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/10/avatar-trang-4.jpg' }}"
                                            alt="{{ $value->images }}" width="50" height="50">
                                        {{ $value->name }}
                                    </td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->gender == 1 ? 'Nam' : 'Nữ' }}</td>
                                    <td>
                                        @if ($value->role_id == 1)
                                            <span class="badge badge-success">ADMIN</span>
                                        @elseif ($value->role_id == 2)
                                            <span class="badge badge-danger">Giáo viên hướng dẫn</span>
                                        @elseif ($value->role_id == 3)
                                            <span class="badge badge-info">Kiểm duyệt viên</span>
                                        @else
                                            <span class="badge badge-warning">Học viên</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1)
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-danger">Không hoạt động</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($value->role_id != 1)
                                            {{-- <a href="{{  route('users.destroy', $value->id) }}" class="btn btn-danger p-1"><i
                                                    class="fa fa-trash"></i></a> --}}
                                            <button type="button" class="btn btn-danger p-1" data-toggle="modal"
                                                data-target="#delete{{ $value->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-info p-1" data-toggle="modal"
                                                data-target="#exampleModal{{ $value->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                                <div class="modal fade" id="delete{{ $value->id }}" tabindex="-1" aria-labelledby="delete{{ $value->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Xóa người dùng</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p >Bạn có chắc muốn xóa người dùng <b class="text-danger">{{ $value->name }}</b>  không...!</p>
                                            </div>
                                            <div class="modal-footer">
                                            
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <form action="{{  route('users.destroy', $value->id) }}" method="GET">
                                                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                                                </form>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModal{{ $value->id }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Cập nhật người dùng</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('users.update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $value->id }}">
                                                <div class="modal-body" style="height:75vh; overflow:auto;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            {{-- <div class="file-wrapper file-set" style="background-image: url('{{ asset($value->images) }}')">
                                                            <input type="file" name="images" accept="image/*" value="{{ $value->images }}"/>
                                                            <div class="close-btn">×</div>
                                                        </div> --}}

                                                            <div class="form-group">
                                                                <input type="file" id="imageUpload" name="images"
                                                                    accept="image/*" class="form-control"
                                                                    onchange="previewImage(event)">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="imagePreview">Tải ảnh lên:</label>
                                                                <div id="imagePreviewWrapper" style="margin-top: 10px;">
                                                                    <img id="imagePreview"
                                                                        src="{{ $value->images ? $value->images : 'https://png.pngtree.com/png-vector/20240202/ourlarge/pngtree-bussinesman-cartoon-avatar-png-image_11591522.png' }}"
                                                                        alt="Xem trước hình ảnh"
                                                                        style="max-width: 300px; max-height: 300px;  border: 1px solid #ddd; padding: 10px; {{ $value->images ? 'display: block;' : 'display: none;' }}">
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="email"
                                                                value="{{ $value->email }}">
                                                            <div style="position: relative; z-index:999999999;">
                                                                <div class="form-group">
                                                                    <label for="name">Họ tên</label>
                                                                    <input type="text" class="form-control"
                                                                        id="name" name="name"
                                                                        placeholder="Nhập họ tên"
                                                                        value="{{ $value->name }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="first_name">Tên người dùng</label>
                                                                    <input type="text" class="form-control"
                                                                        id="first_name" name="first_name"
                                                                        placeholder="Nhập tên người dùng"
                                                                        value="{{ $value->first_name }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="phone">Số điện thoại</label>
                                                                    <input type="text" class="form-control"
                                                                        id="phone" name="phone"
                                                                        placeholder="Nhập số điện thoại"
                                                                        value="{{ $value->phone }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password">Giới tính</label>
                                                                    <select class="form-control" name="gender"
                                                                        id="">
                                                                        <option value="1"
                                                                            {{ $value->gender == 1 ? 'selected' : '' }}>Nam
                                                                        </option>
                                                                        <option value="2"
                                                                            {{ $value->gender == 2 ? 'selected' : '' }}>Nữ
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="address">Địa chỉ</label>
                                                                    <input type="text" class="form-control"
                                                                        id="address" name="address"
                                                                        placeholder="Nhập tên người dùng"
                                                                        value="{{ $value->address }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="text" class="form-control"
                                                                        id="email" placeholder="Nhập tên người dùng"
                                                                        value="{{ $value->email }}" disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password">Password</label>
                                                                    <input type="password" class="form-control"
                                                                        id="password" name="password"
                                                                        placeholder="Nhập password">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="role">Chức vụ</label>
                                                                    <select name="role_id" id=""
                                                                        class="form-control" required>
                                                                        <option value="2"
                                                                            {{ $value->role_id == 2 ? 'selected' : '' }}>
                                                                            Giáo viên hướng dẫn</option>
                                                                        {{-- <option value="3"  {{ $value->role_id == 3 ? 'selected' : '' }}>Kiểm duyệt viên</option> --}}
                                                                        <option value="4"
                                                                            {{ $value->role_id == 4 ? 'selected' : '' }}>
                                                                            Học viên</option>

                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="status">Trạng thái</label>
                                                                    <select name="status" id=""
                                                                        class="form-control" required>
                                                                        <option value="1"
                                                                            {{ $value->status == 1 ? 'selected' : '' }}>Hoạt
                                                                            động</option>
                                                                        <option value="2"
                                                                            {{ $value->status == 2 ? 'selected' : '' }}>
                                                                            Không hoạt động</option>
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
                        <div class="modal-dialog  ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Thêm người dùng</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body" style="height:75vh; overflow:auto;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="file-wrapper">
                                                    <input type="file" name="images" accept="image/*" />
                                                    <div class="close-btn">×</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Họ tên</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" placeholder="Nhập họ tên" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="first_name">Tên người dùng</label>
                                                    <input type="text" class="form-control" id="first_name"
                                                        name="first_name" placeholder="Nhập tên người dùng" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Số điện thoại</label>
                                                    <input type="text" class="form-control" id="phone"
                                                        name="phone" placeholder="Nhập số điện thoại" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Giới tính</label>
                                                    <select class="form-control" name="gender" id="">
                                                        <option value="1">Nam</option>
                                                        <option value="2">Nữ</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Địa chỉ</label>
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" placeholder="Nhập tên người dùng" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control" id="email"
                                                        name="email" placeholder="Nhập tên người dùng" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" placeholder="Nhập password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="role">Chức vụ</label>
                                                    <select name="role" id="" class="form-control" required>
                                                        <option value="2">Giáo viên hướng dẫn</option>
                                                        {{-- <option value="3">Kiểm duyệt viên</option> --}}
                                                        <option value="4">Học viên</option>

                                                    </select>
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
