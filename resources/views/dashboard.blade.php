@extends('layouts.app')

@section('content')
 <div class="container h-100">
    <div class="row h-100">
        <div class="col-md-8">
            <div class="col-md-12">
                <div class="card shadow"  style="height: 80vh;">
                    <div class="card-header color-bg-main">
                        <h4 class="card-title text-center" style="margin-bottom: 0;">Stocks</h4>
                    </div>
                    <div class="card-body color-bg-secondary">
                        <div class="row">
                            <table class="table table-sm" style="margin-right: 1rem; margin-left: 1rem">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Buffer</th>
                                        <th>Lead Time</th>
                                        <th>Depletion in</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocksLow as $item)
                                        <tr>
                                            <td><a class="color-font-link" href="{{route('items.show',$item->id)}}">{{$item->item_name}}</a></td>
                                            <td>{{$item->quantity.' '.$item->uom}}</td>
                                            <td>{{$item->buffer_stocks.' '.$item->uom}}</td>
                                            <td>{{$item->lead_time.' days'}}</td>
                                            @if($item->item_desc == 'N/A')
                                                <td class="table-secondary">{{$item->item_desc}}</td>
                                            @elseif($item->item_desc == 'Stocks depleted')
                                                <td class="table-danger">{{$item->item_desc}}</td>
                                            @elseif($item->item_desc < $item->lead_time)
                                                <td class="table-danger">{{$item->item_desc.' days'}}</td>
                                            @else
                                                <td class="table-warning">{{$item->item_desc.' days'}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{$stocksLow->links()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header color-bg-main">
                        <h5 class="card-title text-center" style="margin-bottom: 0;">Issuance Stats</h5>
                    </div>
                    <div class="card-body color-bg-secondary">
                        <div class="card-deck text-center" style="padding-left: 1rem; padding-right: 1rem;">
                            <div class="card card-show-item card-in">
                                <h6 class="card-title">Today</h6><hr>
                                {{$issToday->count()}}
                            </div>
                            <div class="card card-show-item card-in">
                                <h6 class="card-title">Week</h6><hr>
                                {{$issWeek->count()}}
                            </div>
                            <div class="card card-show-item card-in">
                                <h6 class="card-title">Month</h6><hr>
                                {{$issMonth->count()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="padding-top:.5rem;">
                <div class="card shadow">
                    <div class="card-header color-bg-main">
                        <h5 class="card-title text-center" style="margin-bottom: 0;">Receiving Stats</h5>
                    </div>
                    <div class="card-body color-bg-secondary">
                        <div class="card-deck text-center" style="padding-left: 1rem; padding-right: 1rem;">
                            <div class="card card-show-item card-in">
                                <h6 class="card-title">Today</h6><hr>
                                {{$recToday->count()}}
                            </div>
                            <div class="card card-show-item card-in">
                                <h6 class="card-title">Week</h6><hr>
                                {{$recWeek->count()}}
                            </div>
                            <div class="card card-show-item card-in">
                                <h6 class="card-title">Month</h6><hr>
                                {{$recMonth->count()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="padding-top:.5rem;">
                <div class="card shadow">
                    <div class="card-body color-bg-secondary">
                        <div class="card-deck text-center" style="padding-left: 1rem; padding-right: 1rem;">
                            <div class="card card-show-item card-in" style="background-color: #d6d8db" title='N/A'>
                                <h5 id='stat1' style="padding: .8rem;"></h5>
                            </div>
                            <div class="card card-show-item card-in" style="background-color: #ffeeba" title='Depleton in (days)'>
                                <h5 id='stat2' style="padding: .8rem;""></h5>
                            </div>
                            <div class="card card-show-item card-in" style="background-color: #f5c6cb" title='Stocks Depleted'>
                                <h5 id='stat3' style="padding: .8rem;"></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $stat1 = $('.table-secondary').length;
            $('#stat1').html($stat1);
            $stat2 = $('.table-warning').length;
            $('#stat2').html($stat2);
            $stat3 = $('.table-danger').length;
            $('#stat3').html($stat3);
        });
    </script>
@endpush
