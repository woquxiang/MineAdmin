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
import type { InjurySignatureVo } from '~/injury/api/InjurySignature.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: InjurySignatureVo): MaFormItem[] {
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
      label: '直赔ID',
      prop: 'direct_compensation_id',
                      render: () => <el-input/>
                },
                    {
      label: '姓名',
      prop: 'name',
                      render: () => <el-input/>
                },
                    {
      label: '签名数据（Base64编码）',
      prop: 'signature_data',
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
