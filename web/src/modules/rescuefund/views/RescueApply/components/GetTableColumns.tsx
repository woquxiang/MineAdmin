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
import type { RescueApplyVo } from '~/rescuefund/api/RescueApply.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/rescuefund/api/RescueApply.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()

  const showBtn = (auth: string | string[], row: RescueApplyVo) => {
    return hasAuth(auth)
  }

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index' },
    // 普通列
              { label: () => '主键ID', prop: 'id' },
                    { label: () => '关联的申请ID（主表的主键）', prop: 'application_id' },
                    { label: () => '创建时间', prop: 'created_at' },
                    { label: () => '更新时间', prop: 'updated_at' },
                    { label: () => '创建者', prop: 'created_by' },
                    { label: () => '更新者', prop: 'updated_by' },
                    { label: () => '服务站 受理网点', prop: 'acceptPoint' },
                    { label: () => '案发时间 2024-12-20 15', prop: 'accident_date' },
                    { label: () => '事发地点', prop: 'road' },
                    { label: () => '伤者', prop: 'injured' },
                    { label: () => '1伤2亡', prop: 'type' },
                    { label: () => '123', prop: 'reason' },
                    { label: () => '内容', prop: 'desc' },
                    { label: () => '联系人', prop: 'relation_name' },
                    { label: () => '联系电话', prop: 'relation_phone' },
                    { label: () => '日期', prop: 'date' },
          
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
            show: ({ row }) => showBtn('rescuefund:rescue_apply:update', row),
            text: () => t('crud.edit'),
            onClick: ({ row }) => {
              dialog.setTitle(t('crud.edit'))
              dialog.open({ formType: 'edit', data: row })
            },
          },
          {
            name: 'del',
            show: ({ row }) => showBtn('rescuefund:rescue_apply:delete', row),
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
