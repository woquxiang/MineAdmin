// vite.config.ts
import path2 from "node:path";
import process2 from "node:process";
import dayjs2 from "file:///Users/macos/Desktop/project/v3/web/node_modules/dayjs/dayjs.min.js";
import { defineConfig, loadEnv } from "file:///Users/macos/Desktop/project/v3/web/node_modules/vite/dist/node/index.js";

// package.json
var package_default = {
  name: "mineadmin-ui",
  type: "module",
  version: "3.0.0",
  engines: {
    node: "^20.0.0 || >=21.1.0"
  },
  scripts: {
    dev: "vite",
    build: "vite build",
    serve: "http-server ./dist -o",
    svgo: "svgo -f src/assets/icons",
    "gen:icons": "esno scripts/gen.icons.mts",
    "plugin:publish": "esno scripts/plugin.publish.mts",
    lint: "npm-run-all -s lint:tsc lint:eslint lint:stylelint",
    "lint:tsc": "vue-tsc --noEmit",
    "lint:eslint": "eslint . --cache --fix",
    "lint:stylelint": 'stylelint "src/**/*.{css,scss,vue}" --cache --fix'
  },
  dependencies: {
    "@imengyu/vue3-context-menu": "^1.4.2",
    "@mineadmin/echarts": "^1.0.5",
    "@mineadmin/form": "^1.0.21",
    "@mineadmin/pro-table": "^1.0.55",
    "@mineadmin/search": "^1.0.27",
    "@mineadmin/table": "^1.0.33",
    "@vueuse/core": "^11.1.0",
    "@vueuse/integrations": "^11.1.0",
    "@wangeditor/editor": "^5.1.23",
    "@wangeditor/editor-for-vue": "^5.1.12",
    "animate.css": "^4.1.1",
    axios: "^1.7.8",
    dayjs: "^1.11.12",
    echarts: "^5.5.1",
    "file-saver": "^2.0.5",
    "element-plus": "^2.9.1",
    "floating-vue": "5.2.2",
    "lodash-es": "^4.17.21",
    "md-editor-v3": "^5.0.2",
    nprogress: "^0.2.0",
    overlayscrollbars: "^2.10.0",
    "overlayscrollbars-vue": "^0.5.9",
    "path-browserify": "^1.0.1",
    "path-to-regexp": "^8.1.0",
    pinia: "^2.2.4",
    qs: "^6.13.0",
    radash: "^12.1.0",
    "radix-vue": "^1.9.6",
    sortablejs: "1.15.4",
    "vaul-vue": "^0.2.0",
    vue: "^3.5.12",
    "vue-i18n": "^10.0.5",
    "vue-m-message": "^4.0.2",
    "vue-router": "^4.5.0",
    "web-storage-cache": "^1.1.1"
  },
  devDependencies: {
    "@antfu/eslint-config": "^3.10.0",
    "@iconify/json": "^2.2.276",
    "@iconify/vue": "^4.1.2",
    "@intlify/unplugin-vue-i18n": "^6.0.0",
    "@stylistic/stylelint-config": "^2.0.0",
    "@types/archiver": "^6.0.2",
    "@types/mockjs": "^1.0.10",
    "@types/nprogress": "^0.2.3",
    "@types/path-browserify": "^1.0.2",
    "@types/qs": "^6.9.16",
    "@unocss/eslint-plugin": "^0.64.1",
    "@vitejs/plugin-legacy": "^5.4.3",
    "@vitejs/plugin-vue": "^5.2.1",
    "@vitejs/plugin-vue-jsx": "^4.1.1",
    "ace-builds": "^1.36.4",
    archiver: "^7.0.1",
    autoprefixer: "^10.4.20",
    boxen: "^8.0.1",
    eslint: "^9.11.0",
    esno: "^4.7.0",
    "fs-extra": "^11.2.0",
    "http-server": "^14.1.1",
    "lint-staged": "^15.2.10",
    "npm-run-all2": "^7.0.1",
    picocolors: "^1.1.0",
    postcss: "^8.4.47",
    sass: "^1.79.2",
    stylelint: "^16.8.1",
    "stylelint-config-recess-order": "^5.0.1",
    "stylelint-config-standard-scss": "^13.1.0",
    "stylelint-config-standard-vue": "^1.0.0",
    "stylelint-scss": "^6.7.0",
    svgo: "^3.3.2",
    terser: "^5.36.0",
    typescript: "^5.7.2",
    unocss: "^0.64.1",
    "unplugin-auto-import": "^0.18.3",
    "unplugin-vue-components": "^0.27.4",
    vconsole: "^3.15.1",
    vite: "^5.4.11",
    "vite-plugin-chunk-split": "^0.5.0",
    "vite-plugin-compression2": "^1.3.0",
    "vite-plugin-fake-server": "^2.1.2",
    "vite-plugin-svg-icons": "^2.0.1",
    "vite-plugin-vue-devtools": "^7.4.5",
    "vue-tsc": "^2.1.6",
    "vue3-ace-editor": "^2.2.4"
  }
};

// vite/index.ts
import vueLegacy from "file:///Users/macos/Desktop/project/v3/web/node_modules/@vitejs/plugin-legacy/dist/index.mjs";
import vue from "file:///Users/macos/Desktop/project/v3/web/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJsx from "file:///Users/macos/Desktop/project/v3/web/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";

// vite/archiver.ts
import fs from "node:fs";
import dayjs from "file:///Users/macos/Desktop/project/v3/web/node_modules/dayjs/dayjs.min.js";
import archiver from "file:///Users/macos/Desktop/project/v3/web/node_modules/archiver/index.js";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
function createArchiver(env) {
  const { VITE_BUILD_ARCHIVE } = env;
  let outDir;
  return {
    name: "vite-plugin-archiver",
    apply: "build",
    configResolved(resolvedConfig) {
      outDir = resolvedConfig.build.outDir;
    },
    async closeBundle() {
      if (["zip", "tar"].includes(VITE_BUILD_ARCHIVE)) {
        await sleep(1e3);
        const archive = archiver(VITE_BUILD_ARCHIVE, {
          ...VITE_BUILD_ARCHIVE === "zip" && { zlib: { level: 9 } },
          ...VITE_BUILD_ARCHIVE === "tar" && { gzip: true, gzipOptions: { level: 9 } }
        });
        const output = fs.createWriteStream(`${outDir}.${dayjs().format("YYYY-MM-DD-HH-mm-ss")}.${VITE_BUILD_ARCHIVE === "zip" ? "zip" : "tar.gz"}`);
        archive.pipe(output);
        archive.directory(outDir, false);
        await archive.finalize();
      }
    }
  };
}

// vite/auto-import.ts
import autoImport from "file:///Users/macos/Desktop/project/v3/web/node_modules/unplugin-auto-import/dist/vite.js";
function createAutoImport() {
  return autoImport({
    imports: [
      "vue",
      "vue-router",
      "pinia"
    ],
    dts: "./types/auto-imports.d.ts",
    dirs: [
      "./src/hooks/auto-imports/**",
      "./src/store/modules/**"
    ]
  });
}

// vite/chunk.ts
import { chunkSplitPlugin } from "file:///Users/macos/Desktop/project/v3/web/node_modules/vite-plugin-chunk-split/dist/index.mjs";
function createChunkSplit() {
  return chunkSplitPlugin({
    strategy: "default"
  });
}

// vite/components.ts
import components from "file:///Users/macos/Desktop/project/v3/web/node_modules/unplugin-vue-components/dist/vite.js";
function createComponents() {
  return components({
    dirs: ["src/components"],
    include: [/\.vue$/, /\.vue\?vue/, /\.tsx$/],
    dts: "./types/components.d.ts"
  });
}

// vite/compression.ts
import { compression } from "file:///Users/macos/Desktop/project/v3/web/node_modules/vite-plugin-compression2/dist/index.mjs";
function createCompression(env, isBuild) {
  const plugin = [];
  if (isBuild) {
    const { VITE_BUILD_COMPRESS } = env;
    const compressList = VITE_BUILD_COMPRESS.split(",");
    if (compressList.includes("gzip")) {
      plugin.push(
        compression()
      );
    }
    if (compressList.includes("brotli")) {
      plugin.push(
        compression({
          exclude: [/\.(br)$/, /\.(gz)$/],
          algorithm: "brotliCompress"
        })
      );
    }
  }
  return plugin;
}

// vite/devtools.ts
import VueDevTools from "file:///Users/macos/Desktop/project/v3/web/node_modules/vite-plugin-vue-devtools/dist/vite.mjs";
function createDevtools(env) {
  const { VITE_OPEN_DEVTOOLS } = env;
  return VITE_OPEN_DEVTOOLS === "true" && VueDevTools();
}

// vite/i18n-message.ts
import VueI18nPlugin from "file:///Users/macos/Desktop/project/v3/web/node_modules/@intlify/unplugin-vue-i18n/lib/vite.mjs";
function createI18nMessage() {
  return VueI18nPlugin({
    include: [
      "./src/locales/**",
      "./src/modules/**/locales/**",
      "./src/plugins/*/**/locales/**"
    ]
  });
}

// vite/start-info.ts
import boxen from "file:///Users/macos/Desktop/project/v3/web/node_modules/boxen/index.js";
import picocolors from "file:///Users/macos/Desktop/project/v3/web/node_modules/picocolors/picocolors.js";
function startInfo() {
  return {
    name: "startInfo",
    apply: "serve",
    async buildStart() {
      const { bold, cyan, underline } = picocolors;
      console.log(
        boxen(
          `${bold(cyan(`MineAdmin v${package_default.version}`))}

${underline("https://github.com/mineadmin")}`,
          {
            padding: 1,
            margin: 1,
            borderStyle: "double",
            title: "Welcome use",
            titleAlignment: "center",
            textAlignment: "center"
          }
        )
      );
    }
  };
}

// vite/svg-icon.ts
import path from "node:path";
import process from "node:process";
import { createSvgIconsPlugin } from "file:///Users/macos/Desktop/project/v3/web/node_modules/vite-plugin-svg-icons/dist/index.mjs";
function createSvgIcon(isBuild) {
  return createSvgIconsPlugin({
    iconDirs: [path.resolve(process.cwd(), "src/assets/icons/")],
    symbolId: "icon-[dir]-[name]",
    svgoOptions: isBuild
  });
}

