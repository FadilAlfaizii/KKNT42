<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sensor Aktif</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeSensors ?? 0 }}</p>
                    </div>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Alert Aktif</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeAlerts ?? 0 }}</p>
                    </div>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Artikel</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalArticles ?? 0 }}</p>
                    </div>
                </div>
            </x-filament::card>
        </div>
    </div>
</x-filament-panels::page>
