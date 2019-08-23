<div class="modal fade" id="mdlEditIss" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header color-bg-main color-font-dark">
                <h4 class="modal-title" id="mdlTitle">{{'ADD | '}}</h4>
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {{ Form::open(['action' => 'IssuancesController@modify', 'method' => 'POST'])}}
                <table class="table table-sm table-bordered text-center">
                    <tr class="color-bg-secondary color-font-dark">
                        <th>Id</th>
                        <th style="width: 20%">Date & Time</th>
                        <th style="width: 30%">Item</th>
                        <th>Quantity</th>
                        <th>Received by</th>
                    </tr>
                    <tr>
                        <td id="issId"></td>
                        <td id="issDate"></td>
                        <td id="issItem"></td>
                        <td id="issQty"></td>
                        <td id="issReceived"></td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered text-center">
                    <tr class="color-bg-secondary color-font-dark">
                        <th style="width: 33%">Issued by</th>
                        <th style="width: 33%">Area</th>
                        <th style="width: 33%">Shift</th>
                    </tr>
                    <tr>
                        <td id="issIssued"></td>
                        <td id="issArea"></td>
                        <td id="issShift"></td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered text-center">
                    <tr class="color-bg-secondary color-font-dark">
                        <th>Delete</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>{{Form::checkbox('delete','', false,['class' => 'form-control-sm'])}}</td>
                        <td>{{Form::text('remarks','',['class' => 'form-control', 'placeholder' => 'reason for deletion'])}}</td>
                        <td>{{Form::submit('Delete',['class' => 'btn btn-danger btn-sm'])}}</td>
                    </tr>
                    {{Form::hidden('id','',['id' => 'formIssId'])}}
                    {{Form::hidden('qty','',['id' => 'formQtyId'])}}
                    {{Form::hidden('item_id','',['id' => 'formItemId'])}}
                </table>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
