<x-filament-panels::page>
    @vite(['ekstrak-pdf-kartu-keluarga/index.tsx'])
    
    <div class="ekstrak-kk-container">
        <!-- React App will be mounted here -->
        <div id="ekstrak-kk-root"></div>
    </div>

    <style>
        /* Make the React app fill the Filament card */
        .ekstrak-kk-container {
            min-height: 600px;
        }
        
        /* Override any conflicting Filament styles if needed */
        #ekstrak-kk-root {
            width: 100%;
        }
    </style>
</x-filament-panels::page>
