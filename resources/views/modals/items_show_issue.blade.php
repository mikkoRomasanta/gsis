<div class="modal fade" id="mdlIssue" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header color-bg-main color-font-dark">
                <h4 class="modal-title" id="mdlTitle">{{'ISSUE | '.$item->item_name}}</h4>
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                    {{ Form::open(['action' => ['IssuancesController@store'], 'method' => 'POST', 'class' => 'form-group']) }}
                    <table class="table table-sm table-bordered text-center" id="tbl-issue">
                        <tr>
                            {{-- <th style="width: 28%">Item</th> --}}
                            <th style="width: 20%">Quantity</th>
                            <th style="width: 30%">Area</th>
                            <th style="width: 10%">Shift</th>
                            <th style="width: 20%">Received by</th>
                            <th style="width: 12%">Action</th>
                        </tr>
                        <tr>
                            <td hidden="true">{{Form::select('item_id', [$item->id => $item->item_name], 0, ['class' => 'form-control-sm', 'readonly'])}}</td>
                            <td>{{Form::number('quantity','',['class' => 'form-control form-control-sm', 'placeholder' => '0', 'step' => '0.25'])}}</td>
                            <td>{{Form::select('area',$areas,null,['class' => 'form-control-sm'])}}</td>
                            <td>{{Form::select('shift', ['D/S' => 'D/S', 'N/S' => 'N/S'], 0, ['class' => 'form-control-sm'])}}</td>
                            <td>{{Form::text('received_by','' ,['class' => 'form-control form-control-sm', 'placeholder' => 'receipient',])}}</td>
                            <td>{{Form::submit('Submit',['class' => 'btn btn-primary btn-sm'])}}</td>
                        </tr>
                        {{Form::hidden('issued_by', Auth::user()->username,['class' => 'form-control-sm'])}}
                    </table>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
