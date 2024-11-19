/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { MaProTableColumns, MaProTableExpose } from '@mineadmin/pro-table'
import type { RoleVo } from '~/base/api/role.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import {ElMessage, ElTag} from 'element-plus'
import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/base/api/role.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import {UserOperatorLog} from "~/base/api/log";
import {J123Event} from "~/test/api/test";

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()


  return [{
    type: 'selection',
    showOverflowTooltip: false,
  },
    { label: '受案编号', prop: 'accident_number',width:'200px',sortable:true },
    { label: '事故时间', prop: 'event_date',width:'180px',sortable:true},  // 设置默认排序为降序},
    { label: '天气情况', prop: 'weather' },
    { label: '事故地点', prop: 'location',width:'200px' },
    { label: '事故情形', prop: 'accident_scenario' },
    { label: '事故类型', prop: 'accident_type' },
    { label: '数据来源', prop: 'data_source' },
    { label: '处理方式', prop: 'handling_method',
      cellRender: ({ row }) => {
// 点击事件
        const handleClick = () => {
          // 可以在这里执行其他操作，比如调用API或更新状态
          ElMessage.success(`Clicked on status: ${row.handling_method}`);
        };

        // 判断 `handling_method` 是否有值
        if (row.handling_method) {
          return (
            <ElTag
              type="primary"
              onClick={handleClick} // 点击事件
            >
              {row.handling_method}
            </ElTag>
          );
        }

        // 如果没有值，则返回 null 或其他替代内容
        return '';  // 如果没有值，则不渲染任何内容
      },
    },
    { label: '管理部门', prop: 'management_department' },
    { label: '状态', prop: 'accident_status' },
    {
      type: 'operation',
      fixed:"right",
      width:'80px',
      operationConfigure: {
        type: 'dropdown',
        actions: [
          {
            name: 'edit',
            icon: 'material-symbols:person-edit',
            text: () => t('crud.edit'),
            onClick: ({ row }) => {
              dialog.setTitle(t('crud.edit'))
              dialog.open({ formType: 'edit', data: row })
            },
          },
          {
            name: 'setRole',
            // show: ({ row }) => showBtn(['permission:user:getRole', 'permission:user:setRole'], row),
            icon: 'material-symbols:person-add-rounded',
            text: () => '当事人',
            onClick: ({ row }) => {
              dialog.setTitle('当事人列表')
              dialog.open({ formType: 'party', data: row })
              // 设置对话框的确定按钮行为
              dialog.on.ok = () => {
                dialog.close()
              }
            },
          },
          {
            name: 'del',
            icon: 'mdi:delete',
            text: () => t('crud.delete'),
            onClick: ({ row }, proxy: MaProTableExpose) => {
              msg.delConfirm(t('crud.delDataMessage')).then(async () => {
                const response = await J123Event.delete([row.id])
                if (response.code === ResultCode.SUCCESS) {
                  msg.success(t('crud.delSuccess'))
                  proxy.refresh()
                }
              })
            },
          },
        ]
      },
    },
  ]
}
