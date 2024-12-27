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
import type { InjuryPartyInformationVo } from '~/injury/api/InjuryPartyInformation.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: InjuryPartyInformationVo): MaFormItem[] {
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
      label: '赔偿申请ID',
      prop: 'application_id',
                          render: () => <el-input/>
                },
                    {
      label: '姓名',
      prop: 'name',
                      render: () => <el-input/>
                },
                    {
      label: '联系电话',
      prop: 'phone',
                      render: () => <el-input/>
                },
                    {
      label: '性别',
      prop: 'gender',
                      render: () => <el-input/>
                },
                    {
      label: '身份证号码',
      prop: 'id_number',
                      render: () => <el-input/>
                },
                    {
      label: '住址',
      prop: 'address',
                      render: () => <el-input/>
                },
                    {
      label: '交通方式',
      prop: 'transportation_method',
                      render: () => <el-input/>
                },
                    {
      label: '车辆所有人',
      prop: 'vehicle_owner',
                      render: () => <el-input/>
                },
                    {
      label: '车牌号',
      prop: 'license_plate',
                      render: () => <el-input/>
                },
                    {
      label: '车辆所有人住址',
      prop: 'vehicle_owner_address',
                      render: () => <el-input/>
                },
                    {
      label: '被保险人',
      prop: 'insured_person',
                      render: () => <el-input/>
                },
                    {
      label: '保险公司',
      prop: 'insurance_company',
                      render: () => <el-input/>
                },
                    {
      label: '投保险别',
      prop: 'insurance_type',
                      render: () => <el-input/>
                },
                    {
      label: '投保险金额',
      prop: 'insurance_amount',
                      render: () => <el-input/>
                },
                    {
      label: '创建时间',
      prop: 'created_at',
                      render: () => <el-input/>
                },
                    {
      label: '更新时间',
      prop: 'updated_at',
                      render: () => <el-input/>
                },
                    {
      label: '创建者',
      prop: 'created_by',
                      render: () => <el-input/>
                },
                    {
      label: '更新者',
      prop: 'updated_by',
                      render: () => <el-input/>
                },
                    {
      label: '删除时间',
      prop: 'deleted_at',
                      render: () => <el-input/>
                },
            ]
}
