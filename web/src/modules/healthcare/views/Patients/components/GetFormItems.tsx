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
import type { PatientsVo } from '~/healthcare/api/Patients.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: PatientsVo): MaFormItem[] {
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
      label: '患者姓名',
      prop: 'name',
                          render: () => <el-input/>
                },
                    {
      label: '患者性别',
      prop: 'sex',
                      render: () => <el-input/>
                },
                    {
      label: '患者身份证号',
      prop: 'id_card',
                      render: () => <el-input/>
                },
                    {
      label: '患者出生日期',
      prop: 'birthday',
                      render: () => <el-input/>
                },
                    {
      label: '患者联系电话',
      prop: 'phone',
                      render: () => <el-input/>
                },
            ]
}
