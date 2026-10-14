import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/site.css",
                "resources/css/admin.css",
                "resources/js/site.js",
            ],
            refresh: true,
        }),
    ],
    server: {
        host: "127.0.0.1",
        port: 5174,
        strictPort: true,
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
