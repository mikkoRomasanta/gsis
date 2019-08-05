<div class="modal fade" id="mdlEdit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header color-bg-main color-font-dark">
                <h4 class="modal-title" id="mdlTitle"></h4>
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {{Form::open(['action' => 'UsersController@update', 'method' => 'POST', 'class' => 'text-center'])}}
                    {{csrf_field()}}
                    <div class="form-group row">
                        {{Form::label('name', 'Name',['class' => 'col-md-3 col-form-label', 'style' => 'font-weight: bold'])}}
                        {{Form::text('name','',['id' => 'nameBox', 'class' => 'col-md-8 form-control'])}}
                    </div>
                    <div class="form-group row">
                        {{Form::label('status', 'Status',['class' => 'col-md-3 col-form-label', 'style' => 'font-weight: bold'])}}
                        {{Form::select('status',['ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'],'',['id' => 'statusBox', 'class' => 'col-md-8 form-control'])}}
                    </div>
                    <div class="form-group row">
                        {{Form::label('role', 'Role',['class' => 'col-md-3 col-form-label', 'style' => 'font-weight: bold'])}}
                        {{Form::select('role',['USER' => 'USER', 'GSADMIN' => 'GSADMIN', 'ADMIN' => 'ADMIN'],'',['id' => 'roleBox', 'class' => 'col-md-8 form-control'])}}
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            {{Form::submit('Submit',['class' => 'btn btn-primary'])}}
                        </div>
                    </div>
                    {{Form::hidden('username','',['id' => 'usernameBox'])}}
                    {{Form::hidden('id','',['id' => 'idBox'])}}
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
