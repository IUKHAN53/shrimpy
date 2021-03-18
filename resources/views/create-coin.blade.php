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

                    <form method="POST" action="{{route('add-coin')}}">
                        @csrf
                        <div class="form-group">
                            <label for="binance_coin">Binance Coin</label>
                            <select class="form-control" name="binance_coin_id" id="binance_coin">
                                <option value="">Select Coin</option>
                                @foreach($b_coins as $coin)
                                    <option value="{{$coin->id}}">{{$coin->symbol}} - <span class="small">({{$coin->name}})</span></option>
                                @endforeach
                            </select>
                            @error('binance_coin_id')
                            <p style="color: red">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label for="percent">Percent</label>
                            <input type="number" class="form-control" max="100" min="0" id="percent" name="percent" placeholder="Enter Coin Percent">
                            @error('percent')
                            <p style="color: red">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</x-layout>
