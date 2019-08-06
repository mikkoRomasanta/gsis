@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <input id="role" type="text" style="display:none" value="{{ Auth::user()->role }}" readonly>
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
                    @if(Auth::user()->role == 'ADMIN')
                        <th>Action</th>
                    @else
                        <th style='display:none'>Action</th>
                    @endif
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

            var role = $('#role').val();
            if(role === 'ADMIN'){
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
        }
        else{
            $('#table-accounts').DataTable({
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
                        {'data' : 'created_at'}
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                                'register'
                    ]
                });
        }

        });
    </script>
@endpush
