@extends('layout')

@section("content")
    <section>
        <div class="container">
            <div class="d-flex justify-content-between my-5">
                <h3>Ajax Phonebook</h3>
                <button onclick="addContact()" class="btn btn-primary">Add new Contact</button>
            </div>

            <table class="table" id="table">
                <thead>
                    <tr>
                        <th width="100px">SL</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- AddContactModal -->
        <div class="modal fade" id="addContactModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add new Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name"> Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name">
                            </div>

                            <div class="mb-3">
                                <label for="mobile"> Mobile Number</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter Mobile number">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="Submit" class="btn btn-primary" >Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- EditContactModal -->
        <div class="modal fade" id="editContactModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nameEdit"> Name</label>
                                <input type="text" name="name" id="nameEdit" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="mobileEdit"> Mobile Number</label>
                                <input type="text" name="mobile" id="mobileEdit" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="editId" id="editId">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="Submit" class="btn btn-primary" >Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


@section("script")
    <script>
        // Initialize dataTable
        var table = $("#table").DataTable({
            processing:true,
            serverSide:true,
            ajax:"{{ route('contactApi') }}",
            columns: [
                {data:"DT_RowIndex"},
                {data: "name"},
                {data: "mobile"},
                {data: "action"},
            ]
        });

        function addContact(){
            $("#addContactModal form")[0].reset();
            $("#addContactModal").modal("show")
        }

        $("#addContactModal form").on("submit", function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route('contact.store') }}",
                type: "POST",
                data: $("#addContactModal form").serialize(),
                success: function(res){
                    Swal.fire({
                        title: res.title,
                        text: res.message,
                        icon: res.icon,
                        confirmButtonText:"Close"
                    });

                    $("#addContactModal").modal("hide")
                    table.ajax.reload();

                },
                error: function(err){
                    console.log(err)
                    Swal.fire({
                        title: "Error",
                        text: "Unknown error",
                        icon: "error",
                        confirmButtonText:"Close"
                    })
                }
            })
        });

        function editContact(id){
            $("#editContactModal form")[0].reset();
            $.ajax({
                url: `{{ url('contact') }}/${id}`,
                type: "GET",
                dataType:"JSON",
                success: function(res){
                    console.log(res)
                    $("#nameEdit").val(res.name);
                    $("#mobileEdit").val(res.mobile);
                    $("#editId").val(res.id);

                    $("#editContactModal").modal("show");
                },
                error: function(err){
                    console.log(err);
                }
            })
        }

        $("#editContactModal form").on("submit", function(e){
            e.preventDefault();
            let id = $("#editId").val();
            $.ajax({
                url:`{{ url('contact/update') }}/${id}`,
                type: "POST",
                data: $("#editContactModal form").serialize(),
                success: function(res){
                    console.log(res);
                    Swal.fire({
                        title: res.title,
                        text: res.message,
                        icon: res.icon,
                        confirmButtonText:"Close"
                    });

                    $("#editContactModal").modal("hide")
                    table.ajax.reload();

                },
                error: function(err){
                    console.log(err)
                    Swal.fire({
                        title: "Error",
                        text: "Unknown error",
                        icon: "error",
                        confirmButtonText:"Close"
                    })
                }
            })
        })


        function deleteContact(id){
            Swal.fire({
                title: "Delete?",
                text: "Please ensure and then confirm",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "No, go back",
            }).then(function(e){
                if(e.value===true){
                    let csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: `{{url('contact/delete')}}/${id}`,
                        type: "POST",
                        data: {'_method': 'DELETE', '_token': csrf_token},
                        success: function(res){
                            console.log(res);
                            Swal.fire({
                                title: res.title,
                                text: res.message,
                                icon: res.icon,
                                confirmButtonText:"Close"
                            });

                            $("#editContactModal").modal("hide")
                            table.ajax.reload();

                        },
                        error: function(err){
                            console.log(err)
                            Swal.fire({
                                title: "Error",
                                text: "Unknown error",
                                icon: "error",
                                confirmButtonText:"Close"
                            })
                        }
                    })
                }
            })
        }
        
    </script>
@endsection