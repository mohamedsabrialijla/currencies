<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="mb-6">
                        <div class="grid grid-cols-4 gap-3">
                            <div class="bg-blue-400 text-blue-900 shadow-sm sm:rounded-lg p-4">
                                <div>
                                    <span class="text-2xl">{{ $balance }}</span>
                                    <span class="text-white text-xs">{{ $wallet }}</span>
                                </div>
                                <small>{{ __('Wallet Balance') }}</small>
                            </div>
                            <div class="bg-green-400 text-green-900 shadow-sm sm:rounded-lg p-4">
                                <div>
                                    <span class="text-2xl">{{ $symbol }}</span>
                                    <span class="text-white text-xs">{{ str_replace($wallet, '', $last_symbol) }}</span>
                                </div>
                                <small>{{ __('Last Trade Symbol') }}</small>
                            </div>
                            <div class="bg-red-400 text-green-900 shadow-sm sm:rounded-lg p-4">
                                <div>
                                    <span class="text-2xl">{{ $prev_last_price }}</span>
                                </div>
                                <small>Prev Last Price</small>
                            </div>
                            <div class="bg-green-300 text-green-900 shadow-sm sm:rounded-lg p-4">
                                <div>
                                    <span class="text-2xl">{{ $last_price }}</span>
                                </div>
                                <small>Last Price</small>
                            </div>
                        </div>
                        @if(false)
                        <div class="grid grid-cols-2 gap-4 my-4">
                            <div class="bg-yellow-600 text-black-900 shadow-sm sm:rounded-lg p-4">
                                <div>
                                    <span class="text-2xl">{{ \App\Models\Option::get('trade.min_price') }}</span>
                                </div>
                                <small>Min Price  (Buy when reach greater than : {{ \App\Models\Option::get('trade.min_price')*(1+\App\Models\Option::get('trade.change_percent')) }})</small>
                            </div>
                            <div class="bg-yellow-400 text-black-900 shadow-sm sm:rounded-lg p-4">
                                <div>
                                    <span class="text-2xl">{{ \App\Models\Option::get('trade.max_price') }}</span>
                                </div>
                                <small>Max Price (Sell when reach less than : {{ \App\Models\Option::get('trade.max_price')*(1-\App\Models\Option::get('trade.change_percent_sell')) }})</small>
                            </div>

                        </div>
                            @endif
                    </section>
                    <section>
                        <h2 class="mb-2">{{ __('Recent Trades') }}</h2>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Change %</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LastPrice->BuySellPrice</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Status</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($trades as $trade)
                                <tr>
                                    <td class="px-3 py-4">{{ $trade->symbol }}</td>
                                    <td class="px-3 py-4">{{ $trade->side }}: {{ $trade->type }}</td>
                                    <td class="px-3 py-4">{{ $trade->order_id }}</td>
                                    <td class="px-3 py-4">{{ $trade->price_change_percent }}</td>
                                    <td class="px-3 py-4">{{ number_format($trade->price, 4) }}</td>
                                    <td class="px-3 py-4">{{ number_format($trade->quantity, 4) }}</td>
                                    <td class="px-3 py-4">{{ number_format($trade->quantity * $trade->price, 4) }}</td>
                                    <td class="px-3 py-4">{{ number_format($trade->last_price, 4) }}->{{ number_format($trade->new_price, 4) }}</td>
                                    <td class="px-3 py-4">{{ $trade->order_status }}</td>
                                    <td class="px-3 py-4">{{ $trade->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                    <section class="mt-5">
                        <h2 class="mb-2">{{ __('Recent Logs') }}</h2>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $log)
                                <tr>
                                    <td class="px-3 py-4">{{ $log->created_at }}</td>
                                    <td class="px-3 py-4">{{ $log->level }}</td>
                                    <td class="px-3 py-4">{{ $log->message }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
