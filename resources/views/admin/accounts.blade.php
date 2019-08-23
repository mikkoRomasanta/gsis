@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <input id="role" type="text" style="display:none" value="{{ Auth::user()->role }}" readonly>
        <table class="display compact table-striped" id="table-accounts">
            <thead>
                <tr id="filter_row" class="color-bg-link">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @if(Auth::user()->role == 'ADMIN')
                        <th></th>
                    @else
                        <th style='display:none'></th>
                    @endif
                </tr>
                <tr class="color-bg-main color-font-dark">
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
                        { "defaultContent": "<a href='#' class='btn btn-info btn-sm' id='editBtn'><i class='fas fa-edit fa-fw'></i></a>"}
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                                'register'
                    ],
                    initComplete: function () {
                        this.api().columns([3,4]).every( function () {
                            var column = this;
                            var select = $('<select style="font-size: .8rem;width: 100%;"><option value="">All</option></select>')
                            .appendTo($("#filter_row").find("th").eq(column.index()))
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                                );

                                column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                            } );
                            column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                var val = $.fn.dataTable.util.escapeRegex(d);
                                if (column.search() === "^" + val + "$") {
                                select.append(
                                '<option value="' + d + '" selected="selected">' + d + "</option>"
                                );
                                } else {
                                select.append('<option value="' + d + '">' + d + "</option>");
                                }
                            });
                        });
                    }
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
                    ],
                    orderClasses: false,
                    initComplete: function () {
                        this.api().columns([3,4]).every( function () {
                            var column = this;
                            var select = $('<select style="font-size: .8rem;width: 100%;"><option value="">All</option></select>')
                            .appendTo($("#filter_row").find("th").eq(column.index()))
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                                );

                                column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                            } );
                            column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                var val = $.fn.dataTable.util.escapeRegex(d);
                                if (column.search() === "^" + val + "$") {
                                select.append(
                                '<option value="' + d + '" selected="selected">' + d + "</option>"
                                );
                                } else {
                                select.append('<option value="' + d + '">' + d + "</option>");
                                }
                            });
                        });
                    }
                });
        }

        });
    </script>
@endpush
