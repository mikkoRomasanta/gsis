<div class="modal fade" id="mdlAdd" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header color-bg-main color-font-dark">
                <h4 class="modal-title" id="mdlTitle">{{'ADD | '.$item->item_name}}</h4>
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                    {{ Form::open(['action' => ['ReceivingsController@store'], 'method' => 'POST']) }}
                    <table class="table table-sm table-bordered text-center" id="tbl-issue">
                        <tr>
                            {{-- <th style="width: 28%">Item</th> --}}
                            <th style="width: 20%">Quantity</th>
                            <th style="width: 20%">Uom</th>
                            <th style="width: 25%">Received by</th>
                            <th style="width: 22%">Action</th>
                        </tr>
                        <tr>
                            <td hidden="true">{{Form::select('item_id', [$item->id => $item->item_name], 0, ['class' => 'form-control-sm', 'readonly'])}}</td>
                            <td>{{Form::number('quantity','',['class' => 'form-control form-control-sm', 'placeholder' => '0', 'min' => 0])}}</td>
                            <td>{{Form::text('uom',$item->uom,['class' => 'form-control form-control-sm', 'id' => 'uom', 'readonly'])}}</td>
                            <td>{{Form::text('received_by', Auth::user()->name.' ['.Auth::user()->username.']',['class' => 'form-control form-control-sm', 'placeholder' => 'name', 'readonly'])}}</td>
                            <td>{{Form::submit('Submit',['class' => 'btn btn-primary btn-sm'])}}</td>
                        </tr>
                    </table>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
