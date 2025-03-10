import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        strictPort: true,
        https: true, // 👈 Force HTTPS for Vite assets
    },
    build: {
        manifest: true,
        outDir: "public/build",
        rollupOptions: {
            output: {
                publicPath: process.env.APP_URL + "/build/",
            },
        },
    },
});
