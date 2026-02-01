<x-filament-panels::page>
    <style>
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-slide-in-up {
            animation: slideInUp 0.4s ease-out;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .file-upload-area {
            position: relative;
            transition: all 0.3s ease;
        }
        .file-upload-area:hover {
            border-color: #3b82f6 !important;
            background-color: #eff6ff;
        }
        .dark .file-upload-area:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        .file-upload-area.dragging {
            border-color: #2563eb !important;
            background-color: #dbeafe !important;
            transform: scale(1.02);
        }
        .badge-animate {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <div class="space-y-6">
        <form wire:submit="extract" class="space-y-6">
            {{-- Mode Selection --}}
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 animate-slide-in-up">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Mode Ekstraksi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih metode sesuai dengan jenis dokumen Anda</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="cursor-pointer hover-lift" wire:click="$set('extractionMode', 'manual')">
                        <div class="p-5 border-2 rounded-xl transition-all duration-300 h-full
                            {{ $extractionMode === 'manual' ? 'border-blue-500 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/20 shadow-lg' : 'border-gray-300 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600' }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all duration-300
                                        {{ $extractionMode === 'manual' ? 'border-blue-600 bg-blue-600 shadow-lg shadow-blue-500/50' : 'border-gray-400 dark:border-gray-500' }}">
                                        @if($extractionMode === 'manual')
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">Parser Manual</h4>
                                        <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs rounded-full font-medium">CEPAT</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Untuk dokumen KK yang terstruktur dengan baik</p>
                                    <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                        <li class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            PDF digital/text-based
                                        </li>
                                        <li class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Proses sangat cepat
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cursor-pointer hover-lift" wire:click="$set('extractionMode', 'gemini')">
                        <div class="p-5 border-2 rounded-xl transition-all duration-300 h-full
                            {{ $extractionMode === 'gemini' ? 'border-purple-500 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/20 shadow-lg' : 'border-gray-300 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-600' }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all duration-300
                                        {{ $extractionMode === 'gemini' ? 'border-purple-600 bg-purple-600 shadow-lg shadow-purple-500/50' : 'border-gray-400 dark:border-gray-500' }}">
                                        @if($extractionMode === 'gemini')
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">AI (Gemini)</h4>
                                        <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 text-xs rounded-full font-medium">SMART</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Untuk dokumen hasil scan atau foto</p>
                                    <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                        <li class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Scan/foto dokumen
                                        </li>
                                        <li class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            OCR dengan AI canggih
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($extractionMode === 'gemini')
                    <div class="mt-5 animate-fade-in">
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                            <label class="block text-sm font-semibold mb-3 text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                API Key Gemini
                            </label>
                            <input type="password" wire:model="geminiApiKey" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all"
                                placeholder="Masukkan API key Gemini Anda...">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Dapatkan API key gratis di <a href="https://ai.google.dev" target="_blank" class="text-purple-600 dark:text-purple-400 hover:underline font-medium">ai.google.dev</a>
                            </p>
                        </div>
                    </div>
                @endif
            </div>


            {{-- File Upload --}}
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 animate-slide-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload Dokumen</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Maksimal beberapa file PDF sekaligus</p>
                    </div>
                </div>
                
                <div class="file-upload-area border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition-all duration-300"
                     ondragover="event.preventDefault(); this.classList.add('dragging');"
                     ondragleave="this.classList.remove('dragging');"
                     ondrop="event.preventDefault(); this.classList.remove('dragging');">
                    <input type="file" wire:model="uploadedFiles" multiple accept=".pdf" class="hidden" id="file-upload">
                    <label for="file-upload" class="cursor-pointer block">
                        <div wire:loading.remove wire:target="uploadedFiles">
                            <div class="inline-block p-4 bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 rounded-2xl mb-4">
                                <svg class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Klik untuk upload atau drag & drop
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">PDF files only • Multiple files supported</p>
                        </div>
                        
                        <div wire:loading wire:target="uploadedFiles" class="py-4">
                            <div class="inline-block p-4 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl mb-4">
                                <svg class="h-12 w-12 text-blue-600 dark:text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <p class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-2">
                                Memproses file...
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Mohon tunggu sebentar</p>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Multi-upload
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Fast processing
                            </span>
                        </div>
                    </label>
                </div>

                @if(!empty($uploadedFiles))
                    <div class="mt-5 animate-fade-in">
                        <div class="p-5 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border-2 border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-green-900 dark:text-green-100 text-lg">{{ count($uploadedFiles) }} file siap diproses</p>
                                    <p class="text-sm text-green-700 dark:text-green-300">File PDF berhasil dipilih dan siap untuk ekstraksi</p>
                                </div>
                                <button type="button" wire:click="$set('uploadedFiles', [])" 
                                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 hover:bg-green-100 dark:hover:bg-green-900/30 p-2 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            
                            {{-- File List --}}
                            <div class="space-y-2">
                                @foreach($uploadedFileNames as $index => $fileInfo)
                                    <div class="flex items-center gap-3 p-3 bg-white dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $fileInfo['name'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $fileInfo['size'] }}</p>
                                        </div>
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Toggle Mapping --}}
                <div class="mt-5 p-5 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-xl border border-amber-200 dark:border-amber-800 hover-lift">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="relative inline-block w-12 h-6 cursor-pointer" wire:click="$toggle('useMapping')">
                                <input type="checkbox" wire:model.live="useMapping" id="useMapping" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-amber-500 peer-checked:to-yellow-500"></div>
                            </div>
                            <label for="useMapping" class="text-base font-bold cursor-pointer text-gray-900 dark:text-white">
                                Mapping ID Database
                            </label>
                        </div>
                        <span class="px-3 py-1.5 rounded-full font-semibold text-sm transition-all duration-300 {{ $useMapping ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg' : 'bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300' }}">
                            {{ $useMapping ? 'ON' : 'OFF' }}
                        </span>
                    </div>
                    <div class="ml-15 space-y-2">
                        @if($useMapping)
                            <div class="flex items-start gap-2 text-sm text-amber-800 dark:text-amber-200">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-semibold">Mode: Output Angka ID</p>
                                    <p class="text-xs mt-1">Categorical fields akan dimapping ke angka ID (1, 2, 3...) siap untuk import ke database</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-semibold">Mode: Output Teks Mentah</p>
                                    <p class="text-xs mt-1">Output tetap dalam format teks ("ISLAM", "LAKI-LAKI") untuk preview dan review</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3 animate-slide-in-up" style="animation-delay: 0.2s;">
                <button type="submit" 
                    class="flex-1 min-w-[200px] px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    wire:loading.attr="disabled">
                    <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <svg wire:loading class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span wire:loading.remove>Ekstrak Data Sekarang</span>
                    <span wire:loading class="badge-animate">Memproses...</span>
                </button>
                
                <button type="button" wire:click="resetForm" 
                    class="px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset Form
                </button>
            </div>
        </form>

        {{-- Progress Bar --}}
        @if($isProcessing)
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-2xl p-8 border-2 border-blue-200 dark:border-gray-700 animate-fade-in">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="w-8 h-8 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Sedang Memproses</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $progressMessage }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            {{ $progressPercentage }}%
                        </div>
                    </div>
                </div>
                
                {{-- Progress Bar --}}
                <div class="relative">
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-full transition-all duration-500 ease-out relative overflow-hidden"
                             style="width: {{ $progressPercentage }}%">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                        </div>
                    </div>
                    
                    {{-- Progress Steps --}}
                    <div class="flex justify-between mt-4 text-xs">
                        <div class="flex flex-col items-center {{ $progressPercentage >= 10 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 {{ $progressPercentage >= 10 ? 'bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-lg' : 'bg-gray-300 dark:bg-gray-600' }}">
                                @if($progressPercentage >= 10)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    1
                                @endif
                            </div>
                            <span class="font-semibold">Memuat</span>
                        </div>
                        
                        <div class="flex flex-col items-center {{ $progressPercentage >= 30 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 {{ $progressPercentage >= 30 ? 'bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-lg' : 'bg-gray-300 dark:bg-gray-600' }}">
                                @if($progressPercentage >= 30)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    2
                                @endif
                            </div>
                            <span class="font-semibold">Ekstraksi</span>
                        </div>
                        
                        <div class="flex flex-col items-center {{ $progressPercentage >= 70 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 {{ $progressPercentage >= 70 ? 'bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-lg' : 'bg-gray-300 dark:bg-gray-600' }}">
                                @if($progressPercentage >= 70)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    3
                                @endif
                            </div>
                            <span class="font-semibold">Validasi</span>
                        </div>
                        
                        <div class="flex flex-col items-center {{ $progressPercentage >= 100 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 {{ $progressPercentage >= 100 ? 'bg-gradient-to-br from-green-500 to-emerald-500 text-white shadow-lg' : 'bg-gray-300 dark:bg-gray-600' }}">
                                @if($progressPercentage >= 100)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    4
                                @endif
                            </div>
                            <span class="font-semibold">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- Results Section --}}
        @if(!empty($extractedData))
            {{-- Summary Statistics Card --}}
            @if(!empty($extractionSummary))
                <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-800 dark:via-gray-850 dark:to-gray-900 rounded-2xl shadow-2xl p-8 border-2 border-blue-200 dark:border-gray-700 animate-slide-in-up">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Ringkasan Ekstraksi</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Hasil pemrosesan dokumen Kartu Keluarga</p>
                        </div>
                        <div class="ml-auto">
                            <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full font-bold text-sm shadow-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Berhasil
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        {{-- Total Files --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover-lift border border-gray-100 dark:border-gray-700">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Total PDF</p>
                            <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $extractionSummary['total_files'] }}</p>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-green-600 dark:text-green-400 font-semibold">{{ $extractionSummary['successful_files'] }} berhasil</span>
                                @if($extractionSummary['failed_files'] > 0)
                                    <span class="text-gray-400">•</span>
                                    <span class="text-red-600 dark:text-red-400 font-semibold">{{ $extractionSummary['failed_files'] }} gagal</span>
                                @endif
                            </div>
                        </div>

                        {{-- Total People --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover-lift border border-gray-100 dark:border-gray-700">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Total Orang</p>
                            <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ $extractionSummary['total_people'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Terdeteksi dari semua KK</p>
                        </div>

                        {{-- Data Quality --}}
                        @if($extractionSummary['mapping_enabled'])
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover-lift border border-gray-100 dark:border-gray-700">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-200 dark:from-green-900/30 dark:to-emerald-800/30 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Kualitas Data</p>
                                <div class="flex items-baseline gap-2 mb-2">
                                    <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $extractionSummary['ok_count'] }}</p>
                                    <p class="text-lg text-gray-600 dark:text-gray-300 font-semibold">OK ({{ $extractionSummary['ok_percentage'] }}%)</p>
                                </div>
                                <p class="text-sm">
                                    <span class="text-yellow-600 dark:text-yellow-400 font-semibold">{{ $extractionSummary['flagged_count'] }} perlu cek</span>
                                    <span class="text-gray-400 mx-1">•</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $extractionSummary['flagged_percentage'] }}%</span>
                                </p>
                            </div>

                            {{-- NIK Validation --}}
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover-lift border border-gray-100 dark:border-gray-700">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-{{ $extractionSummary['nik_errors_count'] > 0 ? 'red' : 'green' }}-100 to-{{ $extractionSummary['nik_errors_count'] > 0 ? 'red' : 'emerald' }}-200 dark:from-{{ $extractionSummary['nik_errors_count'] > 0 ? 'red' : 'green' }}-900/30 dark:to-{{ $extractionSummary['nik_errors_count'] > 0 ? 'red' : 'emerald' }}-800/30 rounded-xl flex items-center justify-center">
                                        @if($extractionSummary['nik_errors_count'] > 0)
                                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Validasi NIK</p>
                                @if($extractionSummary['nik_errors_count'] > 0)
                                    <p class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">{{ $extractionSummary['nik_errors_count'] }}</p>
                                    <p class="text-sm text-red-600 dark:text-red-400 font-semibold">NIK invalid terdeteksi</p>
                                @else
                                    <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-green-600 dark:text-green-400 font-semibold">Semua NIK valid</p>
                                @endif
                            </div>
                        @else
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover-lift border border-gray-100 dark:border-gray-700 md:col-span-2">
                                <div class="flex items-center gap-4 h-full">
                                    <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-1">Status</p>
                                        <p class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-1">Data Mentah (Mapping OFF)</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Aktifkan mapping untuk melihat kualitas data dan validasi</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Flagged Fields Breakdown --}}
                    @if($extractionSummary['mapping_enabled'] && !empty($extractionSummary['flagged_fields']))
                        <div class="mt-6 pt-6 border-t-2 border-blue-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Field yang Sering Gagal Mapping:</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @foreach(array_slice($extractionSummary['flagged_fields'], 0, 8) as $field => $count)
                                    <span class="px-4 py-2 bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-200 text-sm rounded-lg font-semibold border border-yellow-200 dark:border-yellow-800 hover-lift">
                                        {{ $field }}: <span class="font-bold">{{ $count }}x</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif


            {{-- Results Table --}}
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-2xl border-2 border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-in-up">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-600 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Hasil Ekstraksi</h3>
                            <p class="text-blue-100 text-sm">{{ $totalRecords }} records berhasil diekstrak</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="checkDuplicates" 
                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            Import ke Database
                        </button>
                        <button wire:click="downloadExcel" 
                            class="px-6 py-3 bg-white text-blue-600 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Excel
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="rounded-xl border-2 border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 sticky top-0 z-10 shadow-md">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600 sticky left-0 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600 bg-yellow-100 dark:bg-yellow-900/50">Flag</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Alamat</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Dusun</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">RW</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">RT</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/30">Nama</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">No KK</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/30">NIK</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Sex</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Tempat Lahir</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Tanggal Lahir</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Agama</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Pendidikan KK</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Pendidikan Sedang</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Pekerjaan</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Status Kawin</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">KK Level</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Warga Negara</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">NIK Ayah</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Nama Ayah</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">NIK Ibu</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Nama Ibu</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Gol. Darah</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Akta Lahir</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Dok. Pasport</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Tgl Akhir Paspor</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Dok. KITAS</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Akta Kawin</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Tgl Kawin</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Akta Cerai</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Tgl Cerai</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Cacat</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Cara KB</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Hamil</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">KTP El</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Status Rekam</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Alamat Sekarang</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Status Dasar</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Suku</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">Tag ID Card</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">ID Asuransi</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 border-b-2 border-gray-300 dark:border-gray-600">No Asuransi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($extractedData as $index => $person)
                                        <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150 {{ ($person['_flag_unmapped'] ?? false) ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                                            <td class="px-4 py-3 text-xs font-medium text-gray-900 dark:text-white sticky left-0 bg-white dark:bg-gray-800">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-xs font-bold text-center">
                                                @if($person['_flag_unmapped'] ?? false)
                                                    <span class="px-3 py-1.5 bg-gradient-to-r from-yellow-400 to-amber-400 text-yellow-900 rounded-lg inline-flex items-center gap-1 shadow-md" title="{{ $person['_unmapped_fields'] ?? '' }}">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        CEK
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1.5 bg-gradient-to-r from-green-400 to-emerald-400 text-green-900 rounded-lg inline-flex items-center gap-1 shadow-md">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        OK
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['alamat'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['dusun'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['rw'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['rt'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20">{{ $person['nama'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300 font-mono">{{ $person['no_kk'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs font-mono {{ !empty($person['_nik_errors']) ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                                                <span @if(!empty($person['_nik_errors'])) title="{{ $person['_nik_errors'] }}" class="text-red-700 dark:text-red-300 font-bold flex items-center gap-1" @else class="text-gray-900 dark:text-white" @endif>
                                                    {{ $person['nik'] ?? '-' }}
                                                    @if(!empty($person['_nik_errors']))
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['sex'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['tempatlahir'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['tanggallahir'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['agama_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['pendidikan_kk_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['pendidikan_sedang_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['pekerjaan_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['status_kawin'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['kk_level'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['warganegara_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300 font-mono">{{ $person['ayah_nik'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['nama_ayah'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300 font-mono">{{ $person['ibu_nik'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['nama_ibu'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['golongan_darah_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['akta_lahir'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['dokumen_pasport'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['tanggal_akhir_paspor'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['dokumen_kitas'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['akta_perkawinan'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['tanggalperkawinan'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['akta_perceraian'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['tanggalperceraian'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['cacat_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['cara_kb_id'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['hamil'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['ktp_el'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['status_rekam'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['alamat_sekarang'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['status_dasar'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['suku'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['tag_id_card'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['id_asuransi'] ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $person['no_asuransi'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Duplicate Warning Modal --}}
    @if($showDuplicateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center animate-fade-in">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="cancelImport"></div>
        
        <!-- Modal -->
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full mx-4 animate-slide-in-up">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 rounded-t-2xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white">⚠ Duplikat NIK Ditemukan</h3>
                        <p class="text-orange-100 text-sm mt-1">Beberapa NIK sudah ada dalam sistem</p>
                    </div>
                    <button wire:click="cancelImport" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 max-h-96 overflow-y-auto">
                <!-- Summary -->
                <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                            {{ $duplicateInfo['summary']['total_duplicates'] ?? 0 }}
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            <div class="font-semibold">Total duplikat ditemukan</div>
                            <div class="text-xs text-gray-500">{{ $duplicateInfo['summary']['batch_duplicates'] ?? 0 }} dalam batch ini, {{ $duplicateInfo['summary']['db_duplicates'] ?? 0 }} di database</div>
                        </div>
                    </div>
                </div>

                <!-- Duplicates in Batch -->
                @if(!empty($duplicateInfo['in_batch']))
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Duplikat dalam Batch
                    </h4>
                    <div class="space-y-2">
                        @foreach(array_slice($duplicateInfo['in_batch'], 0, 5) as $nik => $entries)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                            <div class="font-mono text-sm font-semibold text-gray-900 dark:text-white mb-1">NIK: {{ $nik }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                Muncul {{ count($entries) }}x: 
                                @foreach($entries as $entry)
                                    <span class="inline-block mr-2">{{ $entry['nama'] }} (KK: {{ $entry['no_kk'] }})</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        @if(count($duplicateInfo['in_batch']) > 5)
                        <div class="text-xs text-gray-500 text-center py-2">+ {{ count($duplicateInfo['in_batch']) - 5 }} duplikat lainnya...</div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Duplicates in Database -->
                @if(!empty($duplicateInfo['in_database']))
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        NIK Sudah Ada di Database
                    </h4>
                    <div class="space-y-2">
                        @foreach(array_slice($duplicateInfo['in_database'], 0, 5) as $nik => $penduduks)
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                            <div class="font-mono text-sm font-semibold text-gray-900 dark:text-white mb-1">NIK: {{ $nik }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                Tercatat sebagai: 
                                @foreach($penduduks as $p)
                                    <span class="inline-block mr-2">{{ $p['nama'] }} (KK: {{ $p['no_kk'] }})</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        @if(count($duplicateInfo['in_database']) > 5)
                        <div class="text-xs text-gray-500 text-center py-2">+ {{ count($duplicateInfo['in_database']) - 5 }} duplikat lainnya...</div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <strong>Pilihan Anda:</strong><br>
                        • <strong>Lewati Duplikat</strong>: Import hanya data baru, abaikan NIK yang sudah ada<br>
                        • <strong>Batal</strong>: Kembali dan periksa data terlebih dahulu
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-b-2xl flex gap-3 justify-end">
                <button wire:click="cancelImport" 
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button wire:click="importSkipDuplicates" 
                    class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    Lewati Duplikat & Import
                </button>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>
