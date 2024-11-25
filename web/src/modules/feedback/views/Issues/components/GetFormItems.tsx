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
import type { IssuesVo } from '~/feedback/api/Issues.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: IssuesVo): MaFormItem[] {
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
      label: '投诉建议的内容',
      prop: 'content',
                          render: () => <el-input/>,
                },
                    {
      label: '联系方式',
      prop: 'contact_info',
                      render: () => <el-input/>,
                },
                                                    ]
}
