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
import type { TrafficIncidentsVo } from '~/healthcare/api/TrafficIncidents.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: TrafficIncidentsVo): MaFormItem[] {
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
      label: '事故ID',
      prop: 'accident_id',
                          render: () => <el-input/>
                },
                    {
      label: '患者ID（可能为空）',
      prop: 'patient_id',
                      render: () => <el-input/>
                },
                    {
      label: '事故发生时间',
      prop: 'accident_time',
                      render: () => <el-input/>
                },
                    {
      label: '事故发生地点',
      prop: 'accident_location',
                      render: () => <el-input/>
                },
                    {
      label: '事故描述',
      prop: 'description',
                      render: () => <el-input/>
                },
                    {
      label: '事故原因',
      prop: 'cause',
                      render: () => <el-input/>
                },
            ]
}
