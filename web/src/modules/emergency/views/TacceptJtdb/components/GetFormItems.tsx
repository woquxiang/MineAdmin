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
import type { TacceptJtdbVo } from '~/emergency/api/TacceptJtdb.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: TacceptJtdbVo): MaFormItem[] {
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
      label: '急救编号',
      prop: 'sjbm',
      render: () => <el-input/>
                },
                    {
      label: '呼救电话',
      prop: 'hjdh',
      render: () => <el-input/>
                },
                    {
      label: '事发地址',
      prop: 'xcdz',
      render: () => <el-input/>
                },
                    {
      label: '患者病情描述',
      prop: 'zs',
      render: () => <el-input/>
                },
                    {
      label: '患者姓名',
      prop: 'hzxm',
      render: () => <el-input/>
                },
                    {
      label: '患者性别',
      prop: 'xb',
      render: () => <el-input/>
                },
                    {
      label: '患者年龄',
      prop: 'nl',
      render: () => <el-input/>
                },
                    {
      label: '患者民族',
      prop: 'mz',
      render: () => <el-input/>
                },
                    {
      label: '患者国籍',
      prop: 'gj',
      render: () => <el-input/>
                },
                    {
      label: '联系人',
      prop: 'lxr',
      render: () => <el-input/>
                },
                    {
      label: '患者联系方式',
      prop: 'lxdh',
      render: () => <el-input/>
                },
                    {
      label: '送往地点',
      prop: 'swdd',
      render: () => <el-input/>
                },
                    {
      label: '车牌号码',
      prop: 'cphm',
      render: () => <el-input/>
                },
                    {
      label: '随车电话',
      prop: 'scdh',
      render: () => <el-input/>
                },
                    {
      label: '司机电话',
      prop: 'sjhm',
      render: () => <el-input/>
                },
                    {
      label: '医生电话',
      prop: 'yshm',
      render: () => <el-input/>
                },
                    {
      label: '护士电话',
      prop: 'hshm',
      render: () => <el-input/>
                },
                    {
      label: '更新时间',
      prop: 'gxsj',
      render: () => <el-input/>
                },
            ]
}