// vite/unocss.ts
import Unocss from "file:///Users/macos/Desktop/project/v3/web/node_modules/unocss/dist/vite.mjs";
function createUnocss() {
  return Unocss();
}

// vite/index.ts
function createVitePlugins(viteEnv, isBuild = false) {
  const vitePlugins = [
    vue(),
    vueJsx(),
    startInfo(),
    vueLegacy({
      renderLegacyChunks: false,
      modernPolyfills: [
        "es.array.at",
        "es.array.find-last"
      ]
    })
  ];
  vitePlugins.push(createDevtools(viteEnv));
  vitePlugins.push(createAutoImport());
  vitePlugins.push(createComponents());
  vitePlugins.push(createUnocss());
  vitePlugins.push(createSvgIcon(isBuild));
  vitePlugins.push(...createCompression(viteEnv, isBuild));
  vitePlugins.push(createArchiver(viteEnv));
  vitePlugins.push(createI18nMessage());
  vitePlugins.push(createChunkSplit());
  return vitePlugins;
}

// vite/optimize.ts
var include = [
  "@mineadmin/table",
  "@mineadmin/form",
  "@imengyu/vue3-context-menu",
  "@vueuse/core",
  "@vueuse/integrations",
  "axios",
  "dayjs",
  "echarts",
  "floating-vue",
  "lodash-es",
  "nprogress",
  "overlayscrollbars",
  "overlayscrollbars-vue",
  "path-browserify",
  "path-to-regexp",
  "pinia",
  "radash",
  "radix-vue",
  "vaul-vue",
  "qs",
  "web-storage-cache",
  "sortablejs",
  "vue",
  "vue-i18n",
  "vue-m-message",
  "vue-router",
  "element-plus",
  "element-plus/es",
  "element-plus/es/locale/lang/zh-cn",
  "element-plus/es/locale/lang/en",
  "element-plus/es/components/avatar/style/css",
  "element-plus/es/components/space/style/css",
  "element-plus/es/components/backtop/style/css",
  "element-plus/es/components/form/style/css",
  "element-plus/es/components/radio-group/style/css",
  "element-plus/es/components/radio/style/css",
  "element-plus/es/components/checkbox/style/css",
  "element-plus/es/components/checkbox-group/style/css",
  "element-plus/es/components/switch/style/css",
  "element-plus/es/components/time-picker/style/css",
  "element-plus/es/components/date-picker/style/css",
  "element-plus/es/components/descriptions/style/css",
  "element-plus/es/components/descriptions-item/style/css",
  "element-plus/es/components/link/style/css",
  "element-plus/es/components/tooltip/style/css",
  "element-plus/es/components/drawer/style/css",
  "element-plus/es/components/dialog/style/css",
  "element-plus/es/components/checkbox-button/style/css",
  "element-plus/es/components/option-group/style/css",
  "element-plus/es/components/radio-button/style/css",
  "element-plus/es/components/cascader/style/css",
  "element-plus/es/components/color-picker/style/css",
  "element-plus/es/components/input-number/style/css",
  "element-plus/es/components/rate/style/css",
  "element-plus/es/components/select-v2/style/css",
  "element-plus/es/components/tree-select/style/css",
  "element-plus/es/components/slider/style/css",
  "element-plus/es/components/time-select/style/css",
  "element-plus/es/components/autocomplete/style/css",
  "element-plus/es/components/image-viewer/style/css",
  "element-plus/es/components/upload/style/css",
  "element-plus/es/components/col/style/css",
  "element-plus/es/components/form-item/style/css",
  "element-plus/es/components/alert/style/css",
  "element-plus/es/components/breadcrumb/style/css",
  "element-plus/es/components/select/style/css",
  "element-plus/es/components/input/style/css",
  "element-plus/es/components/breadcrumb-item/style/css",
  "element-plus/es/components/tag/style/css",
  "element-plus/es/components/pagination/style/css",
  "element-plus/es/components/table/style/css",
  "element-plus/es/components/table-v2/style/css",
  "element-plus/es/components/table-column/style/css",
  "element-plus/es/components/card/style/css",
  "element-plus/es/components/row/style/css",
  "element-plus/es/components/button/style/css",
  "element-plus/es/components/menu/style/css",
  "element-plus/es/components/sub-menu/style/css",
  "element-plus/es/components/menu-item/style/css",
  "element-plus/es/components/option/style/css",
  "element-plus/es/components/dropdown/style/css",
  "element-plus/es/components/dropdown-menu/style/css",
  "element-plus/es/components/dropdown-item/style/css",
  "element-plus/es/components/skeleton/style/css",
  "element-plus/es/components/skeleton/style/css",
  "element-plus/es/components/backtop/style/css",
  "element-plus/es/components/menu/style/css",
  "element-plus/es/components/sub-menu/style/css",
  "element-plus/es/components/menu-item/style/css",
  "element-plus/es/components/dropdown/style/css",
  "element-plus/es/components/tree/style/css",
  "element-plus/es/components/dropdown-menu/style/css",
  "element-plus/es/components/dropdown-item/style/css",
  "element-plus/es/components/badge/style/css",
  "element-plus/es/components/breadcrumb/style/css",
  "element-plus/es/components/breadcrumb-item/style/css",
  "element-plus/es/components/image/style/css",
  "element-plus/es/components/collapse-transition/style/css",
  "element-plus/es/components/timeline/style/css",
  "element-plus/es/components/timeline-item/style/css",
  "element-plus/es/components/collapse/style/css",
  "element-plus/es/components/collapse-item/style/css",
  "element-plus/es/components/button-group/style/css",
  "element-plus/es/components/text/style/css",
  "element-plus/es/components/segmented/style/css",
  "element-plus/es/components/footer/style/css",
  "element-plus/es/components/empty/style/css"
];
var exclude = ["@iconify/json"];

