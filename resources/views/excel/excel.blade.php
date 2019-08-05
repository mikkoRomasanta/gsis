@extends('layouts.app')

@section('content')

    <div class="container h-100">
        <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
    </div>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Export</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <a class="btn color-btn-link" href="{{ route('export.items') }}">Items</a>
            </div>
        </div>
    </div>

    <hr>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Import</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <a class="btn color-btn-link" id="issImportButton">Issuance</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <div class="card-body importFormStyle" id="issImportForm">
                    <form action="{{ route('import.issuances') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control-sm">
                        <button class="btn btn-primary btn-sm">Import Issuance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <table class="table table-sm table-bordered importFormStyle" id="issImportTable">
                    <thead>
                        <th>Item Id</th>
                        <th>Quantity</th>
                        <th>Area code</th>
                        <th>Shift</th>
                        <th>Issued By</th>
                        <th>Received By</th>
                        <th>Date & Time</th>
                    </thead>
                    <tbody>
                        <td>1</td>
                        <td>5</td>
                        <td>3</td>
                        <td>N/S</td>
                        <td>Juan</td>
                        <td>Pedro</td>
                        <td>2019-07-30</td>
                    </tbody>
                </table>
                <a class="btn color-btn-link " id="issImportSample" style="display:none" href="{{ asset('/storage/downloads/issTemplate.csv') }}">Download template</a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#issImportButton').click(function(){
                $('#issImportForm').slideToggle(250);
                $('#issImportTable').slideToggle(250);
                $('#issImportSample').slideToggle(250);
            });
        });
    </script>
@endpush
