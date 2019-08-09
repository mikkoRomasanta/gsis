@extends('layouts.app')

@section('content')
    <div class="container h-100" style="padding-top: 1rem">
        <table class="display compact table-striped" id="table-adminLogs">
            <thead>
                <tr id="filter_row" class="color-bg-link">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr class="color-bg-main color-font-dark">
                    <th>Id</th>
                    <th>Date & Time</th>
                    <th>Action1</th>
                    <th>Action2</th>
                    <th>Action3</th>
                    <th>User</th>
                    <th>Remarks</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#table-adminLogs').DataTable({
                ajax: {
                    url: "{{route('get.adminLogs')}}",
                    dataSrc: ''
                    },
                columns: [
                    {'data' : 'id'},
                    {'data' : 'created_at'},
                    {'data' : 'action1'},
                    {'data' : 'action2'},
                    {'data' : 'action3'},
                    {'data' : 'user'},
                    {'data' : 'remarks'}
                ],
				dom: "<'row'<'col-md-3'B><'col-md-1'><'col-md-5'>" +
						"<'col-md-3'f>>" +
						"<'row'<'col-md-6'><'col-md-6'>>" +
						"<'row'<'col-md-12't>>" +
						"<'row'<'col-md-3' l><'col-md-3'i><'col-md-6'p>>",
                buttons: [
                            'csv', 'excel', 'print'
                        ],
                order: [[0,'desc']],
                orderClasses: false,
                initComplete: function () {
                    this.api().columns([2,3,5]).every( function () {
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

            $val = $('#searchContent').html();
            $('input[type="search"]').val($val).keyup()
        });
    </script>
@endpush