// vite.config.ts
var __vite_injected_original_dirname = "/Users/macos/Desktop/project/v3/web";
var vite_config_default = async ({ mode, command }) => {
  const env = loadEnv(mode, process2.cwd());
  function isProduction() {
    return mode === "production";
  }
  const proxyPrefix = env.VITE_PROXY_PREFIX;
  return defineConfig({
    base: env.VITE_APP_ROOT_BASE,
    // 开发服务器选项 https://cn.vitejs.dev/config/#server-options
    server: {
      open: true,
      port: Number(env.VITE_APP_PORT ?? process2.env.port),
      proxy: {
        [proxyPrefix]: {
          target: env.VITE_APP_API_BASEURL,
          changeOrigin: command === "serve" && env.VITE_OPEN_PROXY === "true",
          rewrite: (path3) => path3.replace(new RegExp(`^${proxyPrefix}`), "")
        }
      }
    },
    esbuild: {
      drop: isProduction() ? ["console", "debugger"] : []
    },
    // 构建选项 https://cn.vitejs.dev/config/#server-fsserve-root
    build: {
      outDir: isProduction ? "dist" : `dist-${mode}`,
      sourcemap: env.VITE_BUILD_SOURCEMAP === "true",
      minify: "esbuild",
      rollupOptions: {
        output: {
          chunkFileNames: "static/js/[name]-[hash].js",
          entryFileNames: "static/js/[name]-[hash].js",
          assetFileNames: "static/[ext]/[name]-[hash].[ext]",
          manualChunks: {
            echarts: ["echarts"]
            // 将 echarts 单独打包
          }
        }
      }
    },
    define: {
      __MINE_SYSTEM_INFO__: JSON.stringify({
        pkg: {
          version: package_default.version,
          dependencies: package_default.dependencies,
          devDependencies: package_default.devDependencies
        },
        lastBuildTime: dayjs2().format("YYYY-MM-DD HH:mm:ss")
      })
    },
    plugins: createVitePlugins(env, command === "build"),
    resolve: {
      alias: {
        "@": path2.resolve(__vite_injected_original_dirname, "src"),
        "#": path2.resolve(__vite_injected_original_dirname, "types"),
        "$": path2.resolve(__vite_injected_original_dirname, "src/plugins"),
        "~": path2.resolve(__vite_injected_original_dirname, "src/modules")
      }
    },
    css: {
      preprocessorOptions: {
        scss: {
          api: "modern-compiler",
          // additionalData: scssFiles.join(''),
          javascriptEnabled: true
        }
      }
    },
    optimizeDeps: { include, exclude }
  });
};
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcudHMiLCAicGFja2FnZS5qc29uIiwgInZpdGUvaW5kZXgudHMiLCAidml0ZS9hcmNoaXZlci50cyIsICJ2aXRlL2F1dG8taW1wb3J0LnRzIiwgInZpdGUvY2h1bmsudHMiLCAidml0ZS9jb21wb25lbnRzLnRzIiwgInZpdGUvY29tcHJlc3Npb24udHMiLCAidml0ZS9kZXZ0b29scy50cyIsICJ2aXRlL2kxOG4tbWVzc2FnZS50cyIsICJ2aXRlL3N0YXJ0LWluZm8udHMiLCAidml0ZS9zdmctaWNvbi50cyIsICJ2aXRlL3Vub2Nzcy50cyIsICJ2aXRlL29wdGltaXplLnRzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWJcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUuY29uZmlnLnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUuY29uZmlnLnRzXCI7aW1wb3J0IHBhdGggZnJvbSAnbm9kZTpwYXRoJ1xuaW1wb3J0IHByb2Nlc3MgZnJvbSAnbm9kZTpwcm9jZXNzJ1xuaW1wb3J0IGRheWpzIGZyb20gJ2RheWpzJ1xuaW1wb3J0IHsgZGVmaW5lQ29uZmlnLCBsb2FkRW52IH0gZnJvbSAndml0ZSdcbmltcG9ydCBwa2cgZnJvbSAnLi9wYWNrYWdlLmpzb24nXG5pbXBvcnQgY3JlYXRlVml0ZVBsdWdpbnMgZnJvbSAnLi92aXRlJ1xuaW1wb3J0IHsgZXhjbHVkZSwgaW5jbHVkZSB9IGZyb20gJy4vdml0ZS9vcHRpbWl6ZSdcblxuLy8gaHR0cHM6Ly92aXRlanMuZGV2L2NvbmZpZy9cbmV4cG9ydCBkZWZhdWx0IGFzeW5jICh7IG1vZGUsIGNvbW1hbmQgfSkgPT4ge1xuICBjb25zdCBlbnYgPSBsb2FkRW52KG1vZGUsIHByb2Nlc3MuY3dkKCkpXG4gIGZ1bmN0aW9uIGlzUHJvZHVjdGlvbigpOiBib29sZWFuIHtcbiAgICByZXR1cm4gbW9kZSA9PT0gJ3Byb2R1Y3Rpb24nXG4gIH1cblxuICAvLyBcdTUxNjhcdTVDNDAgc2NzcyBcdThENDRcdTZFOTBcbiAgLy8gY29uc3Qgc2Nzc0ZpbGVzOiBzdHJpbmdbXSA9IFtdXG4gIC8vIGZzLnJlYWRkaXJTeW5jKCdzcmMvYXNzZXRzL3N0eWxlcy9yZXNvdXJjZXMnKS5mb3JFYWNoKChkaXJuYW1lKSA9PiB7XG4gIC8vICAgaWYgKGZzLnN0YXRTeW5jKGBzcmMvYXNzZXRzL3N0eWxlcy9yZXNvdXJjZXMvJHtkaXJuYW1lfWApLmlzRmlsZSgpKSB7XG4gIC8vICAgICBzY3NzRmlsZXMucHVzaChgQHVzZSBcInNyYy9hc3NldHMvc3R5bGVzL3Jlc291cmNlcy8ke2Rpcm5hbWV9XCIgYXMgKjtgKVxuICAvLyAgIH1cbiAgLy8gfSlcblxuICBjb25zdCBwcm94eVByZWZpeCA9IGVudi5WSVRFX1BST1hZX1BSRUZJWFxuICByZXR1cm4gZGVmaW5lQ29uZmlnKHtcbiAgICBiYXNlOiBlbnYuVklURV9BUFBfUk9PVF9CQVNFLFxuICAgIC8vIFx1NUYwMFx1NTNEMVx1NjcwRFx1NTJBMVx1NTY2OFx1OTAwOVx1OTg3OSBodHRwczovL2NuLnZpdGVqcy5kZXYvY29uZmlnLyNzZXJ2ZXItb3B0aW9uc1xuICAgIHNlcnZlcjoge1xuICAgICAgb3BlbjogdHJ1ZSxcbiAgICAgIHBvcnQ6IE51bWJlcihlbnYuVklURV9BUFBfUE9SVCA/PyBwcm9jZXNzLmVudi5wb3J0KSxcbiAgICAgIHByb3h5OiB7XG4gICAgICAgIFtwcm94eVByZWZpeF06IHtcbiAgICAgICAgICB0YXJnZXQ6IGVudi5WSVRFX0FQUF9BUElfQkFTRVVSTCxcbiAgICAgICAgICBjaGFuZ2VPcmlnaW46IGNvbW1hbmQgPT09ICdzZXJ2ZScgJiYgZW52LlZJVEVfT1BFTl9QUk9YWSA9PT0gJ3RydWUnLFxuICAgICAgICAgIHJld3JpdGU6IHBhdGggPT4gcGF0aC5yZXBsYWNlKG5ldyBSZWdFeHAoYF4ke3Byb3h5UHJlZml4fWApLCAnJyksXG4gICAgICAgIH0sXG4gICAgICB9LFxuICAgIH0sXG4gICAgZXNidWlsZDoge1xuICAgICAgZHJvcDogaXNQcm9kdWN0aW9uKCkgPyBbJ2NvbnNvbGUnLCAnZGVidWdnZXInXSA6IFtdLFxuICAgIH0sXG4gICAgLy8gXHU2Nzg0XHU1RUZBXHU5MDA5XHU5ODc5IGh0dHBzOi8vY24udml0ZWpzLmRldi9jb25maWcvI3NlcnZlci1mc3NlcnZlLXJvb3RcbiAgICBidWlsZDoge1xuICAgICAgb3V0RGlyOiBpc1Byb2R1Y3Rpb24gPyAnZGlzdCcgOiBgZGlzdC0ke21vZGV9YCxcbiAgICAgIHNvdXJjZW1hcDogZW52LlZJVEVfQlVJTERfU09VUkNFTUFQID09PSAndHJ1ZScsXG4gICAgICBtaW5pZnk6ICdlc2J1aWxkJyxcbiAgICAgIHJvbGx1cE9wdGlvbnM6IHtcbiAgICAgICAgb3V0cHV0OiB7XG4gICAgICAgICAgY2h1bmtGaWxlTmFtZXM6ICdzdGF0aWMvanMvW25hbWVdLVtoYXNoXS5qcycsXG4gICAgICAgICAgZW50cnlGaWxlTmFtZXM6ICdzdGF0aWMvanMvW25hbWVdLVtoYXNoXS5qcycsXG4gICAgICAgICAgYXNzZXRGaWxlTmFtZXM6ICdzdGF0aWMvW2V4dF0vW25hbWVdLVtoYXNoXS5bZXh0XScsXG4gICAgICAgICAgbWFudWFsQ2h1bmtzOiB7XG4gICAgICAgICAgICBlY2hhcnRzOiBbJ2VjaGFydHMnXSwgLy8gXHU1QzA2IGVjaGFydHMgXHU1MzU1XHU3MkVDXHU2MjUzXHU1MzA1XG4gICAgICAgICAgfSxcbiAgICAgICAgfSxcbiAgICAgIH0sXG4gICAgfSxcbiAgICBkZWZpbmU6IHtcbiAgICAgIF9fTUlORV9TWVNURU1fSU5GT19fOiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgIHBrZzoge1xuICAgICAgICAgIHZlcnNpb246IHBrZy52ZXJzaW9uLFxuICAgICAgICAgIGRlcGVuZGVuY2llczogcGtnLmRlcGVuZGVuY2llcyxcbiAgICAgICAgICBkZXZEZXBlbmRlbmNpZXM6IHBrZy5kZXZEZXBlbmRlbmNpZXMsXG4gICAgICAgIH0sXG4gICAgICAgIGxhc3RCdWlsZFRpbWU6IGRheWpzKCkuZm9ybWF0KCdZWVlZLU1NLUREIEhIOm1tOnNzJyksXG4gICAgICB9KSxcbiAgICB9LFxuICAgIHBsdWdpbnM6IGNyZWF0ZVZpdGVQbHVnaW5zKGVudiwgY29tbWFuZCA9PT0gJ2J1aWxkJyksXG4gICAgcmVzb2x2ZToge1xuICAgICAgYWxpYXM6IHtcbiAgICAgICAgJ0AnOiBwYXRoLnJlc29sdmUoX19kaXJuYW1lLCAnc3JjJyksXG4gICAgICAgICcjJzogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJ3R5cGVzJyksXG4gICAgICAgICckJzogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJ3NyYy9wbHVnaW5zJyksXG4gICAgICAgICd+JzogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgJ3NyYy9tb2R1bGVzJyksXG4gICAgICB9LFxuICAgIH0sXG4gICAgY3NzOiB7XG4gICAgICBwcmVwcm9jZXNzb3JPcHRpb25zOiB7XG4gICAgICAgIHNjc3M6IHtcbiAgICAgICAgICBhcGk6ICdtb2Rlcm4tY29tcGlsZXInLFxuICAgICAgICAgIC8vIGFkZGl0aW9uYWxEYXRhOiBzY3NzRmlsZXMuam9pbignJyksXG4gICAgICAgICAgamF2YXNjcmlwdEVuYWJsZWQ6IHRydWUsXG4gICAgICAgIH0sXG4gICAgICB9LFxuICAgIH0sXG4gICAgb3B0aW1pemVEZXBzOiB7IGluY2x1ZGUsIGV4Y2x1ZGUgfSxcbiAgfSlcbn1cbiIsICJ7XG4gIFwibmFtZVwiOiBcIm1pbmVhZG1pbi11aVwiLFxuICBcInR5cGVcIjogXCJtb2R1bGVcIixcbiAgXCJ2ZXJzaW9uXCI6IFwiMy4wLjBcIixcbiAgXCJlbmdpbmVzXCI6IHtcbiAgICBcIm5vZGVcIjogXCJeMjAuMC4wIHx8ID49MjEuMS4wXCJcbiAgfSxcbiAgXCJzY3JpcHRzXCI6IHtcbiAgICBcImRldlwiOiBcInZpdGVcIixcbiAgICBcImJ1aWxkXCI6IFwidml0ZSBidWlsZFwiLFxuICAgIFwic2VydmVcIjogXCJodHRwLXNlcnZlciAuL2Rpc3QgLW9cIixcbiAgICBcInN2Z29cIjogXCJzdmdvIC1mIHNyYy9hc3NldHMvaWNvbnNcIixcbiAgICBcImdlbjppY29uc1wiOiBcImVzbm8gc2NyaXB0cy9nZW4uaWNvbnMubXRzXCIsXG4gICAgXCJwbHVnaW46cHVibGlzaFwiOiBcImVzbm8gc2NyaXB0cy9wbHVnaW4ucHVibGlzaC5tdHNcIixcbiAgICBcImxpbnRcIjogXCJucG0tcnVuLWFsbCAtcyBsaW50OnRzYyBsaW50OmVzbGludCBsaW50OnN0eWxlbGludFwiLFxuICAgIFwibGludDp0c2NcIjogXCJ2dWUtdHNjIC0tbm9FbWl0XCIsXG4gICAgXCJsaW50OmVzbGludFwiOiBcImVzbGludCAuIC0tY2FjaGUgLS1maXhcIixcbiAgICBcImxpbnQ6c3R5bGVsaW50XCI6IFwic3R5bGVsaW50IFxcXCJzcmMvKiovKi57Y3NzLHNjc3MsdnVlfVxcXCIgLS1jYWNoZSAtLWZpeFwiXG4gIH0sXG4gIFwiZGVwZW5kZW5jaWVzXCI6IHtcbiAgICBcIkBpbWVuZ3l1L3Z1ZTMtY29udGV4dC1tZW51XCI6IFwiXjEuNC4yXCIsXG4gICAgXCJAbWluZWFkbWluL2VjaGFydHNcIjogXCJeMS4wLjVcIixcbiAgICBcIkBtaW5lYWRtaW4vZm9ybVwiOiBcIl4xLjAuMjFcIixcbiAgICBcIkBtaW5lYWRtaW4vcHJvLXRhYmxlXCI6IFwiXjEuMC41NVwiLFxuICAgIFwiQG1pbmVhZG1pbi9zZWFyY2hcIjogXCJeMS4wLjI3XCIsXG4gICAgXCJAbWluZWFkbWluL3RhYmxlXCI6IFwiXjEuMC4zM1wiLFxuICAgIFwiQHZ1ZXVzZS9jb3JlXCI6IFwiXjExLjEuMFwiLFxuICAgIFwiQHZ1ZXVzZS9pbnRlZ3JhdGlvbnNcIjogXCJeMTEuMS4wXCIsXG4gICAgXCJAd2FuZ2VkaXRvci9lZGl0b3JcIjogXCJeNS4xLjIzXCIsXG4gICAgXCJAd2FuZ2VkaXRvci9lZGl0b3ItZm9yLXZ1ZVwiOiBcIl41LjEuMTJcIixcbiAgICBcImFuaW1hdGUuY3NzXCI6IFwiXjQuMS4xXCIsXG4gICAgXCJheGlvc1wiOiBcIl4xLjcuOFwiLFxuICAgIFwiZGF5anNcIjogXCJeMS4xMS4xMlwiLFxuICAgIFwiZWNoYXJ0c1wiOiBcIl41LjUuMVwiLFxuICAgIFwiZmlsZS1zYXZlclwiOiBcIl4yLjAuNVwiLFxuICAgIFwiZWxlbWVudC1wbHVzXCI6IFwiXjIuOS4xXCIsXG4gICAgXCJmbG9hdGluZy12dWVcIjogXCI1LjIuMlwiLFxuICAgIFwibG9kYXNoLWVzXCI6IFwiXjQuMTcuMjFcIixcbiAgICBcIm1kLWVkaXRvci12M1wiOiBcIl41LjAuMlwiLFxuICAgIFwibnByb2dyZXNzXCI6IFwiXjAuMi4wXCIsXG4gICAgXCJvdmVybGF5c2Nyb2xsYmFyc1wiOiBcIl4yLjEwLjBcIixcbiAgICBcIm92ZXJsYXlzY3JvbGxiYXJzLXZ1ZVwiOiBcIl4wLjUuOVwiLFxuICAgIFwicGF0aC1icm93c2VyaWZ5XCI6IFwiXjEuMC4xXCIsXG4gICAgXCJwYXRoLXRvLXJlZ2V4cFwiOiBcIl44LjEuMFwiLFxuICAgIFwicGluaWFcIjogXCJeMi4yLjRcIixcbiAgICBcInFzXCI6IFwiXjYuMTMuMFwiLFxuICAgIFwicmFkYXNoXCI6IFwiXjEyLjEuMFwiLFxuICAgIFwicmFkaXgtdnVlXCI6IFwiXjEuOS42XCIsXG4gICAgXCJzb3J0YWJsZWpzXCI6IFwiMS4xNS40XCIsXG4gICAgXCJ2YXVsLXZ1ZVwiOiBcIl4wLjIuMFwiLFxuICAgIFwidnVlXCI6IFwiXjMuNS4xMlwiLFxuICAgIFwidnVlLWkxOG5cIjogXCJeMTAuMC41XCIsXG4gICAgXCJ2dWUtbS1tZXNzYWdlXCI6IFwiXjQuMC4yXCIsXG4gICAgXCJ2dWUtcm91dGVyXCI6IFwiXjQuNS4wXCIsXG4gICAgXCJ3ZWItc3RvcmFnZS1jYWNoZVwiOiBcIl4xLjEuMVwiXG4gIH0sXG4gIFwiZGV2RGVwZW5kZW5jaWVzXCI6IHtcbiAgICBcIkBhbnRmdS9lc2xpbnQtY29uZmlnXCI6IFwiXjMuMTAuMFwiLFxuICAgIFwiQGljb25pZnkvanNvblwiOiBcIl4yLjIuMjc2XCIsXG4gICAgXCJAaWNvbmlmeS92dWVcIjogXCJeNC4xLjJcIixcbiAgICBcIkBpbnRsaWZ5L3VucGx1Z2luLXZ1ZS1pMThuXCI6IFwiXjYuMC4wXCIsXG4gICAgXCJAc3R5bGlzdGljL3N0eWxlbGludC1jb25maWdcIjogXCJeMi4wLjBcIixcbiAgICBcIkB0eXBlcy9hcmNoaXZlclwiOiBcIl42LjAuMlwiLFxuICAgIFwiQHR5cGVzL21vY2tqc1wiOiBcIl4xLjAuMTBcIixcbiAgICBcIkB0eXBlcy9ucHJvZ3Jlc3NcIjogXCJeMC4yLjNcIixcbiAgICBcIkB0eXBlcy9wYXRoLWJyb3dzZXJpZnlcIjogXCJeMS4wLjJcIixcbiAgICBcIkB0eXBlcy9xc1wiOiBcIl42LjkuMTZcIixcbiAgICBcIkB1bm9jc3MvZXNsaW50LXBsdWdpblwiOiBcIl4wLjY0LjFcIixcbiAgICBcIkB2aXRlanMvcGx1Z2luLWxlZ2FjeVwiOiBcIl41LjQuM1wiLFxuICAgIFwiQHZpdGVqcy9wbHVnaW4tdnVlXCI6IFwiXjUuMi4xXCIsXG4gICAgXCJAdml0ZWpzL3BsdWdpbi12dWUtanN4XCI6IFwiXjQuMS4xXCIsXG4gICAgXCJhY2UtYnVpbGRzXCI6IFwiXjEuMzYuNFwiLFxuICAgIFwiYXJjaGl2ZXJcIjogXCJeNy4wLjFcIixcbiAgICBcImF1dG9wcmVmaXhlclwiOiBcIl4xMC40LjIwXCIsXG4gICAgXCJib3hlblwiOiBcIl44LjAuMVwiLFxuICAgIFwiZXNsaW50XCI6IFwiXjkuMTEuMFwiLFxuICAgIFwiZXNub1wiOiBcIl40LjcuMFwiLFxuICAgIFwiZnMtZXh0cmFcIjogXCJeMTEuMi4wXCIsXG4gICAgXCJodHRwLXNlcnZlclwiOiBcIl4xNC4xLjFcIixcbiAgICBcImxpbnQtc3RhZ2VkXCI6IFwiXjE1LjIuMTBcIixcbiAgICBcIm5wbS1ydW4tYWxsMlwiOiBcIl43LjAuMVwiLFxuICAgIFwicGljb2NvbG9yc1wiOiBcIl4xLjEuMFwiLFxuICAgIFwicG9zdGNzc1wiOiBcIl44LjQuNDdcIixcbiAgICBcInNhc3NcIjogXCJeMS43OS4yXCIsXG4gICAgXCJzdHlsZWxpbnRcIjogXCJeMTYuOC4xXCIsXG4gICAgXCJzdHlsZWxpbnQtY29uZmlnLXJlY2Vzcy1vcmRlclwiOiBcIl41LjAuMVwiLFxuICAgIFwic3R5bGVsaW50LWNvbmZpZy1zdGFuZGFyZC1zY3NzXCI6IFwiXjEzLjEuMFwiLFxuICAgIFwic3R5bGVsaW50LWNvbmZpZy1zdGFuZGFyZC12dWVcIjogXCJeMS4wLjBcIixcbiAgICBcInN0eWxlbGludC1zY3NzXCI6IFwiXjYuNy4wXCIsXG4gICAgXCJzdmdvXCI6IFwiXjMuMy4yXCIsXG4gICAgXCJ0ZXJzZXJcIjogXCJeNS4zNi4wXCIsXG4gICAgXCJ0eXBlc2NyaXB0XCI6IFwiXjUuNy4yXCIsXG4gICAgXCJ1bm9jc3NcIjogXCJeMC42NC4xXCIsXG4gICAgXCJ1bnBsdWdpbi1hdXRvLWltcG9ydFwiOiBcIl4wLjE4LjNcIixcbiAgICBcInVucGx1Z2luLXZ1ZS1jb21wb25lbnRzXCI6IFwiXjAuMjcuNFwiLFxuICAgIFwidmNvbnNvbGVcIjogXCJeMy4xNS4xXCIsXG4gICAgXCJ2aXRlXCI6IFwiXjUuNC4xMVwiLFxuICAgIFwidml0ZS1wbHVnaW4tY2h1bmstc3BsaXRcIjogXCJeMC41LjBcIixcbiAgICBcInZpdGUtcGx1Z2luLWNvbXByZXNzaW9uMlwiOiBcIl4xLjMuMFwiLFxuICAgIFwidml0ZS1wbHVnaW4tZmFrZS1zZXJ2ZXJcIjogXCJeMi4xLjJcIixcbiAgICBcInZpdGUtcGx1Z2luLXN2Zy1pY29uc1wiOiBcIl4yLjAuMVwiLFxuICAgIFwidml0ZS1wbHVnaW4tdnVlLWRldnRvb2xzXCI6IFwiXjcuNC41XCIsXG4gICAgXCJ2dWUtdHNjXCI6IFwiXjIuMS42XCIsXG4gICAgXCJ2dWUzLWFjZS1lZGl0b3JcIjogXCJeMi4yLjRcIlxuICB9XG59XG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGVcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvaW5kZXgudHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9pbmRleC50c1wiOy8qKlxuICogTWluZUFkbWluIGlzIGNvbW1pdHRlZCB0byBwcm92aWRpbmcgc29sdXRpb25zIGZvciBxdWlja2x5IGJ1aWxkaW5nIHdlYiBhcHBsaWNhdGlvbnNcbiAqIFBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFIGZpbGUgdGhhdCB3YXMgZGlzdHJpYnV0ZWQgd2l0aCB0aGlzIHNvdXJjZSBjb2RlLFxuICogRm9yIHRoZSBmdWxsIGNvcHlyaWdodCBhbmQgbGljZW5zZSBpbmZvcm1hdGlvbi5cbiAqIFRoYW5rIHlvdSB2ZXJ5IG11Y2ggZm9yIHVzaW5nIE1pbmVBZG1pbi5cbiAqXG4gKiBAQXV0aG9yIFguTW88cm9vdEBpbW9pLmNuPlxuICogQExpbmsgICBodHRwczovL2dpdGh1Yi5jb20vbWluZWFkbWluXG4gKi9cbmltcG9ydCB0eXBlIHsgUGx1Z2luT3B0aW9uIH0gZnJvbSAndml0ZSdcbmltcG9ydCB2dWVMZWdhY3kgZnJvbSAnQHZpdGVqcy9wbHVnaW4tbGVnYWN5J1xuaW1wb3J0IHZ1ZSBmcm9tICdAdml0ZWpzL3BsdWdpbi12dWUnXG5pbXBvcnQgdnVlSnN4IGZyb20gJ0B2aXRlanMvcGx1Z2luLXZ1ZS1qc3gnXG5pbXBvcnQgY3JlYXRlQXJjaGl2ZXIgZnJvbSAnLi9hcmNoaXZlcidcbmltcG9ydCBjcmVhdGVBdXRvSW1wb3J0IGZyb20gJy4vYXV0by1pbXBvcnQnXG5pbXBvcnQgY3JlYXRlQ2h1bmtTcGxpdCBmcm9tICcuL2NodW5rJ1xuaW1wb3J0IGNyZWF0ZUNvbXBvbmVudHMgZnJvbSAnLi9jb21wb25lbnRzJ1xuaW1wb3J0IGNyZWF0ZUNvbXByZXNzaW9uIGZyb20gJy4vY29tcHJlc3Npb24nXG5pbXBvcnQgY3JlYXRlRGV2dG9vbHMgZnJvbSAnLi9kZXZ0b29scydcbmltcG9ydCBjcmVhdGVJMThuTWVzc2FnZSBmcm9tICcuL2kxOG4tbWVzc2FnZSdcbmltcG9ydCBzdGFydEluZm8gZnJvbSAnLi9zdGFydC1pbmZvJ1xuaW1wb3J0IGNyZWF0ZVN2Z0ljb24gZnJvbSAnLi9zdmctaWNvbidcbmltcG9ydCBjcmVhdGVVbm9jc3MgZnJvbSAnLi91bm9jc3MnXG5cbmV4cG9ydCBkZWZhdWx0IGZ1bmN0aW9uIGNyZWF0ZVZpdGVQbHVnaW5zKHZpdGVFbnY6IGFueSwgaXNCdWlsZCA9IGZhbHNlKSB7XG4gIGNvbnN0IHZpdGVQbHVnaW5zOiAoUGx1Z2luT3B0aW9uIHwgUGx1Z2luT3B0aW9uW10pW10gPSBbXG4gICAgdnVlKCksXG4gICAgdnVlSnN4KCksXG4gICAgc3RhcnRJbmZvKCksXG4gICAgdnVlTGVnYWN5KHtcbiAgICAgIHJlbmRlckxlZ2FjeUNodW5rczogZmFsc2UsXG4gICAgICBtb2Rlcm5Qb2x5ZmlsbHM6IFtcbiAgICAgICAgJ2VzLmFycmF5LmF0JyxcbiAgICAgICAgJ2VzLmFycmF5LmZpbmQtbGFzdCcsXG4gICAgICBdLFxuICAgIH0pLFxuICBdXG4gIHZpdGVQbHVnaW5zLnB1c2goY3JlYXRlRGV2dG9vbHModml0ZUVudikpXG4gIHZpdGVQbHVnaW5zLnB1c2goY3JlYXRlQXV0b0ltcG9ydCgpKVxuICB2aXRlUGx1Z2lucy5wdXNoKGNyZWF0ZUNvbXBvbmVudHMoKSlcbiAgdml0ZVBsdWdpbnMucHVzaChjcmVhdGVVbm9jc3MoKSlcbiAgdml0ZVBsdWdpbnMucHVzaChjcmVhdGVTdmdJY29uKGlzQnVpbGQpKVxuICB2aXRlUGx1Z2lucy5wdXNoKC4uLmNyZWF0ZUNvbXByZXNzaW9uKHZpdGVFbnYsIGlzQnVpbGQpKVxuICB2aXRlUGx1Z2lucy5wdXNoKGNyZWF0ZUFyY2hpdmVyKHZpdGVFbnYpKVxuICB2aXRlUGx1Z2lucy5wdXNoKGNyZWF0ZUkxOG5NZXNzYWdlKCkpXG4gIHZpdGVQbHVnaW5zLnB1c2goY3JlYXRlQ2h1bmtTcGxpdCgpKVxuICByZXR1cm4gdml0ZVBsdWdpbnNcbn1cbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9hcmNoaXZlci50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL2FyY2hpdmVyLnRzXCI7LyoqXG4gKiBNaW5lQWRtaW4gaXMgY29tbWl0dGVkIHRvIHByb3ZpZGluZyBzb2x1dGlvbnMgZm9yIHF1aWNrbHkgYnVpbGRpbmcgd2ViIGFwcGxpY2F0aW9uc1xuICogUGxlYXNlIHZpZXcgdGhlIExJQ0VOU0UgZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUsXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLlxuICogVGhhbmsgeW91IHZlcnkgbXVjaCBmb3IgdXNpbmcgTWluZUFkbWluLlxuICpcbiAqIEBBdXRob3IgWC5Nbzxyb290QGltb2kuY24+XG4gKiBATGluayAgIGh0dHBzOi8vZ2l0aHViLmNvbS9taW5lYWRtaW5cbiAqL1xuaW1wb3J0IGZzIGZyb20gJ25vZGU6ZnMnXG5pbXBvcnQgZGF5anMgZnJvbSAnZGF5anMnXG5pbXBvcnQgYXJjaGl2ZXIgZnJvbSAnYXJjaGl2ZXInXG5pbXBvcnQgdHlwZSB7IFBsdWdpbiB9IGZyb20gJ3ZpdGUnXG5cbmZ1bmN0aW9uIHNsZWVwKG1zOiBudW1iZXIpIHtcbiAgcmV0dXJuIG5ldyBQcm9taXNlKHJlc29sdmUgPT4gc2V0VGltZW91dChyZXNvbHZlLCBtcykpXG59XG5cbmV4cG9ydCBkZWZhdWx0IGZ1bmN0aW9uIGNyZWF0ZUFyY2hpdmVyKGVudjogYW55KTogUGx1Z2luIHtcbiAgY29uc3QgeyBWSVRFX0JVSUxEX0FSQ0hJVkUgfSA9IGVudlxuICBsZXQgb3V0RGlyOiBzdHJpbmdcbiAgcmV0dXJuIHtcbiAgICBuYW1lOiAndml0ZS1wbHVnaW4tYXJjaGl2ZXInLFxuICAgIGFwcGx5OiAnYnVpbGQnLFxuICAgIGNvbmZpZ1Jlc29sdmVkKHJlc29sdmVkQ29uZmlnKSB7XG4gICAgICBvdXREaXIgPSByZXNvbHZlZENvbmZpZy5idWlsZC5vdXREaXJcbiAgICB9LFxuICAgIGFzeW5jIGNsb3NlQnVuZGxlKCkge1xuICAgICAgaWYgKFsnemlwJywgJ3RhciddLmluY2x1ZGVzKFZJVEVfQlVJTERfQVJDSElWRSkpIHtcbiAgICAgICAgYXdhaXQgc2xlZXAoMTAwMClcbiAgICAgICAgY29uc3QgYXJjaGl2ZSA9IGFyY2hpdmVyKFZJVEVfQlVJTERfQVJDSElWRSwge1xuICAgICAgICAgIC4uLihWSVRFX0JVSUxEX0FSQ0hJVkUgPT09ICd6aXAnICYmIHsgemxpYjogeyBsZXZlbDogOSB9IH0pLFxuICAgICAgICAgIC4uLihWSVRFX0JVSUxEX0FSQ0hJVkUgPT09ICd0YXInICYmIHsgZ3ppcDogdHJ1ZSwgZ3ppcE9wdGlvbnM6IHsgbGV2ZWw6IDkgfSB9KSxcbiAgICAgICAgfSlcbiAgICAgICAgY29uc3Qgb3V0cHV0ID0gZnMuY3JlYXRlV3JpdGVTdHJlYW0oYCR7b3V0RGlyfS4ke2RheWpzKCkuZm9ybWF0KCdZWVlZLU1NLURELUhILW1tLXNzJyl9LiR7VklURV9CVUlMRF9BUkNISVZFID09PSAnemlwJyA/ICd6aXAnIDogJ3Rhci5neid9YClcbiAgICAgICAgYXJjaGl2ZS5waXBlKG91dHB1dClcbiAgICAgICAgYXJjaGl2ZS5kaXJlY3Rvcnkob3V0RGlyLCBmYWxzZSlcbiAgICAgICAgYXdhaXQgYXJjaGl2ZS5maW5hbGl6ZSgpXG4gICAgICB9XG4gICAgfSxcbiAgfVxufVxuIiwgImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL2F1dG8taW1wb3J0LnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvYXV0by1pbXBvcnQudHNcIjsvKipcbiAqIE1pbmVBZG1pbiBpcyBjb21taXR0ZWQgdG8gcHJvdmlkaW5nIHNvbHV0aW9ucyBmb3IgcXVpY2tseSBidWlsZGluZyB3ZWIgYXBwbGljYXRpb25zXG4gKiBQbGVhc2UgdmlldyB0aGUgTElDRU5TRSBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZSxcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24uXG4gKiBUaGFuayB5b3UgdmVyeSBtdWNoIGZvciB1c2luZyBNaW5lQWRtaW4uXG4gKlxuICogQEF1dGhvciBYLk1vPHJvb3RAaW1vaS5jbj5cbiAqIEBMaW5rICAgaHR0cHM6Ly9naXRodWIuY29tL21pbmVhZG1pblxuICovXG5pbXBvcnQgYXV0b0ltcG9ydCBmcm9tICd1bnBsdWdpbi1hdXRvLWltcG9ydC92aXRlJ1xuXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBjcmVhdGVBdXRvSW1wb3J0KCkge1xuICByZXR1cm4gYXV0b0ltcG9ydCh7XG4gICAgaW1wb3J0czogW1xuICAgICAgJ3Z1ZScsXG4gICAgICAndnVlLXJvdXRlcicsXG4gICAgICAncGluaWEnLFxuICAgIF0sXG4gICAgZHRzOiAnLi90eXBlcy9hdXRvLWltcG9ydHMuZC50cycsXG4gICAgZGlyczogW1xuICAgICAgJy4vc3JjL2hvb2tzL2F1dG8taW1wb3J0cy8qKicsXG4gICAgICAnLi9zcmMvc3RvcmUvbW9kdWxlcy8qKicsXG4gICAgXSxcbiAgfSlcbn1cbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9jaHVuay50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL2NodW5rLnRzXCI7LyoqXG4gKiBNaW5lQWRtaW4gaXMgY29tbWl0dGVkIHRvIHByb3ZpZGluZyBzb2x1dGlvbnMgZm9yIHF1aWNrbHkgYnVpbGRpbmcgd2ViIGFwcGxpY2F0aW9uc1xuICogUGxlYXNlIHZpZXcgdGhlIExJQ0VOU0UgZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUsXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLlxuICogVGhhbmsgeW91IHZlcnkgbXVjaCBmb3IgdXNpbmcgTWluZUFkbWluLlxuICpcbiAqIEBBdXRob3IgWC5Nbzxyb290QGltb2kuY24+XG4gKiBATGluayAgIGh0dHBzOi8vZ2l0aHViLmNvbS9taW5lYWRtaW5cbiAqL1xuaW1wb3J0IHsgY2h1bmtTcGxpdFBsdWdpbiB9IGZyb20gJ3ZpdGUtcGx1Z2luLWNodW5rLXNwbGl0J1xuXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBjcmVhdGVDaHVua1NwbGl0KCkge1xuICByZXR1cm4gY2h1bmtTcGxpdFBsdWdpbih7XG4gICAgc3RyYXRlZ3k6ICdkZWZhdWx0JyxcbiAgfSlcbn1cbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9jb21wb25lbnRzLnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvY29tcG9uZW50cy50c1wiOy8qKlxuICogTWluZUFkbWluIGlzIGNvbW1pdHRlZCB0byBwcm92aWRpbmcgc29sdXRpb25zIGZvciBxdWlja2x5IGJ1aWxkaW5nIHdlYiBhcHBsaWNhdGlvbnNcbiAqIFBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFIGZpbGUgdGhhdCB3YXMgZGlzdHJpYnV0ZWQgd2l0aCB0aGlzIHNvdXJjZSBjb2RlLFxuICogRm9yIHRoZSBmdWxsIGNvcHlyaWdodCBhbmQgbGljZW5zZSBpbmZvcm1hdGlvbi5cbiAqIFRoYW5rIHlvdSB2ZXJ5IG11Y2ggZm9yIHVzaW5nIE1pbmVBZG1pbi5cbiAqXG4gKiBAQXV0aG9yIFguTW88cm9vdEBpbW9pLmNuPlxuICogQExpbmsgICBodHRwczovL2dpdGh1Yi5jb20vbWluZWFkbWluXG4gKi9cbmltcG9ydCBjb21wb25lbnRzIGZyb20gJ3VucGx1Z2luLXZ1ZS1jb21wb25lbnRzL3ZpdGUnXG5cbmV4cG9ydCBkZWZhdWx0IGZ1bmN0aW9uIGNyZWF0ZUNvbXBvbmVudHMoKSB7XG4gIHJldHVybiBjb21wb25lbnRzKHtcbiAgICBkaXJzOiBbJ3NyYy9jb21wb25lbnRzJ10sXG4gICAgaW5jbHVkZTogWy9cXC52dWUkLywgL1xcLnZ1ZVxcP3Z1ZS8sIC9cXC50c3gkL10sXG4gICAgZHRzOiAnLi90eXBlcy9jb21wb25lbnRzLmQudHMnLFxuICB9KVxufVxuIiwgImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL2NvbXByZXNzaW9uLnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvY29tcHJlc3Npb24udHNcIjsvKipcbiAqIE1pbmVBZG1pbiBpcyBjb21taXR0ZWQgdG8gcHJvdmlkaW5nIHNvbHV0aW9ucyBmb3IgcXVpY2tseSBidWlsZGluZyB3ZWIgYXBwbGljYXRpb25zXG4gKiBQbGVhc2UgdmlldyB0aGUgTElDRU5TRSBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZSxcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24uXG4gKiBUaGFuayB5b3UgdmVyeSBtdWNoIGZvciB1c2luZyBNaW5lQWRtaW4uXG4gKlxuICogQEF1dGhvciBYLk1vPHJvb3RAaW1vaS5jbj5cbiAqIEBMaW5rICAgaHR0cHM6Ly9naXRodWIuY29tL21pbmVhZG1pblxuICovXG5pbXBvcnQgeyBjb21wcmVzc2lvbiB9IGZyb20gJ3ZpdGUtcGx1Z2luLWNvbXByZXNzaW9uMidcbmltcG9ydCB0eXBlIHsgUGx1Z2luT3B0aW9uIH0gZnJvbSAndml0ZSdcblxuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gY3JlYXRlQ29tcHJlc3Npb24oZW52OiBhbnksIGlzQnVpbGQ6IGJvb2xlYW4pIHtcbiAgY29uc3QgcGx1Z2luOiAoUGx1Z2luT3B0aW9uIHwgUGx1Z2luT3B0aW9uW10pW10gPSBbXVxuICBpZiAoaXNCdWlsZCkge1xuICAgIGNvbnN0IHsgVklURV9CVUlMRF9DT01QUkVTUyB9ID0gZW52XG4gICAgY29uc3QgY29tcHJlc3NMaXN0ID0gVklURV9CVUlMRF9DT01QUkVTUy5zcGxpdCgnLCcpXG4gICAgaWYgKGNvbXByZXNzTGlzdC5pbmNsdWRlcygnZ3ppcCcpKSB7XG4gICAgICBwbHVnaW4ucHVzaChcbiAgICAgICAgY29tcHJlc3Npb24oKSxcbiAgICAgIClcbiAgICB9XG4gICAgaWYgKGNvbXByZXNzTGlzdC5pbmNsdWRlcygnYnJvdGxpJykpIHtcbiAgICAgIHBsdWdpbi5wdXNoKFxuICAgICAgICBjb21wcmVzc2lvbih7XG4gICAgICAgICAgZXhjbHVkZTogWy9cXC4oYnIpJC8sIC9cXC4oZ3opJC9dLFxuICAgICAgICAgIGFsZ29yaXRobTogJ2Jyb3RsaUNvbXByZXNzJyxcbiAgICAgICAgfSksXG4gICAgICApXG4gICAgfVxuICB9XG4gIHJldHVybiBwbHVnaW5cbn1cbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9kZXZ0b29scy50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL2RldnRvb2xzLnRzXCI7LyoqXG4gKiBNaW5lQWRtaW4gaXMgY29tbWl0dGVkIHRvIHByb3ZpZGluZyBzb2x1dGlvbnMgZm9yIHF1aWNrbHkgYnVpbGRpbmcgd2ViIGFwcGxpY2F0aW9uc1xuICogUGxlYXNlIHZpZXcgdGhlIExJQ0VOU0UgZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUsXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLlxuICogVGhhbmsgeW91IHZlcnkgbXVjaCBmb3IgdXNpbmcgTWluZUFkbWluLlxuICpcbiAqIEBBdXRob3IgWC5Nbzxyb290QGltb2kuY24+XG4gKiBATGluayAgIGh0dHBzOi8vZ2l0aHViLmNvbS9taW5lYWRtaW5cbiAqL1xuaW1wb3J0IFZ1ZURldlRvb2xzIGZyb20gJ3ZpdGUtcGx1Z2luLXZ1ZS1kZXZ0b29scydcblxuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gY3JlYXRlRGV2dG9vbHMoZW52KSB7XG4gIGNvbnN0IHsgVklURV9PUEVOX0RFVlRPT0xTIH0gPSBlbnZcbiAgcmV0dXJuIFZJVEVfT1BFTl9ERVZUT09MUyA9PT0gJ3RydWUnICYmIFZ1ZURldlRvb2xzKClcbn1cbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9pMThuLW1lc3NhZ2UudHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9pMThuLW1lc3NhZ2UudHNcIjsvKipcbiAqIE1pbmVBZG1pbiBpcyBjb21taXR0ZWQgdG8gcHJvdmlkaW5nIHNvbHV0aW9ucyBmb3IgcXVpY2tseSBidWlsZGluZyB3ZWIgYXBwbGljYXRpb25zXG4gKiBQbGVhc2UgdmlldyB0aGUgTElDRU5TRSBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZSxcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24uXG4gKiBUaGFuayB5b3UgdmVyeSBtdWNoIGZvciB1c2luZyBNaW5lQWRtaW4uXG4gKlxuICogQEF1dGhvciBYLk1vPHJvb3RAaW1vaS5jbj5cbiAqIEBMaW5rICAgaHR0cHM6Ly9naXRodWIuY29tL21pbmVhZG1pblxuICovXG5pbXBvcnQgVnVlSTE4blBsdWdpbiBmcm9tICdAaW50bGlmeS91bnBsdWdpbi12dWUtaTE4bi92aXRlJ1xuXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBjcmVhdGVJMThuTWVzc2FnZSgpIHtcbiAgcmV0dXJuIFZ1ZUkxOG5QbHVnaW4oe1xuICAgIGluY2x1ZGU6IFtcbiAgICAgICcuL3NyYy9sb2NhbGVzLyoqJyxcbiAgICAgICcuL3NyYy9tb2R1bGVzLyoqL2xvY2FsZXMvKionLFxuICAgICAgJy4vc3JjL3BsdWdpbnMvKi8qKi9sb2NhbGVzLyoqJyxcbiAgICBdLFxuICB9KVxufVxuIiwgImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL3N0YXJ0LWluZm8udHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9zdGFydC1pbmZvLnRzXCI7LyoqXG4gKiBNaW5lQWRtaW4gaXMgY29tbWl0dGVkIHRvIHByb3ZpZGluZyBzb2x1dGlvbnMgZm9yIHF1aWNrbHkgYnVpbGRpbmcgd2ViIGFwcGxpY2F0aW9uc1xuICogUGxlYXNlIHZpZXcgdGhlIExJQ0VOU0UgZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUsXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLlxuICogVGhhbmsgeW91IHZlcnkgbXVjaCBmb3IgdXNpbmcgTWluZUFkbWluLlxuICpcbiAqIEBBdXRob3IgWC5Nbzxyb290QGltb2kuY24+XG4gKiBATGluayAgIGh0dHBzOi8vZ2l0aHViLmNvbS9taW5lYWRtaW5cbiAqL1xuaW1wb3J0IGJveGVuIGZyb20gJ2JveGVuJ1xuaW1wb3J0IHBpY29jb2xvcnMgZnJvbSAncGljb2NvbG9ycydcbmltcG9ydCBwa2cgZnJvbSAnLi4vcGFja2FnZS5qc29uJ1xuXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBzdGFydEluZm8oKTogYW55IHtcbiAgcmV0dXJuIHtcbiAgICBuYW1lOiAnc3RhcnRJbmZvJyxcbiAgICBhcHBseTogJ3NlcnZlJyxcbiAgICBhc3luYyBidWlsZFN0YXJ0KCkge1xuICAgICAgY29uc3QgeyBib2xkLCBjeWFuLCB1bmRlcmxpbmUgfSA9IHBpY29jb2xvcnNcblxuICAgICAgY29uc29sZS5sb2coXG4gICAgICAgIGJveGVuKFxuICAgICAgICAgIGAke2JvbGQoY3lhbihgTWluZUFkbWluIHYke3BrZy52ZXJzaW9ufWApKX1cXG5cXG4ke3VuZGVybGluZSgnaHR0cHM6Ly9naXRodWIuY29tL21pbmVhZG1pbicpfWAsXG4gICAgICAgICAge1xuICAgICAgICAgICAgcGFkZGluZzogMSxcbiAgICAgICAgICAgIG1hcmdpbjogMSxcbiAgICAgICAgICAgIGJvcmRlclN0eWxlOiAnZG91YmxlJyxcbiAgICAgICAgICAgIHRpdGxlOiAnV2VsY29tZSB1c2UnLFxuICAgICAgICAgICAgdGl0bGVBbGlnbm1lbnQ6ICdjZW50ZXInLFxuICAgICAgICAgICAgdGV4dEFsaWdubWVudDogJ2NlbnRlcicsXG4gICAgICAgICAgfSxcbiAgICAgICAgKSxcbiAgICAgIClcbiAgICB9LFxuICB9XG59XG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGVcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvc3ZnLWljb24udHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9zdmctaWNvbi50c1wiOy8qKlxuICogTWluZUFkbWluIGlzIGNvbW1pdHRlZCB0byBwcm92aWRpbmcgc29sdXRpb25zIGZvciBxdWlja2x5IGJ1aWxkaW5nIHdlYiBhcHBsaWNhdGlvbnNcbiAqIFBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFIGZpbGUgdGhhdCB3YXMgZGlzdHJpYnV0ZWQgd2l0aCB0aGlzIHNvdXJjZSBjb2RlLFxuICogRm9yIHRoZSBmdWxsIGNvcHlyaWdodCBhbmQgbGljZW5zZSBpbmZvcm1hdGlvbi5cbiAqIFRoYW5rIHlvdSB2ZXJ5IG11Y2ggZm9yIHVzaW5nIE1pbmVBZG1pbi5cbiAqXG4gKiBAQXV0aG9yIFguTW88cm9vdEBpbW9pLmNuPlxuICogQExpbmsgICBodHRwczovL2dpdGh1Yi5jb20vbWluZWFkbWluXG4gKi9cbmltcG9ydCBwYXRoIGZyb20gJ25vZGU6cGF0aCdcbmltcG9ydCBwcm9jZXNzIGZyb20gJ25vZGU6cHJvY2VzcydcbmltcG9ydCB7IGNyZWF0ZVN2Z0ljb25zUGx1Z2luIH0gZnJvbSAndml0ZS1wbHVnaW4tc3ZnLWljb25zJ1xuXG5leHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBjcmVhdGVTdmdJY29uKGlzQnVpbGQ6IGJvb2xlYW4pIHtcbiAgcmV0dXJuIGNyZWF0ZVN2Z0ljb25zUGx1Z2luKHtcbiAgICBpY29uRGlyczogW3BhdGgucmVzb2x2ZShwcm9jZXNzLmN3ZCgpLCAnc3JjL2Fzc2V0cy9pY29ucy8nKV0sXG4gICAgc3ltYm9sSWQ6ICdpY29uLVtkaXJdLVtuYW1lXScsXG4gICAgc3Znb09wdGlvbnM6IGlzQnVpbGQsXG4gIH0pXG59XG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGVcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIi9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvdW5vY3NzLnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9Vc2Vycy9tYWNvcy9EZXNrdG9wL3Byb2plY3QvdjMvd2ViL3ZpdGUvdW5vY3NzLnRzXCI7LyoqXG4gKiBNaW5lQWRtaW4gaXMgY29tbWl0dGVkIHRvIHByb3ZpZGluZyBzb2x1dGlvbnMgZm9yIHF1aWNrbHkgYnVpbGRpbmcgd2ViIGFwcGxpY2F0aW9uc1xuICogUGxlYXNlIHZpZXcgdGhlIExJQ0VOU0UgZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUsXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLlxuICogVGhhbmsgeW91IHZlcnkgbXVjaCBmb3IgdXNpbmcgTWluZUFkbWluLlxuICpcbiAqIEBBdXRob3IgWC5Nbzxyb290QGltb2kuY24+XG4gKiBATGluayAgIGh0dHBzOi8vZ2l0aHViLmNvbS9taW5lYWRtaW5cbiAqL1xuaW1wb3J0IFVub2NzcyBmcm9tICd1bm9jc3Mvdml0ZSdcblxuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gY3JlYXRlVW5vY3NzKCkge1xuICByZXR1cm4gVW5vY3NzKClcbn1cbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL21hY29zL0Rlc2t0b3AvcHJvamVjdC92My93ZWIvdml0ZS9vcHRpbWl6ZS50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vVXNlcnMvbWFjb3MvRGVza3RvcC9wcm9qZWN0L3YzL3dlYi92aXRlL29wdGltaXplLnRzXCI7LyoqXG4gKiBNaW5lQWRtaW4gaXMgY29tbWl0dGVkIHRvIHByb3ZpZGluZyBzb2x1dGlvbnMgZm9yIHF1aWNrbHkgYnVpbGRpbmcgd2ViIGFwcGxpY2F0aW9uc1xuICogUGxlYXNlIHZpZXcgdGhlIExJQ0VOU0UgZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUsXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLlxuICogVGhhbmsgeW91IHZlcnkgbXVjaCBmb3IgdXNpbmcgTWluZUFkbWluLlxuICpcbiAqIEBBdXRob3IgWC5Nbzxyb290QGltb2kuY24+XG4gKiBATGluayAgIGh0dHBzOi8vZ2l0aHViLmNvbS9taW5lYWRtaW5cbiAqL1xuY29uc3QgaW5jbHVkZSA9IFtcbiAgJ0BtaW5lYWRtaW4vdGFibGUnLFxuICAnQG1pbmVhZG1pbi9mb3JtJyxcbiAgJ0BpbWVuZ3l1L3Z1ZTMtY29udGV4dC1tZW51JyxcbiAgJ0B2dWV1c2UvY29yZScsXG4gICdAdnVldXNlL2ludGVncmF0aW9ucycsXG4gICdheGlvcycsXG4gICdkYXlqcycsXG4gICdlY2hhcnRzJyxcbiAgJ2Zsb2F0aW5nLXZ1ZScsXG4gICdsb2Rhc2gtZXMnLFxuICAnbnByb2dyZXNzJyxcbiAgJ292ZXJsYXlzY3JvbGxiYXJzJyxcbiAgJ292ZXJsYXlzY3JvbGxiYXJzLXZ1ZScsXG4gICdwYXRoLWJyb3dzZXJpZnknLFxuICAncGF0aC10by1yZWdleHAnLFxuICAncGluaWEnLFxuICAncmFkYXNoJyxcbiAgJ3JhZGl4LXZ1ZScsXG4gICd2YXVsLXZ1ZScsXG4gICdxcycsXG4gICd3ZWItc3RvcmFnZS1jYWNoZScsXG4gICdzb3J0YWJsZWpzJyxcbiAgJ3Z1ZScsXG4gICd2dWUtaTE4bicsXG4gICd2dWUtbS1tZXNzYWdlJyxcbiAgJ3Z1ZS1yb3V0ZXInLFxuICAnZWxlbWVudC1wbHVzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcycsXG4gICdlbGVtZW50LXBsdXMvZXMvbG9jYWxlL2xhbmcvemgtY24nLFxuICAnZWxlbWVudC1wbHVzL2VzL2xvY2FsZS9sYW5nL2VuJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2F2YXRhci9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvc3BhY2Uvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2JhY2t0b3Avc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2Zvcm0vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3JhZGlvLWdyb3VwL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9yYWRpby9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvY2hlY2tib3gvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2NoZWNrYm94LWdyb3VwL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9zd2l0Y2gvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3RpbWUtcGlja2VyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kYXRlLXBpY2tlci9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvZGVzY3JpcHRpb25zL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kZXNjcmlwdGlvbnMtaXRlbS9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvbGluay9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvdG9vbHRpcC9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvZHJhd2VyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kaWFsb2cvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2NoZWNrYm94LWJ1dHRvbi9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvb3B0aW9uLWdyb3VwL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9yYWRpby1idXR0b24vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2Nhc2NhZGVyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9jb2xvci1waWNrZXIvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2lucHV0LW51bWJlci9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvcmF0ZS9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvc2VsZWN0LXYyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy90cmVlLXNlbGVjdC9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvc2xpZGVyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy90aW1lLXNlbGVjdC9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvYXV0b2NvbXBsZXRlL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9pbWFnZS12aWV3ZXIvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3VwbG9hZC9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvY29sL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9mb3JtLWl0ZW0vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2FsZXJ0L3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9icmVhZGNydW1iL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9zZWxlY3Qvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2lucHV0L3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9icmVhZGNydW1iLWl0ZW0vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3RhZy9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvcGFnaW5hdGlvbi9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvdGFibGUvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3RhYmxlLXYyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy90YWJsZS1jb2x1bW4vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2NhcmQvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3Jvdy9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvYnV0dG9uL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9tZW51L3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9zdWItbWVudS9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvbWVudS1pdGVtL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9vcHRpb24vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2Ryb3Bkb3duL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kcm9wZG93bi1tZW51L3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kcm9wZG93bi1pdGVtL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9za2VsZXRvbi9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvc2tlbGV0b24vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2JhY2t0b3Avc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL21lbnUvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3N1Yi1tZW51L3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9tZW51LWl0ZW0vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2Ryb3Bkb3duL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy90cmVlL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kcm9wZG93bi1tZW51L3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9kcm9wZG93bi1pdGVtL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9iYWRnZS9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvYnJlYWRjcnVtYi9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvYnJlYWRjcnVtYi1pdGVtL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9pbWFnZS9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvY29sbGFwc2UtdHJhbnNpdGlvbi9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvdGltZWxpbmUvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3RpbWVsaW5lLWl0ZW0vc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL2NvbGxhcHNlL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9jb2xsYXBzZS1pdGVtL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9idXR0b24tZ3JvdXAvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3RleHQvc3R5bGUvY3NzJyxcbiAgJ2VsZW1lbnQtcGx1cy9lcy9jb21wb25lbnRzL3NlZ21lbnRlZC9zdHlsZS9jc3MnLFxuICAnZWxlbWVudC1wbHVzL2VzL2NvbXBvbmVudHMvZm9vdGVyL3N0eWxlL2NzcycsXG4gICdlbGVtZW50LXBsdXMvZXMvY29tcG9uZW50cy9lbXB0eS9zdHlsZS9jc3MnLFxuXVxuXG5jb25zdCBleGNsdWRlID0gWydAaWNvbmlmeS9qc29uJ11cblxuZXhwb3J0IHsgZXhjbHVkZSwgaW5jbHVkZSB9XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQTJSLE9BQU9BLFdBQVU7QUFDNVMsT0FBT0MsY0FBYTtBQUNwQixPQUFPQyxZQUFXO0FBQ2xCLFNBQVMsY0FBYyxlQUFlOzs7QUNIdEM7QUFBQSxFQUNFLE1BQVE7QUFBQSxFQUNSLE1BQVE7QUFBQSxFQUNSLFNBQVc7QUFBQSxFQUNYLFNBQVc7QUFBQSxJQUNULE1BQVE7QUFBQSxFQUNWO0FBQUEsRUFDQSxTQUFXO0FBQUEsSUFDVCxLQUFPO0FBQUEsSUFDUCxPQUFTO0FBQUEsSUFDVCxPQUFTO0FBQUEsSUFDVCxNQUFRO0FBQUEsSUFDUixhQUFhO0FBQUEsSUFDYixrQkFBa0I7QUFBQSxJQUNsQixNQUFRO0FBQUEsSUFDUixZQUFZO0FBQUEsSUFDWixlQUFlO0FBQUEsSUFDZixrQkFBa0I7QUFBQSxFQUNwQjtBQUFBLEVBQ0EsY0FBZ0I7QUFBQSxJQUNkLDhCQUE4QjtBQUFBLElBQzlCLHNCQUFzQjtBQUFBLElBQ3RCLG1CQUFtQjtBQUFBLElBQ25CLHdCQUF3QjtBQUFBLElBQ3hCLHFCQUFxQjtBQUFBLElBQ3JCLG9CQUFvQjtBQUFBLElBQ3BCLGdCQUFnQjtBQUFBLElBQ2hCLHdCQUF3QjtBQUFBLElBQ3hCLHNCQUFzQjtBQUFBLElBQ3RCLDhCQUE4QjtBQUFBLElBQzlCLGVBQWU7QUFBQSxJQUNmLE9BQVM7QUFBQSxJQUNULE9BQVM7QUFBQSxJQUNULFNBQVc7QUFBQSxJQUNYLGNBQWM7QUFBQSxJQUNkLGdCQUFnQjtBQUFBLElBQ2hCLGdCQUFnQjtBQUFBLElBQ2hC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Q0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQ0Y7QUFFQSxJQUFNLFVBQVUsQ0FBQyxlQUFlOzs7QWJ2SGhDLElBQU0sbUNBQW1DO0FBU3pDLElBQU8sc0JBQVEsT0FBTyxFQUFFLE1BQU0sUUFBUSxNQUFNO0FBQzFDLFFBQU0sTUFBTSxRQUFRLE1BQU1DLFNBQVEsSUFBSSxDQUFDO0FBQ3ZDLFdBQVMsZUFBd0I7QUFDL0IsV0FBTyxTQUFTO0FBQUEsRUFDbEI7QUFVQSxRQUFNLGNBQWMsSUFBSTtBQUN4QixTQUFPLGFBQWE7QUFBQSxJQUNsQixNQUFNLElBQUk7QUFBQTtBQUFBLElBRVYsUUFBUTtBQUFBLE1BQ04sTUFBTTtBQUFBLE1BQ04sTUFBTSxPQUFPLElBQUksaUJBQWlCQSxTQUFRLElBQUksSUFBSTtBQUFBLE1BQ2xELE9BQU87QUFBQSxRQUNMLENBQUMsV0FBVyxHQUFHO0FBQUEsVUFDYixRQUFRLElBQUk7QUFBQSxVQUNaLGNBQWMsWUFBWSxXQUFXLElBQUksb0JBQW9CO0FBQUEsVUFDN0QsU0FBUyxDQUFBQyxVQUFRQSxNQUFLLFFBQVEsSUFBSSxPQUFPLElBQUksV0FBVyxFQUFFLEdBQUcsRUFBRTtBQUFBLFFBQ2pFO0FBQUEsTUFDRjtBQUFBLElBQ0Y7QUFBQSxJQUNBLFNBQVM7QUFBQSxNQUNQLE1BQU0sYUFBYSxJQUFJLENBQUMsV0FBVyxVQUFVLElBQUksQ0FBQztBQUFBLElBQ3BEO0FBQUE7QUFBQSxJQUVBLE9BQU87QUFBQSxNQUNMLFFBQVEsZUFBZSxTQUFTLFFBQVEsSUFBSTtBQUFBLE1BQzVDLFdBQVcsSUFBSSx5QkFBeUI7QUFBQSxNQUN4QyxRQUFRO0FBQUEsTUFDUixlQUFlO0FBQUEsUUFDYixRQUFRO0FBQUEsVUFDTixnQkFBZ0I7QUFBQSxVQUNoQixnQkFBZ0I7QUFBQSxVQUNoQixnQkFBZ0I7QUFBQSxVQUNoQixjQUFjO0FBQUEsWUFDWixTQUFTLENBQUMsU0FBUztBQUFBO0FBQUEsVUFDckI7QUFBQSxRQUNGO0FBQUEsTUFDRjtBQUFBLElBQ0Y7QUFBQSxJQUNBLFFBQVE7QUFBQSxNQUNOLHNCQUFzQixLQUFLLFVBQVU7QUFBQSxRQUNuQyxLQUFLO0FBQUEsVUFDSCxTQUFTLGdCQUFJO0FBQUEsVUFDYixjQUFjLGdCQUFJO0FBQUEsVUFDbEIsaUJBQWlCLGdCQUFJO0FBQUEsUUFDdkI7QUFBQSxRQUNBLGVBQWVDLE9BQU0sRUFBRSxPQUFPLHFCQUFxQjtBQUFBLE1BQ3JELENBQUM7QUFBQSxJQUNIO0FBQUEsSUFDQSxTQUFTLGtCQUFrQixLQUFLLFlBQVksT0FBTztBQUFBLElBQ25ELFNBQVM7QUFBQSxNQUNQLE9BQU87QUFBQSxRQUNMLEtBQUtELE1BQUssUUFBUSxrQ0FBVyxLQUFLO0FBQUEsUUFDbEMsS0FBS0EsTUFBSyxRQUFRLGtDQUFXLE9BQU87QUFBQSxRQUNwQyxLQUFLQSxNQUFLLFFBQVEsa0NBQVcsYUFBYTtBQUFBLFFBQzFDLEtBQUtBLE1BQUssUUFBUSxrQ0FBVyxhQUFhO0FBQUEsTUFDNUM7QUFBQSxJQUNGO0FBQUEsSUFDQSxLQUFLO0FBQUEsTUFDSCxxQkFBcUI7QUFBQSxRQUNuQixNQUFNO0FBQUEsVUFDSixLQUFLO0FBQUE7QUFBQSxVQUVMLG1CQUFtQjtBQUFBLFFBQ3JCO0FBQUEsTUFDRjtBQUFBLElBQ0Y7QUFBQSxJQUNBLGNBQWMsRUFBRSxTQUFTLFFBQVE7QUFBQSxFQUNuQyxDQUFDO0FBQ0g7IiwKICAibmFtZXMiOiBbInBhdGgiLCAicHJvY2VzcyIsICJkYXlqcyIsICJwcm9jZXNzIiwgInBhdGgiLCAiZGF5anMiXQp9Cg==
