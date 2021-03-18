<x-layout>
    @slot('title','Shrimpy Home')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    All Actions
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="ml-2">
                            <button class="btn btn-outline-primary" id="get_accounts">
                                <span class="font-weight-bold">Get Accounts</span>
                            </button>
                        </div>
                        <div class="ml-2">
                            <button class="btn btn-outline-success" id="get_portfolio">
                                <span class="font-weight-bold">Get Portfolio</span>
                            </button>
                        </div>
                        <div class="ml-2">
                            <button class="btn btn-outline-info" id="update_portfolio">
                                <span class="font-weight-bold">Update Portfolio</span>
                            </button>
                        </div>
                        <div class="ml-2">
                            <button class="btn btn-outline-dark" id="run_auto_trade">
                                <span class="font-weight-bold">Run Auto Trade Script</span>
                            </button>
                        </div>
                    </div>
                </div>
                @if($accounts)
                    <div class="card-body">
                        <h4>Accounts</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <section class="panel">
                                    <table class="table table-striped table-advance table-hover">
                                        <tbody>
                                        <tr>
                                            <th>Account ID</th>
                                            <th>Exchange</th>
                                            <th>Is Rebalancing</th>
                                        </tr>
                                        @foreach($accounts_data as $account)
                                            <tr>
                                                <td>{{$account->id}}</td>
                                                <td>{{$account->exchange}}</td>
                                                <td>{{($account->isRebalancing)?'YES':'NO'}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </div>
                @endif
                @if($portfolios)
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <section class="panel">
                                    <table class="table table-striped table-advance table-hover">
                                        <tbody>
                                        <tr>
                                            <th>Portfolio ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Strategy</th>
                                            <th>Rebalance Period</th>
                                        </tr>
                                        @foreach($portfolio_data as $portfolio)
                                            <tr>
                                                <td>{{$portfolio->id}}</td>
                                                <td>{{$portfolio->name}}</td>
                                                <td>{{($portfolio->active)?'YES':'NO'}}</td>
                                                <td>{{($portfolio->strategy->isDynamic)?'Dynamic':'Static'}}</td>
                                                <td>{{$portfolio->rebalancePeriod}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        $('#get_accounts').click(function () {
            location.href = '{{route('get-accounts')}}';
        });
        $('#run_auto_trade').click(function () {
            location.href = '{{route('auto-trades')}}';
        });
        $('#get_portfolio').click(function () {
            location.href = '{{route('get-portfolios')}}';
        });
        $('#update_portfolio').click(function () {
            location.href = '{{route('update-portfolios')}}';
        });
    </script>
</x-layout>
