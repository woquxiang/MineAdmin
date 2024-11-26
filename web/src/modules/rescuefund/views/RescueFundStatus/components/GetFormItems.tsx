/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { MaFormItem } from '@mineadmin/form'
import type { RescueFundStatusVo } from '~/rescuefund/api/RescueFundStatus.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: RescueFundStatusVo): MaFormItem[] {
  // 新增默认值
  if (formType === 'add') {
    // todo...
  }

  // 编辑默认值
  if (formType === 'edit') {
    // todo...
  }

  return [
                        {
      label: '申请表ID',
      prop: 'application_id',
                          render: () =><el-input/>,
                },
                    {
      label: '垫付ID',
      prop: 'sqxx_id',
                      render: () =><el-input/>,
                },
                    {
      label: '案件状态',
      prop: 'wechat_sqxx_state',
                      render: () =><el-input/>,
                },
                    {
      label: '审核人用户账号',
      prop: 'approve_user_name',
                      render: () =><el-input/>,
                },
                    {
      label: '支付时间',
      prop: 'give_money_time',
                      render: () =><el-input/>,
                },
                    {
      label: '费用类型',
      prop: 'apply_fee_type',
                      render: () =><el-input/>,
                },
                    {
      label: '垫付金额',
      prop: 'adjustment_money',
                      render: () =><el-input/>,
                },
                    {
      label: '审核状态',
      prop: 'wechat_approve_state',
                      render: () =><el-input/>,
                },
                    {
      label: '退回原因',
      prop: 'return_reason',
                      render: () =><el-input/>,
                }
            ]
}
