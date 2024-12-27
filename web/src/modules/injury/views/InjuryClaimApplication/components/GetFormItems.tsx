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
import type { InjuryClaimApplicationVo } from '~/injury/api/InjuryClaimApplication.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: InjuryClaimApplicationVo): MaFormItem[] {
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
      label: '报警电话',
      prop: 'emergency_phone',
                      render: () => <el-input/>
                },
                    {
      label: '申请时间',
      prop: 'application_date',
                      render: () => <el-input/>
                }
            ]
}
