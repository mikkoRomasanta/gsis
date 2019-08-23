@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <a href="{{URL::previous()}}" class="btn btn-secondary float-left">Back</a>
        <button class="btn btn-success float-right" id="addBtn">Add Row</button>
        <h3 class="text-center" style="margin: 0px">Add new item/s</h3>
    </div><hr>
    <div class="container">
        {{ Form::open(['action' => 'ItemsController@store', 'method' => 'POST', 'name' => 'add_items']) }}
            <table class="table table-sm table-bordered text-center" id="tbl-create">
                <tr>
                    <th style="width: 30%">Item Name</th>
                    <th style="width: 25%">Item Desc</th>
                    <th style="width: 10%">Buffer Stocks</th>
                    <th style="width: 13">Lead Time</th>
                    <th style="width: 10%">UOM</th>
                    <th style="width: 12%">Payment</th>
                    <th style="width: 3%">Del</th>
                </tr>
                <tr>
                    <td>{{Form::text('item_name[]','',['class' => 'form-control', 'placeholder' => 'item name'])}}</td>
                    <td>{{Form::textarea('item_desc[]','',['class' => 'form-control', 'placeholder' => 'item description', 'rows' => 1])}}</td>
                    <td>{{Form::number('buffer_stocks[]',0,['class' => 'form-control', 'placeholder' => '0'])}}</td>
                    <td>{{Form::number('lead_time[]',30,['class' => 'form-control', 'placeholder' => '0'])}}</td>
                    <td>{{Form::select('uom[]',$uom,null,['class' => 'form-control'])}}</td>
                    <td>{{Form::select('payment[]', [0 => 'flowlites', 1 => 'cprf'], 0, ['class' => 'form-control'])}}</td>
                    <td></td>
                </tr>
            </table>
            {{Form::submit('Submit',['class' => 'btn btn-primary'])}}
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var i=1;
            $('#addBtn').click(function(){
                i++;
                $('#tbl-create').append('<tr id="row'+i+'"><td>{{Form::text('item_name[]','',['class' => 'form-control', 'placeholder' => 'item name'])}}</td><td>{{Form::textarea('item_desc[]','',['class' => 'form-control', 'placeholder' => 'item description', 'rows' => 1])}}</td><td>{{Form::number('buffer_stocks[]',0,['class' => 'form-control', 'placeholder' => '0'])}}</td> <td>{{Form::number('lead_time[]',30,['class' => 'form-control', 'placeholder' => '0'])}}</td><td>{{Form::select('uom[]',$uom,null,['class' => 'form-control'])}}</td><td>{{Form::select('payment[]', [0 => 'flowlites', 1 => 'cprf'], 0, ['class' => 'form-control'])}}</td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger">-</button></td></tr>');
            });

            $(document).on('click', '.btn-danger', function(){
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });

            $('#submit').click(function(){
                $.ajax({
                        url:postURL,
                        method:"POST",
                        data:$('#receive_stocks').serialize(),
                        type:'json',
                        success:function(data)
                        {
                            if(data.error){
                                printErrorMsg(data.error);
                            }else{
                                i=1;
                                $('#receive_stocks')[0].reset();
                                $(".print-success-msg").find("ul").html('');
                                $(".print-success-msg").css('display','block');
                                $(".print-error-msg").css('display','none');
                                $(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');
                            }
                        }
                });
            });


            function printErrorMsg (msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','block');
                $(".print-success-msg").css('display','none');
                $.each( msg, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }
        });
    </script>
@endpush
