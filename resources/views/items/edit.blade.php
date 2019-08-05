@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <a href="javascript:history.back()" class="btn btn-secondary float-left">Back</a>
        <h3 class="text-center" style="margin: 0px">Edit item</h3>
    </div><hr>
    <div class="container h-100">
        {{ Form::open(['action' => ['ItemsController@update', $item->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="form-group form-row">
                <div class="col-6">
                {{Form::label('item_name','Item Name')}}
                {{Form::text('item_name', $item->item_name,['class' => 'form-control', 'placeholder' => 'item name'])}}
                </div>
                <div class="col-6">
                {{Form::label('item_desc','Item Description')}}
                {{Form::textarea('item_desc', $item->item_desc,['class' => 'form-control', 'placeholder' => 'item description', 'rows' => 1])}}
                </div>
            </div>
            <div class="form-group form-row">
                <div class="col-6">
                    {{Form::label('buffer_stocks', 'Buffer Stocks ('.$item->uom.')')}}
                    {{Form::number('buffer_stocks',$item->buffer_stocks,['class' => 'form-control', 'placeholder' => '0'])}}
                </div>
                <div class="col-6">
                    {{Form::label('lead_time', 'Lead Time (days)')}}
                    {{Form::number('lead_time',$item->lead_time,['class' => 'form-control', 'placeholder' => '0'])}}
                </div>
            </div>
            <div class="form-group  form-row">
                <div class="col">
                    {{Form::label('uom','Unit of Measurement')}}
                    {{Form::select('uom',$uom,$item->uom,['class' => 'form-control-sm'])}}
                    {{-- {{Form::select('uom', ['pc' => 'pc', 'ml' => 'ml'], $item->uom, ['class' => 'form-control-sm'])}}  removed ML --}}
                </div>
                <div class="col">
                    {{Form::label('payment','Payment')}}
                    {{Form::select('payment', [0 => 'flowlites', 1 => 'cprf'], $item->payment, ['class' => 'form-control-sm'])}}
                </div>
            </div>
            <div class="form-group">
                    {{Form::label('Image')}}
                    {{Form::file('image')}}
            </div>
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Submit',['class' => 'btn btn-primary'])}}
        {{ Form::close() }}
    </div>
@endsection
