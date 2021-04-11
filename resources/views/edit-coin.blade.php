<x-layout>
    @slot('title','Shrimpy - Add Coins')
    <div class="card">
        <div class="card-header">
            <div class="flex">
                <div class="float-left">
                    <h3>Create Coin</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">

                    <form method="POST" action="{{route('edit-coin',['coin_id'=>$my_coin->id])}}">
                        @csrf
                        <div class="form-group">
                            <label for="binance_coin">Binance Coin</label>
                            <select class="form-control" name="binance_coin_id" id="binance_coin">
                                <option value="">Select Coin</option>
                                @foreach($b_coins as $coin)
                                    <option value="{{$coin->id}}" {{($my_coin->binance_coin->id == $coin->id)?'selected':''}}>{{$coin->name}}</option>
                                @endforeach
                            </select>
                            @error('binance_coin_id')
                            <p style="color: red">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label for="conversion_coin">Conversion Coin (Optional)</label>
                            <select class="form-control" name="conversion_coin" id="conversion_coin">
                                <option value="">Select Conversion Coin</option>
                                @foreach($b_coins as $coin)
                                    <option value="{{$coin->symbol}}" {{(old('conversion_coin') == $coin->symbol)?'selected':''}}>{{$coin->symbol}} - <span class="small">({{$coin->name}})</span></option>
                                @endforeach
                            </select>
                            @error('conversion_coin')
                            <p style="color: red">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label for="action">Action</label>
                            <select type="number" class="form-control" name="action" id="action">
                                <option value="" {{($my_coin->action == null)?'selected':''}}>Select Action</option>
                                <option value="send" {{($my_coin->action == 'send')?'selected':''}}>Send</option>
                                <option value="convert" {{($my_coin->action == 'convert')?'selected':''}}>Convert</option>
                                <option value="remove" {{($my_coin->action == 'remove')?'selected':''}}>Remove</option>
                            </select>
                            @error('action')
                            <p style="color: red">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</x-layout>
