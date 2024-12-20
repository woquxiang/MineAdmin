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
import type { TacceptJtdbVo } from '~/emergency/api/TacceptJtdb.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/emergency/api/TacceptJtdb.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'
import { ElTag } from 'element-plus'

export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()

  const showBtn = (auth: string | string[], row: TacceptJtdbVo) => {
    return hasAuth(auth)
  }

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index' },
    // 普通列
    //           { label: () => 'ID', prop: 'id' },
                    { label: () => '急救编号', prop: 'sjbm',width:'200px' ,sortable:true},
                    { label: () => '呼救电话', prop: 'hjdh' ,width:'150px'},
                    { label: () => '事发地址', prop: 'xcdz' ,width:'200px'},

    { label: () => '患者', prop: 'huanzhe', children:[
        { label: () => '病情描述', prop: 'zs',width:'200px' },
        { label: () => '姓名', prop: 'hzxm' ,width:'200px'},
        { label: () => '性别', prop: 'xb' ,width:'80px'},
        { label: () => '年龄', prop: 'nl',width:'80px' },
        { label: () => '民族', prop: 'mz',width:'80px' },
        { label: () => '国籍', prop: 'gj',width:'80px' },
      ]},
                     { label: () => '联系人', prop: 'lxr',width:'80px' },
                    { label: () => '患者联系方式', prop: 'lxdh' ,width:'200px'},
                    { label: () => '送往地点', prop: 'swdd',width:'200px' },
                    { label: () => '车牌号码', prop: 'cphm',width:'200px',
                      //用tag组件显示颜色 用 cellrender方法
                      cellRender: ({row}) => {
                        return (
                          <ElTag>{row.cphm}</ElTag>
                        );
                      }
                    },
                    { label: () => '随车电话', prop: 'scdh' ,width:'150px'},
                    { label: () => '司机电话', prop: 'sjhm',width:'150px' },
                    { label: () => '医生电话', prop: 'yshm',width:'150px' },
                    { label: () => '护士电话', prop: 'hshm',width:'150px' },
                    { label: () => '更新时间', prop: 'gxsj',width:'150px' },

    // 操作列
    // {
    //   type: 'operation',
    //   label: () => t('crud.operation'),
    //   width: '260px',
    //   operationConfigure: {
    //     type: 'tile',
    //     actions: [
    //       {
    //         name: 'edit',
    //         icon: 'i-heroicons:pencil',
    //         show: ({ row }) => showBtn('emergency:taccept_jtdb:update', row),
    //         text: () => t('crud.edit'),
    //         onClick: ({ row }) => {
    //           dialog.setTitle(t('crud.edit'))
    //           dialog.open({ formType: 'edit', data: row })
    //         },
    //       },
    //       {
    //         name: 'del',
    //         show: ({ row }) => showBtn('emergency:taccept_jtdb:delete', row),
    //         icon: 'i-heroicons:trash',
    //         text: () => t('crud.delete'),
    //         onClick: ({ row }, proxy: MaProTableExpose) => {
    //           msg.delConfirm(t('crud.delDataMessage')).then(async () => {
    //             const response = await deleteByIds([row.id])
    //             if (response.code === ResultCode.SUCCESS) {
    //               msg.success(t('crud.delSuccess'))
    //               await proxy.refresh()
    //             }
    //           })
    //         },
    //       },
    //     ],
    //   },
    // },
  ]
}
