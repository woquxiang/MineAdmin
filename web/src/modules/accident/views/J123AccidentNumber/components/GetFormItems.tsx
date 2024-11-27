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
import type { J123AccidentNumberVo } from '~/accident/api/J123AccidentNumber.ts'

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: J123AccidentNumberVo): MaFormItem[] {
  // 新增默认值
  if (formType === 'add') {
    // todo...
    model.status = '1'
  }

  // 编辑默认值
  if (formType === 'edit') {
    // todo...
  }

  return [
                        {
      label: '案件编号',
      prop: 'accident_number',
                          render: () => <el-input/>,
                          itemProps: {
                            rules: [{ required: true, message: '案件编号' }],
                          },
                          renderProps: {
                            placeholder: '请输入要指定抓取的案件编号',
                          },
                },
                    {
      label: '抓取状态',
      prop: 'status',
      render: 'Switch',
      renderProps: {
        activeText:'开启',
        inactiveText:'关闭',
        activeValue:'1',
        inactiveValue:'0',
      },
        itemProps: {
          rules: [{ required: true, message: '抓取状态' }],
        },
   },
                                                    ]
}
