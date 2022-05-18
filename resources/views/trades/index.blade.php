<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Change %</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LastPrice->BuySellPrice</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($trades as $trade)
                            <tr>
                                <td class="px-3 py-4">{{ $trade->symbol }}</td>
                                <td class="px-3 py-4">{{ $trade->side }}</td>
                                <td class="px-3 py-4">{{ $trade->order_id }}</td>
                                <td class="px-3 py-4">{{ $trade->price_change_percent }}</td>
                                <td class="px-3 py-4">{{ $trade->type }}</td>
                                <td class="px-3 py-4">{{ $trade->price }}</td>
                                <td class="px-3 py-4">{{ $trade->quantity }}</td>
                                <td class="px-3 py-4">{{ $trade->quantity * $trade->price }}</td>
                                <td class="px-3 py-4">{{ number_format($trade->last_price, 4) }}->{{ number_format($trade->new_price, 4) }}</td>
                                <td class="px-3 py-4">{{ $trade->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $trades->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
