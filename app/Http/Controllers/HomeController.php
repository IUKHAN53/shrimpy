<?php

namespace App\Http\Controllers;

use App\Api\API_Connection;
use App\Models\BinanceCoin;
use App\Models\MyCoin;
use App\Models\SplitShrimpy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $accounts = false;
        $portfolios = false;
        return view('home')->with([
            'accounts' => $accounts,
            'portfolios' => $portfolios,
        ]);
    }

    public function myCoins()
    {
        return view('my-coins')->with([
            'my_coins' => SplitShrimpy::all(),
        ]);
    }

    public function editCoin(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'binance_coin_id' => 'required', 'unique:my_coins,binance_coin_id' . $request->coin_id,
                'conversion_coin' => '',
                'action' => 'required'
            ], [
                'binance_coin_id.required' => 'Coin Field is required',
            ]);
            $binance_coin = BinanceCoin::find($request->binance_coin_id);
            SplitShrimpy::findOrFail($request->coin_id)->update([
                'symbol' => $binance_coin->symbol,
                'binance_coin_id' => $binance_coin->id,
                'conversion_coin' => $request->conversion_coin ?? null,
            ]);
            return redirect(route('my-coins'))->with('success', 'Coin Updated Successfully');
        } else {
            return view('edit-coin')->with([
                'my_coin' => SplitShrimpy::findOrFail($request->coin_id),
                'b_coins' => BinanceCoin::all()
            ]);
        }
    }

    public function deleteCoin(Request $request)
    {
        $coin = SplitShrimpy::findOrFail($request->coin_id);
        $coin->delete();
        return redirect(route('my-coins'))->with('success', 'Coin Deleted Successfully');

    }

    public function addCoin(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'binance_coin_id' => 'required|unique:split_shrimpies,binance_coin_id',
                'conversion_coin' => '',
                'action' => 'required'
            ], [
                'binance_coin_id.required' => 'Coin Field is required',
            ]);
            $binance_coin = BinanceCoin::findOrFail($request->binance_coin_id);
            SplitShrimpy::create([
                'symbol' => $binance_coin->symbol,
                'percent' => 0,
                'action' => $request->action,
                'conversion_coin' => $request->conversion_coin ?? null,
                'binance_coin_id' => $binance_coin->id,
            ]);
            $this->calculateEvenly();
            return redirect(route('my-coins'))->with('success', 'Coin Added Successfully');
        } else {
            return view('create-coin')->with('b_coins', BinanceCoin::orderBy('symbol', 'asc')->get());
        }
    }

    public function binanceCoins()
    {
        return view('binance-coins')->with('coins', BinanceCoin::all());
    }


    function calculateEvenly()
    {
        $bnb = SplitShrimpy::where('symbol', 'BNB')->first();
        $percentage = 100;
        if ($bnb) {
            $bnb->percent = 1;
            $bnb->save();
            $percentage = 99;
        }
        $coins = SplitShrimpy::where('symbol', '!=', 'BNB')->WhereIn('action', ['send', 'convert'])->get();
        $total = ($coins) ? $coins->count() : 1;
        $percent = floor($percentage / $total);
        $total_percent = 0;
        foreach ($coins as $coin) {
            $coin->percent = $percent;
            $coin->save();
            $total_percent += $percent;
        }
        $otherCoin = SplitShrimpy::firstOrFail();
        $otherCoin->percent += $percentage - $total_percent;
        $otherCoin->save();
    }

    public function updateAction(Request $request)
    {
        SplitShrimpy::updateOrCreate(['id' => $request->coin_id], [
            'action' => $request->action,
        ]);
        $this->calculateEvenly();
        return redirect(route('my-coins'))->with('success', 'Coin Updated Successfully');
    }

    public function syncBinanceCoins()
    {
        $binance = BinanceCoin::all();
        $client = new API_Connection();
        $tickers = $client->getTickerData();
        $ticker_symbols = [];
        if ($binance->count() > 0) {
            foreach ($tickers as $ticker) {
                $ticker_symbols[] = $ticker->symbol;
                $coin = BinanceCoin::where('symbol', $ticker->symbol)->first();
                if ($coin) {
                    $coin->update(['price_usd' => $ticker->priceUsd]);
                    if ($coin->high < $ticker->priceUsd) {
                        $coin->update(['high' => $ticker->priceUsd]);
                    }
                } else {
                    BinanceCoin::create([
                        'name' => $ticker->name,
                        'symbol' => $ticker->symbol,
                        'price_usd' => $ticker->priceUsd,
                        'high' => $ticker->priceUsd,
                        'percent_change_24_h_usd' => $ticker->percentChange24hUsd ?? '',
                    ]);
                }
            }
            BinanceCoin::whereNotIn('symbol', $ticker_symbols)->delete();
        } else {
            foreach ($tickers as $ticker) {
                BinanceCoin::create([
                    'name' => $ticker->name,
                    'symbol' => $ticker->symbol,
                    'price_usd' => $ticker->priceUsd,
                    'high' => $ticker->priceUsd,
                    'percent_change_24_h_usd' => $ticker->percentChange24hUsd,
                ]);
            }
        }
        return redirect(route('binance-coins'))->with('success', 'All Coins Synced Successfully');
    }

    public function getAccounts()
    {
        $client = new API_Connection();
        $accounts = true;
        $portfolios = false;
        return view('home')->with([
            'accounts' => $accounts,
            'accounts_data' => $client->getAccounts(),
            'portfolios' => $portfolios,
        ]);
    }

    public function getPortfolios()
    {
        $client = new API_Connection();
        $accounts = false;
        $portfolios = true;
        return view('home')->with([
            'accounts' => $accounts,
            'portfolio_data' => $client->getPortfolio(),
            'portfolios' => $portfolios,
        ]);
    }

    public function updatePortfolios()
    {
        $sum = DB::table('split_shrimpies')->where('action', '!=', 'remove')->sum('percent');
        if ($sum != 100) {
            Log::debug('Cannot Update Portfolio When sum of percents does not equals 100');
            return back()->with('error', 'Cannot Update Portfolio When sum of percents for does not equals 100');
        } else {
            $coins = SplitShrimpy::whereIn('action', ['send', 'convert'])->get();
            foreach ($coins as $coin) {
                $symbol = ($coin->action == 'convert') ? $coin->conversion_coin : $coin->symbol;
                $coins_data[] = '{"symbol":"' . $symbol . '","percent":"' . $coin->percent . '"}';
            }
            $json_body = '
            {
                "name":"Binance",
                "rebalancePeriod":0,
                "strategy":{
                    "isDynamic":false,
                    "allocations":[' . implode(', ', $coins_data) . ']
                },
                "strategyTrigger":"Threshold",
                "rebalanceThreshold":"1",
                "maxSpread":"10",
                "maxSlippage":"10"
            }';
            $client = new API_Connection();
            $client->updatePortfolio($json_body);
        }
        return back()->with('info', 'Portfolio updated successfully');

    }


    function getPercentage($percentage, $total)
    {
        return (($percentage / 100) * $total);
    }

    public function scheduledTasks()
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        Log::debug('Started Syncing Coins');
        $this->syncBinanceCoins();
        Log::debug('Completed Syncing Coins');
        Log::debug('----------------------------------------------');
        Log::debug('Started Updating Portfolio');
        $this->updatePortfolios();
        Log::debug('Completed Updating Portfolio');
        Log::debug('----------------------------------------------');
        Log::debug('Started Auto Trades');
        $this->autoTrades();
        Log::debug('Completed Auto Trades');
        Log::debug('----------------------------------------------');
    }

    public function autoTrades()
    {
        $coins = SplitShrimpy::with('binance_coin')->get();
        foreach ($coins as $coin) {
            Log::debug('Coin: ' . $coin->symbol);
            $high_99 = $this->getPercentage(99, $coin->binance_coin->high);
            $high_96 = $this->getPercentage(96, $coin->binance_coin->high);
            if ($coin->percent != 1 && $coin->binance_coin->price_usd < $high_99) {
                if ($coin->conversion_coin == null) {
                    $coin->action = 'remove';
                    Log::debug('Applied Process: Set Action REMOVE for ' . $coin->symbol);
                } else {
                    $coin->action = 'send';
                    Log::debug('Applied Process: Set Action SEND for ' . $coin->symbol);
                }

            } else if ($coin->percent == 1 && $coin->binance_coin->price_usd > $high_99) {
                if ($coin->conversion_coin == null) {
                    $coin->action = 'send';
                    Log::debug('Applied Process: Set Action SEND for ' . $coin->symbol);
                } else {
                    $coin->action = 'convert';
                    Log::debug('Applied Process: Set Action CONVERT for ' . $coin->symbol);
                }
            } else if ($coin->percent == 1 && $coin->binance_coin->price_usd < $high_96) {
                if ($coin->conversion_coin == null) {
                    $coin->action = 'send';
                    Log::debug('Applied Process: Set Action SEND for ' . $coin->symbol);
                } else {
                    $coin->action = 'convert';
                    Log::debug('Applied Process: Set Action CONVERT for ' . $coin->symbol);
                }
                $coin->binance_coin->high = $coin->binance_coin->price_usd;
                $coin->binance_coin->save();
            } else {
                Log::debug('No Process Applied');
                Log::debug('----------------------------------------------');
            }
            $coin->save();
        }
        return back()->with('info', 'Auto trades Applied');
    }


    public function old_autoTrades()
    {
        $coins = SplitShrimpy::with('binance_coin', 'usdt')->get();
        $usdt = $coins->where('symbol', 'USDT')->first();
        $coins = $coins->where('symbol', '!=', 'USDT');
        if ($usdt) {
            foreach ($coins as $coin) {
                Log::debug('Coin: ' . $coin->symbol);
                $high_99 = $this->getPercentage(99, $coin->binance_coin->high);
                $high_96 = $this->getPercentage(96, $coin->binance_coin->high);
                if ($coin->percent != 1 && $coin->binance_coin->price_usd < $high_99) {
                    $changed_percent = $coin->percent - 8;
                    if ($changed_percent > 0) {
                        $coin->percent = $changed_percent;
                        $coin->save();
                        $usdt->percent += 8;
                        $usdt->save();
                        Log::debug('Applied Process: Deduct 8 from ' . $coin->symbol . ' Percent, Add 8 to USDT');
                    } else {
                        Log::debug('Percent less then 8');
                    }
                } else if ($usdt->percent < 8) {
                    Log::debug('USDT percent less then 8');
                } else if ($coin->percent == 1 && $coin->binance_coin->price_usd > $high_99) {
                    $coin->percent += 8;
                    $coin->save();
                    $usdt->percent -= 8;
                    $usdt->save();
                    Log::debug('Applied Process: Add 8 to ' . $coin->symbol . ' Percent, Deduct 8 from USDT');
                } else if ($coin->percent == 1 && $coin->binance_coin->price_usd < $high_96) {
                    $coin->percent += 8;
                    $coin->save();
                    $usdt->percent -= 8;
                    $usdt->save();
                    $coin->binance_coin->high = $coin->binance_coin->price_usd;
                    $coin->binance_coin->save();
                    Log::debug('Applied Process: Add 8 to ' . $coin->symbol . ' Percent, Deduct 8 from USDT, Set High to PriceUSD');
                } else {
                    Log::debug('No Process Applied');
                    Log::debug('----------------------------------------------');
                }
            }
        } else {
            Log::debug('Cannot auto trade when USDT does not exist in my coins');
            return back()->with('error', 'Cannot auto trade when USDT does not exist in my coins');
        }
        return back()->with('info', 'Auto trades Applied');
    }
}

