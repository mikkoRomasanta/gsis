@extends('layouts.app')

@section('content')
    <p hidden='true' id='searchContent'>{{$search}}</p>
    <div class="container h-100">
        <table class="display compact table-striped" id="table-receivings">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date & Time</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Received by</th>
                </tr>
            </thead>
            {{-- <tbody>
                @foreach($query as $rec)
                <tr>
                    <td>{{$rec->id}}</td>
                    <td>{{$rec->created_at}}</td>
                    <td>{{$rec->item_name}}</td>
                    <td>{{$rec->quantity.' '.$rec->uom}}</td>
                    <td>{{$rec->received_by}}</td>
                </tr>
                @endforeach
            </tbody> --}}
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-receivings').DataTable({
                ajax: {
                    url: "{{route('get.rec')}}",
                    dataSrc: ''
                    },
                columns: [
                    {'data' : 'id'},
                    {'data' : 'created_at'},
                    {'data' : 'item_name'},
                    {'data' : 'quantity'},
                    {'data' : 'received_by'}
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
