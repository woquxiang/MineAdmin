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
import type { PatientHospitalMappingVo } from '~/healthcare/api/PatientHospitalMapping.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: PatientHospitalMappingVo): MaFormItem[] {
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
      label: '本地患者ID',
      prop: 'patient_id',
                          render: () => <el-input/>
                },
                    {
      label: '医院ID',
      prop: 'hospital_id',
                      render: () => <el-input/>
                },
                    {
      label: '医院患者ID 用来查询医院数据',
      prop: 'hospital_patient_id',
                      render: () => <el-input/>
                },
                    {
      label: '记录创建时间',
      prop: 'created_at',
                      render: () => <el-input/>
                },
                    {
      label: '记录更新时间',
      prop: 'updated_at',
                      render: () => <el-input/>
                },
            ]
}
