@extends('base.base')
@section('content')
@section('title', __('User List'))
@push('custom-css')
    <style>
        .table_user_profile {
            display: flex;
            align-items: center;
        }

        .table_user_profile figure {
            width: 40px;
            min-width: 40px;
            height: 40px;
            overflow: hidden;
            border-radius: 50%;
            margin: 0 15px 0 0;
        }

        .table_user_profile figure img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .table_user_profile figcaption h6 {
            font-size: 14px;
            margin-bottom: 1;
            font-weight: 600;
        }

        .table_user_profile figcaption p {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 400;
        }

        .success_msg,
        .error_msg {
            display: none;
        }

        .error {
            color: red;
        }
    </style>
@endpush
<div class="card mt-3">
    <div class="card-body">
        <h4 class="card-title">User list</h4>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <button class="mb-3 usermodel">Add New</button>
                    <table id="userTable" class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Description</th>
                                <th>CreatedAt</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="usermodel" tabindex="-1" aria-labelledby="usermodelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="user" id="user" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="usermodelLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" id="email"
                            placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control only_number" name="phone" id="phone"
                            placeholder="Enter phone">
                    </div>
                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control">
                            @foreach ($role as $row)
                                <option value="{{ $row->id }}">{{ $row->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="image" id="image">
                    </div>
                    <div class="form-group">
                        <label for="Description">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter ......"></textarea>
                    </div>
                </div>
                <div class="alert alert-success success_msg" role="alert"></div>
                <div class="alert alert-danger error_msg" role="alert"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('custom-script')
    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user_list') }}",
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row, meta) {
                            return data && data.length > 30 ?
                                `<span class="short-text">${data.substring(0, 30)}...</span>
                    <span class="full-text" style="display:none;">${data}</span>
                    <span class="view-more" style="color: blue; cursor: pointer;">View More</span>` :
                                data;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                drawCallback: function(settings, json) {
                    $('#userTable').off('click', '.view-more').on('click', '.view-more', function() {
                        var $shortText = $(this).siblings('.short-text');
                        var $fullText = $(this).siblings('.full-text');

                        if ($shortText.is(':visible')) {
                            $shortText.hide();
                            $fullText.show();
                            $(this).text('View Less');
                        } else {
                            $shortText.show();
                            $fullText.hide();
                            $(this).text('View More');
                        }
                    });
                }
            });


            $(document).on('click', '.usermodel', function() {
                $('#usermodel').modal('show')
            })
            $("form[name='user']").validate({

                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email_rule: true,
                    },
                    phone: {
                        required: true
                    },
                    role: {
                        required: true
                    },
                    image: {
                        required: true
                    },

                },
                messages: {
                    name: {
                        required: 'Enter Name'
                    },
                    email: {
                        required: 'Enter email'
                    },
                    phone: {
                        required: 'Enter phone'
                    },
                    role: {
                        required: 'Select role'
                    },
                    image: {
                        required: 'Select image'
                    },
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: "{{ route('add_user') }}",
                        type: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                $("form[name='user']").find(
                                    '.serverside_error').remove();
                                $('.success_msg').html(response.msg);
                                $('.success_msg').fadeIn();
                                setTimeout(function() {
                                    $('.success_msg').fadeOut();
                                }, 3000);
                                $('#user')[0].reset();
                                $('#usermodel').modal('hide');
                                $('#userTable').DataTable().ajax.reload();
                            } else {
                                $("form[name='user']").find(
                                    '.serverside_error').remove();
                                $('.error_msg').html(response.msg);
                                $('.error_msg').fadeIn();
                                setTimeout(function() {
                                    $('.error_msg').fadeOut();
                                }, 3000);
                            }
                        },
                        error: function(xhr, status, error) {
                            handleServerError('user', xhr.responseJSON.errors);
                        }
                    });
                }
            });
        });
    </script>
@endpush

@endsection
