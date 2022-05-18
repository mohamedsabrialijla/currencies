<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Options') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <x-alerts />
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form action="{{ route('options') }}" method="post">
                        @csrf

                        <div>
                            <x-label for="trade_status" :value="__('Enable Trade')" />
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="trade_status_on" name="options[trade.status]" value="1" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" {{ ($options['trade.status'] ?? 1)? ' checked' : '' }}>
                                    <label for="trade_status_on" class="ml-3 block text-sm font-medium text-gray-700">
                                        On
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="trade_status_off" name="options[trade.status]" value="0" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" {{ !($options['trade.status'] ?? 1)? ' checked' : '' }}>
                                    <label for="trade_status_off" class="ml-3 block text-sm font-medium text-gray-700">
                                        Off
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-label for="mode_live" :value="__('Trade Mode')" />
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="mode_live" name="options[binance.mode]" value="live" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" {{ (($options['binance.mode'] ?? '' == 'live'))? ' checked' : '' }}>
                                    <label for="mode_live" class="ml-3 block text-sm font-medium text-gray-700">
                                        Live
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="mode_test" name="options[binance.mode]" value="test" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" {{ (($options['binance.mode'] ?? '') == 'test')? ' checked' : '' }}>
                                    <label for="mode_test" class="ml-3 block text-sm font-medium text-gray-700">
                                        Test
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-label for="api_key" :value="__('API Key')" />
                            <x-input id="api_key" class="block mt-1 w-full" type="text" name="options[binance.api.key]" :value="$options['binance.api.key'] ?? ''" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="api_secret" :value="__('API Secret')" />
                            <x-input id="api_secret" class="block mt-1 w-full" type="text" name="options[binance.api.secret]" :value="$options['binance.api.secret'] ?? ''" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="trade_wallet" :value="__('Wallet Symbol')" />
                            <x-select id="trade_wallet" class="block mt-1 w-full" name="options[trade.wallet]" :options="$balances" :selected="$options['trade.wallet'] ?? 0" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="trade_symbols" :value="__('Trade Symbols')" />
                            <x-input id="trade_symbols" class="block mt-1 w-full" type="text" name="options[trade.symbols]" :value="$options['trade.symbols'] ?? 1" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="change_percent" :value="__('Change Percent')" />
                            <x-input id="change_percent" class="block mt-1 w-full" type="text" name="options[trade.change_percent]" :value="$options['trade.change_percent'] ?? ''" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="change_percent" :value="'change percent sell'" />
                            <x-input id="change_percent" class="block mt-1 w-full" type="text" name="options[trade.change_percent_sell]" :value="$options['trade.change_percent_sell'] ?? ''" required />
                        </div>
                        <div class="mt-4">
                            <x-label for="run_mins" :value="__('Run Every (mins)')" />
                            <x-input id="run_mins" class="block mt-1 w-full" type="number" min="1" max="60" name="options[trade.run_mins]" :value="$options['trade.run_mins'] ?? ''" required />
                        </div>
                        <div class="flex items-center justify-end mt-4">

                            <x-button class="ml-4">
                                {{ __('Save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
