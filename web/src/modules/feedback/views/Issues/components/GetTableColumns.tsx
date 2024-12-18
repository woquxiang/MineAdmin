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
import type { IssuesVo } from '~/feedback/api/Issues.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/feedback/api/Issues.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()

  const showBtn = (auth: string | string[], row: IssuesVo) => {
    return hasAuth(auth)
  }

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    // { type: 'index' },
    // 普通列
    { label: () => 'ID', prop: 'id' },
    { label: () => '投诉建议的内容', prop: 'content' },
    { label: () => '联系方式', prop: 'contact_info' },
    { label: () => '提交时间', prop: 'created_at' },

    // 操作列
    {
      type: 'operation',
      label: () => t('crud.operation'),
      width: '150px',
      operationConfigure: {
        type: 'tile',
        actions: [
          {
            name: 'edit',
            icon: 'i-heroicons:pencil',
            // show: ({ row }) => showBtn('feedback:issues:update', row),
            show:()=>false,
            // disabled: ({ row }) => true,
            text: () => t('crud.edit'),
            onClick: ({ row }) => {
              dialog.setTitle(t('crud.edit'))
              dialog.open({ formType: 'edit', data: row })
            },
          },
          {
            name: 'del',
            show: ({ row }) => showBtn('feedback:issues:delete', row),
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
