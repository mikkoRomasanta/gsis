@extends('layouts.app')

@section('content')
    <div class="container h-100" style="padding-top: 1rem">
        <table class="table-striped" id="table-issuance">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date & Time</th>
                    <th>Action</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $adlog)
                <tr>
                    <td>{{$adlog->id}}</td>
                    <td>{{$adlog->created_at}}</td>
                    <td>{{$adlog->action}}</td>
                    <td>{{$adlog->user}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#table-issuance').DataTable({
                dom: 'Bfrtip',
                buttons: [
                            'csv', 'excel', 'print'],
                order: [[0,'desc']]
            });
        });
    </script>
@endpush
