/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { MaProTableColumns, MaProTableExpose } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import defaultAvatar from '@/assets/images/defaultAvatar.jpg'
import { ElTag } from 'element-plus'

export default function getUserColumns(tableRef: Ref<MaProTableExpose>, formRef: Ref<any>, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index' },
    // 普通列
    { label: () => t('baseUser.avatar'), prop: 'avatar', width: '120px',
      cellRender: ({ row }) => (
        <div class="flex-center">
          <el-avatar src={(row.avatar === '' || !row.avatar) ? defaultAvatar : row.avatar} alt={row.username} />
        </div>
      ),
    },
    { label: () => t('baseUser.username'), prop: 'username' },
    { label: () => t('baseUser.nickname'), prop: 'nickname' },
    { label: () => t('baseUser.phone'), prop: 'phone' },
    { label: () => t('baseUser.email'), prop: 'email' },
    { label: () => t('baseUser.status'), prop: 'status',
      cellRender: ({ row }) => (
        <ElTag type={dictStore.t('system-status', row.status, 'color')}>
          {t(dictStore.t('system-status', row.status, 'i18n'))}
        </ElTag>
      ),
    },
    // 操作列
    {
      type: 'operation',
      label: () => t('crud.operation'),
      operationConfigure: {
        actions: [
          {
            name: 'edit',
            icon: 'material-symbols:person-edit',
            show: ({ row }) => row.id !== 1 && row.username !== 'SuperAdmin',
            text: () => '编辑',
            onClick: ({ row }) => {
              formRef.value.open(row)
            },
          },
          {
            name: 'del',
            show: ({ row }) => row.id !== 1 && row.username !== 'SuperAdmin',
            icon: 'mdi:delete',
            text: '删除',
          },
          {
            name: 'setRole',
            show: ({ row }) => row.id !== 1 && row.username !== 'SuperAdmin',
            icon: 'material-symbols:person-add-rounded',
            text: '赋予角色',
          },
          {
            name: 'initPassword',
            show: ({ row }) => row.id !== 1 && row.username !== 'SuperAdmin',
            icon: 'material-symbols:passkey',
            text: '初始化密码',
          },
          {
            name: 'noAllowSuperAdmin',
            show: ({ row }) => row.id === 1 && row.username === 'SuperAdmin',
            disabled: () => true,
            text: '超管不可修改',
          },
        ],
      },
    },
  ]
}