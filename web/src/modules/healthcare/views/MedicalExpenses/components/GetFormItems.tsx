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
import type { MedicalExpensesVo } from '~/healthcare/api/MedicalExpenses.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: MedicalExpensesVo): MaFormItem[] {
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
      label: '就诊记录ID OR 案件ID',
      prop: 'visit_id',
                          render: () => <el-input/>
                },
                    {
      label: '费用名称（例如：住院费、检查费等）',
      prop: 'expense_name',
                      render: () => <el-input/>
                },
                    {
      label: '费用金额',
      prop: 'price',
                      render: () => <el-input/>
                },
                    {
      label: '服务数量',
      prop: 'quantity',
                      render: () => <el-input/>
                },
            ]
}
