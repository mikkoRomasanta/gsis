@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <table class="display compact table-striped" id="table-accounts">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Last Password Change</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('modal')
    @include('modals.admin_accounts_edit')
@endsection

@push('scripts')
    <script type="text/javascript">

        $(document).ready(function() {

            $.fn.dataTable.ext.buttons.register = {
                text: 'New User',
                action: function ( e, dt, node, config ) {
                location.href = '/register';
                }
            };

            var tableUser = $('#table-accounts').DataTable({
                ajax: {
                    url: "{{route('get.user')}}",
                    dataSrc: ''
                    },
                columns: [
                    {'data' : 'id'},
                    {'data' : 'username'},
                    {'data' : 'name'},
                    {'data' : 'status'},
                    {'data' : 'role'},
                    {'data' : 'updated_at'},
                    {'data' : 'created_at'},
                    { "defaultContent": "<a href='#' class='btn btn-info btn-sm' id='editBtn'>Edit</a>"}
                ],
                dom: 'Bfrtip',
                buttons: [
                            'register'
                ]
            });

            $('#table-accounts tbody')
		    .on( 'click', '#editBtn', function () {
                $("#mdlEdit").modal();
                var data = tableUser.row( $(this).parents('tr') ).data();
                $('#nameBox').val(data.name);
                $('#statusBox').val(data.status);
                $('#roleBox').val(data.role);
                $('#usernameBox').val(data.username);
                $('#idBox').val(data.id);
                $('#mdlTitle').html('EDIT | '+data.username);
		});

        });
    </script>
@endpush
