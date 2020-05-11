@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <a href="{{URL::previous()}}" class="btn btn-secondary float-left">Back</a>
        <button class="btn btn-success float-right" id="addBtn">Add Row</button>
        <h3 class="text-center" style="margin: 0px">Issue Stocks</h3>
    </div><hr>
    <div class="container">
        {{ Form::open(['action' => ['IssuancesController@storeMulti'], 'method' => 'POST', 'name' => 'receive_stocks']) }}
            <table class="table table-sm table-bordered text-center" id="tbl-receive">
                <tr>
                    <th style="width: 30%">Item</th>
                    <th style="width: 8%">Stocks</th>
                    <th style="width: 10%">Quantity</th>
                    <th style="width: 20%">Area</th>
                    <th style="width: 9%">Shift</th>
                    <th style="width: 15%">Received by</th>
                    <th style="width: 8%">Delete</th>
                </tr>
                <tr>
                    <td style="text-align: center">{{Form::select('item_id[]', $items, null,['class' => 'form-control', 'id' => 'item_id', 'placeholder' => 'choose an item...', 'style' => 'background-color: #e9ecef'])}}</td>
                    <td>{{Form::text('stocks[]','',['class' => 'form-control', 'id' => 'stocks', 'readonly'])}}</td>
                    <td>{{Form::number('quantity[]','',['class' => 'form-control', 'placeholder' => '0', 'step' => '0.25', 'min' => 0])}}</td>
                    <td>{{Form::select('area[]',$areas,null,['class' => 'form-control'])}}</td>
                    <td>{{Form::select('shift[]', ['D/S' => 'D/S', 'N/S' => 'N/S'], 0, ['class' => 'form-control'])}}</td>
                    <td>{{Form::text('received_by[]', '',['class' => 'form-control', 'placeholder' => 'receipient'])}}</td>
                    <td></td>
                </tr>
                {{Form::hidden('issued_by', Auth::user()->first_name.' '.Auth::user()->last_name.' ['.Auth::user()->emp_id.']',['class' => 'form-control-sm'])}}
            </table>
            {{Form::submit('Submit',['class' => 'btn btn-primary float-right'])}}
        {{Form::close()}}
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var i=1;
            $('#addBtn').click(function(){
                i++;
                $('#tbl-receive').append('<tr id="row'+i+'"><td style="text-align: center">{{Form::select('item_id[]', $items, null,['class' => 'form-control', 'id' => 'item_id', 'placeholder' => 'choose an item...', 'style' => 'background-color: #e9ecef'])}}</td><td>{{Form::text('stocks[]','',['class' => 'form-control', 'id' => 'stocks', 'readonly'])}}</td><td>{{Form::number('quantity[]','',['class' => 'form-control', 'placeholder' => '0', 'step' => '0.25'])}}</td><td>{{Form::select('area[]',$areas,null,['class' => 'form-control'])}}</td> <td>{{Form::select('shift[]', ['D/S' => 'D/S', 'N/S' => 'N/S'], 0, ['class' => 'form-control'])}}</td><td>{{Form::text('received_by[]', '',['class' => 'form-control', 'placeholder' => 'receipient'])}}</td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger">-</button></td></tr>');
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

            $(document).on("change", "#item_id", function(){
                val = $(this).val();
                stockBox = $(this).closest('tr').find('#stocks');
                itemBox = $(this).closest('tr').find('#item_id');
                $.ajax({
                    url: '/getItemQuantity/' + val,
                    type: 'get',
                    data: {},
                    success: function(data){
                        if(data.success == true){
                            $stock = data.quantity;
                            $(stockBox).val($stock);
                        }
                        else{
                            alert('No data');
                        }
                    }
                });

                if(val == ''){
                    $(itemBox).css({'background-color': '#e9ecef'}); //disabled color if placeholder is selected
                }
                else{
                    $(itemBox).css({'background-color': '#ffffff'});
                }
            });

        });
    </script>
@endpush
