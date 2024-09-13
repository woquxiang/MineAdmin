/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { App } from 'vue'
import type { RouteRecordRaw, Router } from 'vue-router'
import { useProTableRenderPlugin } from '@mineadmin/pro-table'
import type { Plugin } from '#/global'
import router from '@/router'

const pluginConfig: Plugin.PluginConfig = {
  install(app: App) {
  },
  hooks: {
    setup: () => {
      const { addPlugin } = useProTableRenderPlugin()
      // 从components目录加载所有
      const components = import.meta.glob('./components/**/*.vue')
      Object.keys(components).forEach(async (key) => {
        const component: any = await components[key]()
        addPlugin({
          name: component.default.name,
          render: (scope, options: any) => h(component.default, { scope, options }),
        })
      })
    },
    registerRoute: (router: Router, routesRaw): void => {
      router.addRoute({
        name: 'cell-render',
        path: '/cell-render',
        component: () => import('./practice.vue'),
      })
    },
    routerRedirect: (route: RouteRecordRaw) => {
      // 劫持welcome路由返回practice.vue
      if (route.path === '/welcome') {
        router.push('/as-cell-render')
      }
    },
  },
  config: {
    enable: true,
    info: {
      name: 'mine-admin/cell-render',
      version: '1.0.0',
      author: 'amazes',
      description: '表格渲染组件',
    },
  },
}

export default pluginConfig
