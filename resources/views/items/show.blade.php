@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
        {{-- <a href="/items/{{$item->id}}/issue" class="btn btn-success float-right" style="margin-left:.5rem;">Issue</a> --}}
        <button type="button" class="btn btn-success float-right" style="margin-left: .5rem;" data-toggle="modal" data-target="#mdlAdd">Add</button>
        <button type="button" class="btn btn-warning float-right" style="color: white" data-toggle="modal" data-target="#mdlIssue">Issue</button>
    </div>
    <hr>
    <div class="container">
        <div class="row">
            @if($item->image != 'noimage.jpg')
                <div class="col-md-3 col-sm-3 text-center"><img style="height: 10rem; width: 10rem" src="/storage/images/uploads/{{$item->image}}"></div>
            @else
                <div class="col-md-3 col-sm-3 text-center"><img style="height: 10rem; width: 10rem" src="/{{$item->image}}"></div>
            @endif
            <div class="col-md-4 col-sm-4 my-auto text-center"><h2 id="itemName">{{$item->item_name}}</h2><hr><small>{{$item->item_desc}}</small></div>
            <div class="col-md-5 col-sm-5 my-auto text-center">
                <div class="card-deck" style="padding-left: 1rem; padding-right: 1rem;">
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Stocks</h6><hr>
                        <div>{{$item->quantity.' '.$item->uom}}</div>
                    </div>
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Buffer</h6><hr>
                        <div>{{$item->buffer_stocks.' '.$item->uom}}</div>
                    </div>
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Lead Time</h6><hr>
                        <div>{{$item->lead_time}} days</div>
                    </div>
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Payment</h6><hr>
                        <div>{{$item->payment}}</div>
                    </div>
                </div>
                <div class="card-deck" style="padding-left: 1rem; padding-right: 1rem;" title="Stats for {{$stats['month']}}">
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Beginning</h6><hr>
                    <div>{{$stats['beg'].' '.$item->uom}}</div>
                    </div>
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Received</h6><hr>
                        <div>{{$stats['rec'].' '.$item->uom}}</div>
                    </div>
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Ending</h6><hr>
                        <div>{{$stats['end'].' '.$item->uom}}</div>
                    </div>
                    <div class="card card-show-item color-bg-secondary shadow-sm">
                        <h6 class="card-title text-center">Issuance</h6><hr>
                        <div>{{$stats['ave'].'/day'}}</div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-7 col-sm-7 text-center table-responsive">
                <h3><a href='/issuances?s={{$item->item_name}}' class='color-font-link'>Issuance</a></h3>
                <table class="table table-striped table-bordered table-sm text-center" style="font-size: .9rem;">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Date & Time</th>
                            {{-- <th scope="col">Item</th> --}}
                            <th scope="col">Qty</th>
                            <th scope="col">Received by</th>
                            <th scope="col">Issued by</th>
                            <th scope="col">Area</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issuances as $iss)
                            <tr>
                                <td>{{$iss->id}}</td>
                                <td>{{$iss->created_at}}</td>
                                {{-- <td>{{$iss->Item->item_name}}</td> --}}
                                <td>{{$iss->quantity.' '.$item->uom}}</td>
                                <td>{{$iss->received_by}}</td>
                                <td>{{$iss->issued_by}}</td>
                                <td>{{$iss->Area->area_name}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-5 col-sm-5 text-center table-responsive">
                <h3><a href='/receivings?s={{$item->item_name}}' class='color-font-link'>Receiving</a></h3>
                <table class="table table-striped table-bordered table-sm text-center" style="font-size: .9rem;">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Date & Time</th>
                            {{-- <th scope="col">Item</th> --}}
                            <th scope="col">Qty</th>
                            <th scope="col">Received by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receivings as $rec)
                            <tr>
                                <td>{{$rec->id}}</td>
                                <td>{{$rec->created_at}}</td>
                                {{-- <td>{{$rec->Item->item_name}}</td> --}}
                                <td>{{$rec->quantity.' '.$item->uom}}</td>
                                <td>{{$rec->received_by}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            @if(Auth::user()->role != 'USER')
                <a href="/items/{{$item->id}}/edit" class="btn btn-info float-left">Edit</a>
                {{Form::open(['action' => ['ItemsController@destroy', $item->id], 'method' => 'POST', 'class' => 'float-right', 'onclick' =>  'return confirm("Are you sure?")' ])}}
                    {{Form::hidden('_method', 'DELETE')}}

                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                {{Form::close()}}
            @endif
    </div>
@endsection

@section('modal')
    @include('modals.items_show_add')
    @include('modals.items_show_issue')
@endsection
