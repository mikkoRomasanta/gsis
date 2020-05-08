@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <a href="{{URL::previous()}}" class="btn btn-secondary">Back</a>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header color-bg-main">
                    <h4 class="card-title text-center" style="margin-bottom: 0;">Register</h4>
                </div>
                <div class="card-body color-bg-secondary">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="id" class="col-md-3 col-form-label"><strong>Employee</strong></label>
                            <div class="col-md-9">
                                <input id="flexdata" type="text" class="form-control" name="id" value="{{ old('id') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-md-3 col-form-label"><strong>Role</strong></label>
                            <div class="col-md-9">
                                <select class="form-control" name="role">
                                    <option value="USER">USER</option>
                                    <option value="GSADMIN">GS-ADMIN</option>
                                    @if(Auth::user()->role == 'ADMIN')
                                        <option value="ADMIN">ADMIN</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                        Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $('#flexdata').flexdatalist({ //choose user to add to process
            minLength: 1,
            data: '{{url("getAllEmp")}}',
            searchIn: ['emp_id','first_name','last_name'],
            valueProperty: 'id',
            visibleProperties: ['emp_id','first_name','last_name'],
            selectionRequired: true,
            searchContain: true
        });

    });
</script>


@endpush