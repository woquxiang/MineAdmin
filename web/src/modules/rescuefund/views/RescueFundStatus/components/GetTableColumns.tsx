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
import type { RescueFundStatusVo } from '~/rescuefund/api/RescueFundStatus.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/rescuefund/api/RescueFundStatus.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'
import {ElTag} from "element-plus";

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()

  const router = useRouter()

  const showBtn = (auth: string | string[], row: RescueFundStatusVo) => {
    return hasAuth(auth)
  }

  // 通用的字典映射方法
  const getLabelFromDict = (dictName: string, code: string): string => {
    const dict =dictStore.find(dictName) || []
    if (!dict.length) {
      console.error(`字典 "${dictName}" 不存在或没有数据！`)  // 如果字典为空或不存在，输出错误日志
      return code  // 如果字典不存在，直接返回原始代码
    }
    const item = dict.find(item => item.value === code)  // 查找对应的字典项，注意这里使用 value 来匹配
    return item ? item.label : code  // 如果找到标签，返回标签，否则返回原始代码
  }

  return [
    // 多选列
    // { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index',label:'行号' ,width:'80px' },
    // 普通列
                    { label: () => '申请表ID', prop: 'application_id',width:'100px',
                      cellRender: ({ row }) => {
                        return (
                          <router-link to={`/rescuefund/examine/${row.application_id}`}>
                            <el-link underline={false} className="text-blue-500 font-bold">
                              {row.application_id}
                            </el-link>
                          </router-link>
                        )
                      }
                    },
                    { label: () => '垫付ID', prop: 'sqxx_id' ,width:'200px'},
                    { label: () => '案件状态', prop: 'wechat_sqxx_state',
                      cellRender: ({ row }) =>{
                        const label =  getLabelFromDict('rescue-fund-wechat_sqxx_state_type', row.wechat_sqxx_state)
                        return (
                          <ElTag
                            type="primary"
                          >
                            {label}
                          </ElTag>
                        )
                      }
                    },
                    { label: () => '审核人用户账号', prop: 'approve_user_name' },
                    { label: () => '支付时间', prop: 'give_money_time' },
                    { label: () => '费用类型', prop: 'apply_fee_type' ,
                      cellRender: ({ row }) =>{
                        const label =  getLabelFromDict('rescue-fund-apply_fee_type', row.apply_fee_type)
                        return (
                          <ElTag
                            type="primary"
                          >
                            {label}
                          </ElTag>
                        )
                      }
                    },
                    { label: () => '垫付金额', prop: 'adjustment_money' },
                    { label: () => '审核状态', prop: 'wechat_approve_state',
                      cellRender: ({ row }) =>{
                        const label =  getLabelFromDict('rescue-fund-wechat_approve_state_type', row.wechat_approve_state)
                        return (
                          <ElTag
                            type="primary"
                          >
                            {label}
                          </ElTag>
                        )
                      }
                    },
                    { label: () => '退回原因', prop: 'return_reason' },
    // 操作列
    // {
    //   type: 'operation',
    //   label: () => t('crud.operation'),
    //   width: '260px',
    //   operationConfigure: {
    //     type: 'tile',
    //     actions: [
    //       {
    //         name: 'edit',
    //         icon: 'i-heroicons:pencil',
    //         show: ({ row }) => showBtn('rescuefund:rescue_fund_status:update', row),
    //         text: () => t('crud.edit'),
    //         onClick: ({ row }) => {
    //           dialog.setTitle(t('crud.edit'))
    //           dialog.open({ formType: 'edit', data: row })
    //         },
    //       },
    //       {
    //         name: 'del',
    //         show: ({ row }) => showBtn('rescuefund:rescue_fund_status:delete', row),
    //         icon: 'i-heroicons:trash',
    //         text: () => t('crud.delete'),
    //         onClick: ({ row }, proxy: MaProTableExpose) => {
    //           msg.delConfirm(t('crud.delDataMessage')).then(async () => {
    //             const response = await deleteByIds([row.id])
    //             if (response.code === ResultCode.SUCCESS) {
    //               msg.success(t('crud.delSuccess'))
    //               await proxy.refresh()
    //             }
    //           })
    //         },
    //       },
    //     ],
    //   },
    // },
  ]
}
