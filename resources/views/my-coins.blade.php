<x-layout>
    @slot('title','Shrimpy - My Coins')
    <div class="card">
        <div class="card-header">
            <div class="flex">
                <div class="float-left">
                    <h3>My Coins</h3>
                </div>
                <div class="float-right">
                    <button class="btn btn-primary" id="add-coin">
                        Add Coin
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
                                <th>Coin Percent</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($my_coins as $coin)
                                <tr>
                                    <td>{{$coin->binance_coin->name}}</td>
                                    <td>{{$coin->binance_coin->symbol}}</td>
                                    <td>{{$coin->percent}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-success"
                                               href="{{route('edit-coin',['coin_id'=>$coin->id])}}"><i
                                                    class="far fa-pen"></i></a>
                                            <a class="btn btn-danger" onclick="deleteCoin('{{$coin->id}}')"
                                               href="#"><i class="far fa-trash"></i></a>
                                        </div>
                                    </td>
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
        $('#add-coin').click(function () {
            location.href = '{{route('add-coin')}}'
        });
        function deleteCoin(coin_id){
            if(confirm('Do you want to delete the coin')){
                let url = '{{route('delete-coin',['coin_id'=>'coin-id'])}}'
                url = url.replace('coin-id',coin_id);
                location.href = url;
            }
        }
    </script>
</x-layout>
