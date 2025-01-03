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
import type { RescueApplyVo } from '~/rescuefund/api/RescueApply.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: RescueApplyVo): MaFormItem[] {
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
      label: '服务站 受理网点',
      prop: 'acceptPoint',
      render: () => <el-input/>,
                },
                    {
      label: '案发时间 2024-12-20 15',
      prop: 'accident_date',
      render: () => <el-date-picker />,
                },
                    {
      label: '事发地点',
      prop: 'road',
      render: () => <el-input/>,
                },
                    {
      label: '伤者',
      prop: 'injured',
      render: () => <el-input/>,
                },
                    {
      label: '1伤2亡',
      prop: 'type',
      render: () => <el-input/>,
                },
                    {
      label: '123',
      prop: 'reason',
      render: () => <el-input/>,
                },
                    {
      label: '内容',
      prop: 'desc',
      render: () => <el-input/>,
                },
                    {
      label: '联系人',
      prop: 'relation_name',
      render: () => <el-input/>,
                },
                    {
      label: '联系电话',
      prop: 'relation_phone',
      render: () => <el-input/>,
                },
                    {
      label: '日期',
      prop: 'date',
      render: () => <el-input/>,
                },
            ]
}
