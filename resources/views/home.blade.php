@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card">
                
                <div class="card-header">{{ __('Dashboard') }}</div>
                
                <div class="col-md-3">
                    <a class='btn btn-info mt-3 mb-3 ml-4 mr-4' href="#AddProductModal" data-toggle="modal">Add Product</a>
                </div>
                
                
                <div class="card-body">
                    
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    
                    <!-- Fetch list of products and display in table -->
                       <table class="table" >
                            <thead>
                                <tr>
                                    <th width='5%'>#</th>
                                    <th width='30%'>Product Name</th>
                                    <th width='35%'>Description</th>
                                    <th width='10%'>Price</th>
                                    <th width='20%'>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $cnt = 1; ?>
                            @foreach($products as $product)
                                <tr>
                                    <td><?php echo $cnt ?></td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>
                                        <!-- Edit -->
                                        <a href="" class="btn btn-sm btn-success btn_edit"> Edit</a>
                                        <!-- Delete -->
                                        <a href="" class="btn btn-sm btn-danger btn_delete" data-id="($product->id)">Delete</a>
                                    </td>
                                </tr>
                                <?php $cnt++ ?>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- table end -->


                    <!-- {{ __('You are logged in!') }} -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
{{-- Add Modal --}}
<div class="modal fade" id="AddProductModal" tabindex="-1" aria-labelledby="AddProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddProductModalLabel">Add Product </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                
                <ul id="save_msgList"></ul>

                <div class="form-group mb-3">
                    <label for="">Product Name</label>
                    <input type="text" class="name form-control" id="productName" placeholder="Enter Product Name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control description" id="productDescription" placeholder="Write product description here" required></textarea> 
                </div>
                <div class="form-group mb-3">
                    <label for="">Price</label>
                    <input type="number" id="productPrice" class="price form-control" required>
                </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add_product">Save</button>
            </div>

        </div>
    </div>
</div>


{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit & Update Product </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <ul id="update_msgList"></ul>

                <input type="hidden" id="stud_id" />

                <div class="form-group mb-3">
                    <label for="">Product Name</label>
                    <input type="text" id="product_name" required class="form-control" name="product_name">
                </div>
                <div class="form-group mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control" placeholder="Write description here" id="p_description" name="product_description" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="">Price</label>
                    <input type="number" id="price" name="product_price" required class="form-control">
                </div>
             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-info update_product">Update</button>
            </div>

        </div>
    </div>
</div>
{{-- Edn- Edit Modal --}}


{{-- Delete Modal --}}
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Product </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Confirm to Delete Data ?</h4>
                <input type="hidden" id="deleteing_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary delete_student">Yes Delete</button>
            </div>
        </div>
    </div>
</div>
{{-- End - Delete Modal --}}
<!-- End Modals -->
@endsection







<!-- @section('scripts') -->
<script>
    $(document).ready(function () {

        fetchProducts();
        
        $(document).on('click', '.add_Product', function (e) {
            e.preventDefault();

            $(this).text('Sending..');

            var data = {
                'name': $('.name').val(),
                'course': $('.course').val(),
                'email': $('.email').val(),
                'phone': $('.phone').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Products",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#save_msgList').html("");
                        $('#save_msgList').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#save_msgList').append('<li>' + err_value + '</li>');
                        });
                        $('.add_Product').text('Save');
                    } else {
                        $('#save_msgList').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#AddProductModal').find('input').val('');
                        $('.add_Product').text('Save');
                        $('#AddProductModal').modal('hide');
                        fetchProducts();
                    }
                }
            });

        });

        $(document).on('click', '.editbtn', function (e) {
            e.preventDefault();
            var stud_id = $(this).val();
            // alert(stud_id);
            $('#editModal').modal('show');
            $.ajax({
                type: "GET",
                url: "/edit-Product/" + stud_id,
                success: function (response) {
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#editModal').modal('hide');
                    } else {
                        // console.log(response.Product.name);
                        $('#name').val(response.Product.name);
                        $('#course').val(response.Product.course);
                        $('#email').val(response.Product.email);
                        $('#phone').val(response.Product.phone);
                        $('#stud_id').val(stud_id);
                    }
                }
            });
            $('.btn-close').find('input').val('');

        });

        $(document).on('click', '.update_Product', function (e) {
            e.preventDefault();

            $(this).text('Updating..');
            var id = $('#stud_id').val();
            // alert(id);

            var data = {
                'name': $('#name').val(),
                'course': $('#course').val(),
                'email': $('#email').val(),
                'phone': $('#phone').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/update-Product/" + id,
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#update_msgList').html("");
                        $('#update_msgList').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#update_msgList').append('<li>' + err_value +
                                '</li>');
                        });
                        $('.update_Product').text('Update');
                    } else {
                        $('#update_msgList').html("");

                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#editModal').find('input').val('');
                        $('.update_Product').text('Update');
                        $('#editModal').modal('hide');
                        fetchProducts();
                    }
                }
            });

        });

        $(document).on('click', '.deletebtn', function () {
            var stud_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(stud_id);
        });

        $(document).on('click', '.delete_Product', function (e) {
            e.preventDefault();

            $(this).text('Deleting..');
            var id = $('#deleteing_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete-Product/" + id,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('.delete_Product').text('Yes Delete');
                    } else {
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('.delete_Product').text('Yes Delete');
                        $('#DeleteModal').modal('hide');
                        fetchProducts();
                    }
                }
            });
        });

    });

    function fetchProducts() {
            $.ajax({
                type: "GET",
                url: "/fetch-Products",
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('tbody').html("");
                    $.each(response.Products, function (key, item) {
                        $('tbody').append('<tr>\
                            <td>' + item.id + '</td>\
                            <td>' + item.name + '</td>\
                            <td>' + item.course + '</td>\
                            <td>' + item.email + '</td>\
                            <td>' + item.phone + '</td>\
                            <td><button type="button" value="' + item.id + '" class="btn btn-primary editbtn btn-sm">Edit</button></td>\
                            <td><button type="button" value="' + item.id + '" class="btn btn-danger deletebtn btn-sm">Delete</button></td>\
                        \</tr>');
                    });
                }
            });
    }
</script>
<!-- @endsection -->



