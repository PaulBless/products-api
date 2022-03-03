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
                       <table class="table table-hover table-responsive" id="products-table" >
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
                                        <a href="#" class="btn btn-sm btn-success btn_edit" value="{{$product->id}}" data-id="{{ $product->id }}"> Edit</a>
                                        <!-- Delete -->
                                        <a href="#" class="btn btn-sm btn-danger btn_delete" value="{{ $product->id }}" data-id="{{ $product->id }}" >Delete</a>
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

   <!-- Modals -->
{{-- Add Modal --}}
<div class="modal fade" id="AddProductModal" tabindex="-1" aria-labelledby="AddProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="AddProductModalLabel">Add Product </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
              
                <form id="addForm" method="post">
                
                <ul id="save_msgList"></ul>

                <div class="form-group mb-3">
                    <label for="">Product Name</label>
                    <input type="text" class="name form-control" id="productName" placeholder="Enter Product Name" name="name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control description" id="productDescription" placeholder="Write product description here" name="description" required></textarea> 
                </div>
                <div class="form-group mb-3">
                    <label for="">Price</label>
                    <input type="number" id="productPrice" class="price form-control" placeholder="Price" name="price" required>
                </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary save_product">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editModalLabel">Edit & Update Product </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="editForm" method="PUT">

                <input type="hidden" id="p_id" name="product_id" />

                <div class="form-group mb-3">
                    <label for="">Product Name</label>
                    <input type="text" id="p_name" required class="form-control" name="name">
                </div>
                <div class="form-group mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control" placeholder="Write description here" id="p_description" name="description" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="">Price</label>
                    <input type="number" id="p_price" name="price" required class="form-control">
                </div>
             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-info update_product">Update</button>
            </div>
            </form>

        </div>
    </div>
</div>
{{-- End- Edit Modal --}}


{{-- Delete Modal --}}
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLabel">Delete Product </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="deleting_name"></h4>
                <input type="hidden" id="deleteing_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary delete_product">Yes Delete</button>
            </div>
        </div>
    </div>
</div>
{{-- End - Delete Modal --}}
<!-- End Modals --> 

<!-- script -->
<script>
  
 $(function(){
    
    $(document).ready(function(){
        $('#products-table').DataTable();

    });

    $(document).on('click', '#sa', function(){
        swal('testing','its working!!','success');
    });

    //triggers add product form submission
    $('#addForm').submit(function(e) {
            e.preventDefault();
            var form_data = $(this).serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

             var data = {
                'name': $('#productName').val(),
                'description': $('#productDescription').val(),
                'price': $('#productPrice').val(),
            }

            $.ajax({
                type: "POST",
                url: "/api/addproduct",
                data: form_data,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.status == 400) {
                        swal('Error!!', response.message,'error');
                        
                    } else {
                        // swal('Great!!', 'Product Added Successfully','success');
                        swal({title: 'Success!!', text: response.message, type: "success"}, function(){
                            location.reload();
                            }
                        );
                        $('#addForm').trigger('reset');
                        $('#AddProductModal').modal('hide');
                    }
                }
            });

    });

    //trigger edit button click
    $(document).on('click', '.btn_edit', function (e) {
        e.preventDefault();
        var productId = $(this).data('id');
        // alert(productId);
        $.ajax({
            type: "GET",
            url: "/api/editproduct/"+productId,
            success: function (response) {
                console.log("Results: ", response);
                if (response.status == 404) {
                    swal(response.status, response.message, 'error');
                    $('#editModal').modal('hide');
                } else {
                    $('#p_name').val(response.product.name);
                    $('#p_description').val(response.product.description);
                    $('#p_price').val(response.product.price);
                    $('#p_id').val(productId);
                    $('#editModal').modal('show');
                }
            }
        });
    });

    //triggers edit form submission
    $('#editForm').submit(function(e){
        e.preventDefault();

        var id = $('#p_id').val();  //get specific product ID
        var form_data = $(this).serialize();    //get form inputs
        var data = {
            'name': $('#p_name').val(),
            'description': $('#p_description').val(),
            'price': $('#p_price').val(),
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT",
            url: "/api/updateproduct/"+id,
            data: data,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.status == 404) {
                    swal('Error!!', response.message, 'error');
                } else {
                    swal({title: 'Updated!!', text: response.message, type: "success"}, function(){ location.reload(); }
                    );
                }
            },
            error: function() {}
        });
    });

    // delete button click
    $(document).on('click', '.btn_delete', function(e){
        e.preventDefault();
        var recordId = $(this).data('id');
        // alert(recordId);
        // processDelete(recordId);
        $.ajax({
            type: "GET",
            url: "/api/editproduct/"+recordId,
            success: function (response) {
                console.log("Results: ", response);
                if (response.status == 404) {
                    swal(response.status, response.message, 'error');
                    $('#editModal').modal('hide');
                } else {
                    $('#deleting_name').html(response.product.name);
                    $('#deleteing_id').val(recordId);
                    $('#DeleteModal').modal('show');
                }
            }
        });

        
    });

    // delete record from table
    $(document).on('click', '.delete_product', function (e) {
        e.preventDefault();

        $(this).text('deleting..');
        var id = $('#deleteing_id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "DELETE",
            url: "/api/deleteproduct/"+ id,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.status == 404) {
                    swal('Error!!', response.message, 'error');
                } else {
                    swal({title: 'Success!!', text: response.message, type: "success"}, function(){
                            location.reload();
                    });
                    $('#DeleteModal').modal('hide');
                }
                }
            });
    });

 });

</script>

<script>
    function processDelete(dataId) {
        swal({
          title: 'Confirm Delete!!',
          text: "This will permanently delete product, you cannot reverse! Are you Sure?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Delete!',
          showLoaderOnConfirm: true,
            
          preConfirm: function() {
            return new Promise(function(resolve) {
              $.ajax({
                url: '/api/deleteproduct/'+dataId,
                type: 'DELETE',
                dataType: 'json'
              })
              .done(function(response){
                swal('Deleted!', response.message, 'success').then(function(){  
                    window.location.href = 'business_types';
                  });
              })
              .fail(function(){
                swal('Oops...', 'Something went wrong with ajax !', 'error');
              });
            });
            },
          allowOutsideClick: false			  
        });	
    }
</script>
</div>
@endsection



    