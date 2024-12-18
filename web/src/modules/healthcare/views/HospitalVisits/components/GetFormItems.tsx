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
import type { HospitalVisitsVo } from '~/healthcare/api/HospitalVisits.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: HospitalVisitsVo): MaFormItem[] {
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
      label: '就诊类型',
      prop: 'visits_type',
                          render: () => <el-input/>
                },
                    {
      label: '事故ID（可能为空）',
      prop: 'accident_id',
                      render: () => <el-input/>
                },
                    {
      label: '患者ID（可能为空）',
      prop: 'patient_id',
                      render: () => <el-input/>
                },
                    {
      label: '医院ID',
      prop: 'hospital_id',
                      render: () => <el-input/>
                },
                    {
      label: '诊断信息',
      prop: 'diagnosis',
                      render: () => <el-input/>
                },
                    {
      label: '科室',
      prop: 'section',
                      render: () => <el-input/>
                },
                    {
      label: '病区',
      prop: 'sickarea',
                      render: () => <el-input/>
                },
                    {
      label: '床位号',
      prop: 'bedno',
                      render: () => <el-input/>
                },
                    {
      label: '主治医生',
      prop: 'doctor',
                      render: () => <el-input/>
                },
                    {
      label: '就诊时间',
      prop: 'i_time',
                      render: () => <el-input/>
                },
                    {
      label: '出院时间',
      prop: 'o_time',
                      render: () => <el-input/>
                },
            ]
}
