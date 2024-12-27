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
import type { InjuryClaimApplicationVo } from '~/injury/api/InjuryClaimApplication.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/injury/api/InjuryClaimApplication.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'
import { useRouter } from 'vue-router'

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()
  const router = useRouter()

  const showBtn = (auth: string | string[], row: InjuryClaimApplicationVo) => {
    return hasAuth(auth)
  }

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index' },
    // 普通列
                        { label: () => '直赔编号', prop: 'claim_code' },
                    { label: () => '报警电话', prop: 'emergency_phone' },
                    { label: () => '申请时间', prop: 'application_date' },
                    { label: () => '创建时间', prop: 'created_at' },
                    { label: () => '更新时间', prop: 'updated_at' },

    // 操作列
    {
      type: 'operation',
      label: () => t('crud.operation'),
      width: '260px',
      operationConfigure: {
        type: 'tile',
        actions: [
          {
            name: 'edit',
            icon: 'i-heroicons:pencil',
            show: ({ row }) => showBtn('injury:injury_claim_application:update', row),
            text: () => t('crud.edit'),
            onClick: ({ row }) => {
              // 跳转到编辑页面 跳转到编辑页面 /injury/save/:id 用params传id
              router.push({
                path: '/injury/save/' + row.id,
              })
            },
          },
          {
            name: 'del',
            show: ({ row }) => showBtn('injury:injury_claim_application:delete', row),
            icon: 'i-heroicons:trash',
            text: () => t('crud.delete'),
            onClick: ({ row }, proxy: MaProTableExpose) => {
              msg.delConfirm(t('crud.delDataMessage')).then(async () => {
                const response = await deleteByIds([row.id])
                if (response.code === ResultCode.SUCCESS) {
                  msg.success(t('crud.delSuccess'))
                  await proxy.refresh()
                }
              })
            },
          },
        ],
      },
    },
  ]
}
