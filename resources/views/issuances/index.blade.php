@extends('layouts.app')

@section('content')
    <p hidden='true' id='searchContent'>{{$search}}</p>
    <div class="container h-100">
        <table class="display compact table-striped" id="table-issuance">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date & Time</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Received by</th>
                    <th>Issued by</th>
                    <th>Area</th>
                    <th>Shift</th>
                </tr>
            </thead>
            {{-- <tbody>
                @foreach($query as $iss)
                <tr>
                    <td>{{$iss->id}}</td>
                    <td>{{$iss->created_at}}</td>
                    <td>{{$iss->item_name}}</td>
                    <td>{{$iss->quantity.' '.$iss->uom}}</td>
                    <td>{{$iss->received_by}}</td>
                    <td>{{$iss->issued_by}}</td>
                    <td>{{$iss->area_name}}</td>
                </tr>
                @endforeach
            </tbody> --}}
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
                    {'data' : 'created_at'},
                    {'data' : 'item_name'},
                    {'data' : 'quantity'},
                    {'data' : 'received_by'},
                    {'data' : 'issued_by'},
                    {'data' : 'area_name'},
                    {'data' : 'shift'},
                ],
				dom: "<'row'<'col-md-3'B><'col-md-1'><'col-md-5'>" +
						"<'col-md-3'f>>" +
						"<'row'<'col-md-6'><'col-md-6'>>" +
						"<'row'<'col-md-12't>>" +
						"<'row'<'col-md-3' l><'col-md-3'i><'col-md-6'p>>",
                buttons: [
                            'csv', 'excel', 'print'
                        ],
                order: [[0,'desc']]
            });

            $val = $('#searchContent').html();
            $('input[type="search"]').val($val).keyup()

        });
    </script>
@endpush
{{-- $('#table-issuance').DataTable({
    ajax: {
        url: "{{route('get.iss')}}",
        dataSrc: ''
        },
    columns: [
        {'data' : 'id'},
        {'data' : 'created_at'},
        {'data' : 'item_name'},
        {'data' : 'quantity'},
        {'data' : 'received_by'},
        {'data' : 'issued_by'},
        {'data' : 'area_name'},
    ],
    dom: 'Bfrtip',
    buttons: [
                'csv', 'excel', 'print'
            ],
    order: [[0,'desc']]
}); --}}

    {{-- $('#table-issuance').DataTable({
        dom: 'Bfrtip',
        buttons: [
                    'csv', 'excel', 'print'],
        order: [[0,'desc']]
    }); --}}
