import { defineConfig, loadEnv } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    
    return {
        plugins: [
            laravel({
                input: [
                    "resources/css/app.css",
                    "resources/js/app.jsx",
                    "resources/css/filament/admin/theme.css",
                    "ekstrak-pdf-kartu-keluarga/index.tsx",
                ],
                refresh: [...refreshPaths, "app/Livewire/**"],
            }),
            react({
                include: "**/*.{jsx,tsx}",
            }),
        ],
        define: {
            'process.env.GEMINI_API_KEY': JSON.stringify(env.GEMINI_API_KEY)
        },
        resolve: {
            alias: {
                '@ekstrak': path.resolve(__dirname, './ekstrak-pdf-kartu-keluarga'),
            }
        }
    };
});
