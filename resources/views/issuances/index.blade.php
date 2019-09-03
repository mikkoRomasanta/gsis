@extends('layouts.app')


@if(Auth::user()->role != 'USER')
    @section('content')
        <p hidden='true' id='searchContent'>{{$search}}</p>
        <div class="container h-100">
            <table class="display compact table-striped" id="table-issuance" style="font-size: .95rem">
                <thead>
                    <tr id="filter_row" class="color-bg-link">
                        <th></th>
                        <th></th>
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
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Received by</th>
                        <th>Issued by</th>
                        <th>Area</th>
                        <th>Shift</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endsection

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {

                $('#table-issuance').DataTable({
                    ajax: {
                        url: "{{route('get.iss')}}",
                        dataSrc: ''
                        },
                    columns: [
                        {'data' : 'id'},
                        {
                            'data' : 'created_at',
                            "render": function ( data, type, row, meta ) {
                                if(data === null){
                                    return '';
                                }
                                else{
                                    return type === 'display' && data.length > 10 ?
                                    '<span title="'+data+'">'+data.substr( 0, 10 )+'</span>' :
                                    data;
                                }
                            }
                        },
                        {'data' : 'item_name'},
                        {'data' : 'quantity'},
                        {'data' : 'received_by'},
                        {
                            'data' : 'issued_by',
                            "render": function ( data, type, row, meta ) {
                                if(data === null){
                                    return '';
                                }
                                else{
                                    var username = data.indexOf('[')-1;
                                    return type === 'display' && data.length > username ?
                                    '<span title="'+data+'">'+data.substr( 0, username )+'</span>' :
                                    data;
                                }
                            }
                        },
                        {'data' : 'area_name'},
                        {'data' : 'shift'},
                        {
                            "data": null,
                            orderable: false,
                            render: function ( data, type, row ) {
                            var btn = '<button class="btn btn-sm color-btn-link" data-toggle="modal" data-target="#mdlEditIss"><i class="fas fa-trash-alt fa-fw"></i></button>'
                            return btn;
                        }
                        },
                    ],
                    dom: "<'row'<'col-md-8'B><'col-md-1'>" +
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
                        this.api().columns([2,5,6,7]).every( function () {
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

                $('#issGoBtn').click(function(){
                    issId = $('#issuance_id').val();
                    alert('test'+issId);
                });

                $( '#mdlEditIss' ).on( 'show.bs.modal', function (e) {
                    var target = e.relatedTarget;
                    var tr = $( target ).closest('tr');
                    var tds = tr.find('td');

                    $('#mdlTitle').html('Delete | Issuance #'+tds.eq(0).text());
                    $('#issId').html(tds.eq(0).text());
                    $('#issDate').html(tds.eq(1).text());
                    $('#issItem').html(tds.eq(2).text());
                    $('#issQty').html(tds.eq(3).text());
                    $('#issReceived').html(tds.eq(4).text());
                    $('#issIssued').html(tds.eq(5).text());
                    $('#issArea').html(tds.eq(6).text());
                    $('#issShift').html(tds.eq(7).text());
                    $('#formIssId').val(tds.eq(0).text());
                    $('#formItemId').val(tds.eq(2).text());
                    $('#formQtyId').val(tds.eq(3).text());
                });

            });
        </script>
    @endpush

    @section('modal')
        @include('modals.issuances_edit')
    @endsection

@else
    @include('issuances.userindex')
@endif
