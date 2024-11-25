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
import type { NewsVo } from '~/content/api/News.ts'
import XyWangEditor from "@/components/xy-wang-editor/index.vue";

export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: NewsVo): MaFormItem[] {
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
      label: '标题',
      prop: 'title',
      render: () => <el-input/>,
      itemProps: {
        rules: [{ required: true, message: '标题' }],
      },
                },
                    {
      label: '简短描述',
      prop: 'short_description',
      render: () => <el-input/>,
      itemProps: {
        rules: [{ required: true, message: '简短描述' }],
      },
                },
    {
      label: '详情',
      prop: 'content',
      cols: { md: 24, xs: 24 },
      render: () => XyWangEditor,
      renderProps: {
        height: 350, // 可调整编辑器的高度
      },
      itemProps: {
        rules: [{ required: true, message: '详情', trigger: 'blur' }],
      },
    },
                                                            {
      label: '排序',
      prop: 'sort_order',
                                                              render: () => <el-input/>,
      itemProps: {
        rules: [{ required: true, message: '排序' }],
      },
                },
                    {
      label: '作者',
      prop: 'author',
                      render: () => <el-input/>,
      itemProps: {
        rules: [{ required: true, message: '排序' }],
      },
                },
            ]
}
