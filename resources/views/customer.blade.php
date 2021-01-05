<!DOCTYPE html>
<html>

<head>
    <title>Laravel Ajax CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

</head>

<body>

<div class="container">
    <h1>Laravel Ajax CRUD</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewCustomer">Create New Customer</a>
    <table class="table table-bordered data-table">
        <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Last Name</th>
            <th>Details</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="CustomerForm" name="CustomerForm" class="form-horizontal">
                    <input type="hidden" name="Customer_id" id="Customer_id">

                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="firstName" name="firstName"
                                   placeholder="Enter Name"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Last Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="lastName" name="lastName"
                                   placeholder="Enter Last Name" value="" maxlength="100" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="info" name="info" required="" placeholder="Enter Details"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverside: true,
            ajax: "{{route('getCustomers')}}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'firstName', name: 'firstName'},
                {data: 'lastName', name: 'lastName'},
                {data: 'info', name: 'info'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#createNewCustomer').click(function () {
            $('#saveBtn').val("create-Customer");
            $('#Customer_id').val('');
            $('#CustomerForm').trigger("reset");
            $('#modelHeading').html("Create New Customer");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editCustomer', function () {
            var Customer_id = $(this).data('id');
            console.log(Customer_id);
            $.get("" + "customers/" + Customer_id + '/edit', function (data) {
                console.log(data);
                $('#modelHeading').html("Edit Customer");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#Customer_id').val(data.id);
                $('#firstName').val(data.firstName);
                $('#lastName').val(data.lastName);
                $('#info').val(data.info);
            });
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending...');
            var isim, soyisim, bilgi;
            isim = $('#firstName').val();
            soyisim = $('#lastName').val();
            bilgi = $('#info').val();
            if (isim == "") {
                alert("isim boş olamaz");
                $('#saveBtn').html("Save Changes");
            } else if (soyisim == "") {
                alert("soy isim boş olamaz");
                $('#saveBtn').html("Save Changes");
            } else if (bilgi == "") {
                alert("bilgi boş olamaz");
                $('#saveBtn').html("Save Changes");
            } else {
                $.ajax({
                    data: $('#CustomerForm').serialize(),
                    url: "{{route('customers.store')}}",
                    type: "POST",
                    dataType: "json",
                    success: function (data) {
                        $('#CustomerForm').trigger("reset");
                        $('#ajaxModel').modal("hide");
                        location.reload();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html("Save Changes");
                    }
                });
            }
        });

        $('body').on('click', '.deleteCustomer', function () {
            var Customer_id = $(this).data("id");
            confirm("Are you sure want to delete!");

            $.ajax({
                type: "DELETE",
                url: "" + 'customers/' + Customer_id,
                success: function (data) {
                    //table.destroy();
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data)
                }
            });
        });

    });
</script>

</html>
