@extends('layouts.app')

@section('content')

    <div class="container h-100">
        <table class="display compact table-striped" id="table-items">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Stocks</th>
                    <th>Buffer Stocks</th>
                    <th>Uom</th>
                    <th>Lead Time</th>
                    <th>Payment</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#table-items').DataTable({
                ajax: {
                    url: "{{route('get.items')}}",
                    dataSrc: ''
                    },
                columns: [
                    {
                        'data' : 'id',
                        render: function(data, type, row, meta){
                            if(type === 'display'){
                                data = '<a href="/items/' + data + '">'+ data + '</a>'
                            }
                            return data;
                        }
                    },
                    {'data' : 'item_name'},
                    {
                        'data' : 'item_desc',
                        "render": function ( data, type, row, meta ) {
                            if(data === null){
                                return '';
                            }
                            else{
                                return type === 'display' && data.length > 30 ?
                                '<span title="'+data+'">'+data.substr( 0, 28 )+'...</span>' :
                                data;
                            }
                        }
                    },
                    {'data' : 'quantity'},
                    {'data' : 'buffer_stocks'},
                    {'data' : 'uom'},
                    {'data' : 'lead_time'},
                    {
                        'data' : 'payment',
                        'render': function (data, type, row, meta){
                            if(data === 0){
                                return 'Flowlites';
                            }
                            else if(data === 1){
                                return 'CPRF';
                            }
                            else{
                                return 'Error';
                            }
                        }
                    },
                ],
				dom: "<'row'<'col-md-7'B><'col-md-2 switchView'>" +
						"<'col-md-3'f>>" +
						"<'row'<'col-md-6'><'col-md-6'>>" +
						"<'row'<'col-md-12't>>" +
						"<'row'<'col-md-3' l><'col-md-3'i><'col-md-6'p>>",
                buttons: [
                            'csv', 'excel', 'print',
                        {
                            text: 'Add Items',
                            className: 'dt-middle-button',
                            action: function ( e, dt, node, config ) {
                            location.href='/items/create';
                            }
                        },
                        {
                            text: 'Add Stocks',
                            action: function ( e, dt, node, config ) {
                            location.href='/items/receivemulti';
                            }
                        },
                        {
                            text: 'Issue',
                            action: function ( e, dt, node, config ) {
                            location.href='/items/issuemulti';
                            }
                        },
                ],
                order: [[0,'asc']]
            });

            $('<div>' +
                '<label>View: &nbsp; </label>' +
                '<select id="switchView" class="form-control-sm">' +
                '<option value="1">Table</option>' +
                '<option value="0">Cards</option>' +
                '</select>' +
                '</div>').appendTo(".switchView");

            $('select#switchView').change(function(){
                if($(this).val() == 0){
                    window.location.href = '/items'
                }
                else{
                    window.location.href = '/items?view=dt'
                }
            });

        });
    </script>
@endpush
