<x-layout>
    @slot('title','Shrimpy - Binance Coins')

    <div class="card">
        <div class="card-header">
            <div class="flex">
                <div class="float-left">
                    <h3>Binance Coins</h3>
                </div>
                <div class="float-right">
                    <span class="m-3">Last Updated: {{($coins->first())?$coins->first()->updated_at:''}}</span>
                    <button class="btn btn-primary" id="get_binance_coins">
                        Sync Binance Coins
                    </button>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <table class="table table-striped table-advance table-hover" id="my_table">
                            <thead>
                            <tr>
                                <th>Coin Name</th>
                                <th>Coin Symbol</th>
                                <th>Price USD</th>
                                <th>Change 24H</th>
                                <th>High</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coins as $coin)
                                <tr>
                                    <td>{{$coin->name}}</td>
                                    <td>{{$coin->symbol}}</td>
                                    <td>{{$coin->price_usd}}</td>
                                    <td>{{$coin->percent_change_24_h_usd}}</td>
                                    <td>{{$coin->high}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#get_binance_coins').click(function () {
            location.href = '{{route('sync-binance-coins')}}';
        });
    </script>
</x-layout>
