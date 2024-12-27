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
import type { InjuryPartyInformationVo } from '~/injury/api/InjuryPartyInformation.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/injury/api/InjuryPartyInformation.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()

  const showBtn = (auth: string | string[], row: InjuryPartyInformationVo) => {
    return hasAuth(auth)
  }

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index' },
    // 普通列
                        { label: () => '赔偿申请ID', prop: 'application_id' },
                    { label: () => '姓名', prop: 'name' },
                    { label: () => '联系电话', prop: 'phone' },
                    { label: () => '性别', prop: 'gender' },
                    { label: () => '身份证号码', prop: 'id_number' },
                    { label: () => '住址', prop: 'address' },
                    { label: () => '交通方式', prop: 'transportation_method' },
                    { label: () => '车辆所有人', prop: 'vehicle_owner' },
                    { label: () => '车牌号', prop: 'license_plate' },
                    { label: () => '车辆所有人住址', prop: 'vehicle_owner_address' },
                    { label: () => '被保险人', prop: 'insured_person' },
                    { label: () => '保险公司', prop: 'insurance_company' },
                    { label: () => '投保险别', prop: 'insurance_type' },
                    { label: () => '投保险金额', prop: 'insurance_amount' },
                    { label: () => '创建时间', prop: 'created_at' },
                    { label: () => '更新时间', prop: 'updated_at' },
                    { label: () => '创建者', prop: 'created_by' },
                    { label: () => '更新者', prop: 'updated_by' },
                    { label: () => '删除时间', prop: 'deleted_at' },
          
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
            show: ({ row }) => showBtn('injury:injury_party_information:update', row),
            text: () => t('crud.edit'),
            onClick: ({ row }) => {
              dialog.setTitle(t('crud.edit'))
              dialog.open({ formType: 'edit', data: row })
            },
          },
          {
            name: 'del',
            show: ({ row }) => showBtn('injury:injury_party_information:delete', row),
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
