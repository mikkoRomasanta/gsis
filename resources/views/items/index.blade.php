@extends('layouts.app')

@section('content')

    <div class="container h-100">
        @if(!Auth::guest()) <!-- If user is not a guest show Add items button -->
            @if(Auth::user()->role != 'USER')
                <a class="btn btn-primary" href="/items/create">Add Items</a>
            @endif
            <a class="btn btn-success" href="/items/receivemulti">Add Stocks</a>
            <a class="btn btn-warning" href="/items/issuemulti" style="color: white">Issue</a>
            {{-- <a class="btn btn-secondary float-right" href="/items?view='dt'">Switch View</a> --}}
            <label>View:</label>
            <select id='switchView' class="form-control-sm">
                <option value='0'>Cards</option>
                <option value='1'>Table</option>
            </select>
        @endif
        <form action="{{route('items.index')}}" method="get" role="search" class="form-inline float-right">
            <div class="form-group">
                <input type="text" class="form-control" name="s" placeholder="Keyword" value="{{ isset($s) ? $s : '' }}">
            </div>

            <div class="form-group">
                <button class="btn btn-info" type="submit">Search</button>
            </div>
        </form>

    </div>
    <hr>

    @if(count($items) > 0)
        <div class="container">
            @foreach($items->chunk(3) as $chunk)
                <div class="row" style="margin-bottom: .5rem;">
                    <?php $i = 1; ?>
                        @foreach($chunk as $item)
                            @if($i <= 3)
                                <div class="col-md-4 col-sm-4">
                                <?php $i++; ?>
                                    <div class="card card-body bg-light h-100" style="padding: 1rem;">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-3">
                                                @if($item->image != 'noimage.jpg')
                                                    <img style="height: 3.125rem; width: 3.125rem" src="/storage/images/uploads/{{$item->image}}">
                                                    @else
                                                    <img style="height: 3.125rem; width: 3.125rem" src="/{{$item->image}}">
                                                @endif
                                            </div>
                                            <div class="col-md-7 col-sm-7">
                                                <h6><a class="color-font-link" href="{{route('items.show',$item->id)}}">{{$item->item_name}}</a></h6>
                                                <small>{{str_limit($item->item_desc,25)}}</small>
                                            </div>
                                            <div class="col-md-3 col-sm-2 text-right">
                                                <strong class="color-font-secondary">{{$item->quantity}}<br></strong>
                                                <small class="color-font-secondary">{{strtolower($item->uom)}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                </div>
            @endforeach

            {{$items->appends(['s' => $s])->links()}}
        </div>
    @else
        <p>No available item.</p>
    @endif

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('select#switchView').change(function(){
                if($(this).val() == 0){
                    window.location.href = '/items'
                }
                else{
                    window.location.href = '/items?view=dt'
                }
            });
        });
    </script>
@endpush
