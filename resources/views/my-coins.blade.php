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
                                <th>Action</th>
                                <th>Coin Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($my_coins as $coin)
                                <tr>
                                    <td>{{$coin->binance_coin->name}}</td>
                                    <td>{{$coin->binance_coin->symbol}}</td>
                                    <td>{{$coin->percent}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">{{$coin->split_shrimpy->action ?? 'Choose Action'}}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="#" class="dropdown-item" onclick="changeAction('{{$coin->id}}','send')">Send</a>
                                                <a href="#" class="dropdown-item" onclick="changeAction('{{$coin->id}}','convert')">Convert</a>
                                                <a href="#" class="dropdown-item" onclick="changeAction('{{$coin->id}}','remove')">Remove</a>
                                            </div>
                                        </div>
                                    </td>
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

        function changeAction(coin_id,action){
            let url = '{{route('update-action',['coin_id'=>'coin-id','action'=>'action_name'])}}'
            url = url.replace('coin-id', coin_id);
            url = url.replace('action_name', action);
            url = url.replace('&amp;', '&')
            location.href = url;
        }

        function deleteCoin(coin_id) {
            if (confirm('Do you want to delete the coin')) {
                let url = '{{route('delete-coin',['coin_id'=>'coin-id'])}}'
                url = url.replace('coin-id', coin_id);
                location.href = url;
            }
        }

    </script>
</x-layout>
