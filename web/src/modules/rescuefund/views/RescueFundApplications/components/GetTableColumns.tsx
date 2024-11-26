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
import type { RescueFundApplicationsVo } from '~/rescuefund/api/RescueFundApplications.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { useMessage } from '@/hooks/useMessage.ts'
import { deleteByIds } from '~/rescuefund/api/RescueFundApplications.ts'
import { ResultCode } from '@/utils/ResultCode.ts'
import hasAuth from '@/utils/permission/hasAuth.ts'
import {Dictionary} from "#/global";
import {ElTag} from "element-plus";


export default function getTableColumns(dialog: UseDialogExpose, formRef: any, t: any): MaProTableColumns[] {
  const dictStore = useDictStore()
  const msg = useMessage()

  const router = useRouter()

  const showBtn = (auth: string | string[], row: RescueFundApplicationsVo) => {
    return hasAuth(auth)
  }

  const dictionaryData = computed<Dictionary[] | null>(() => {
    return dictName === '' ? data : dictStore.find(dictName)
  })

  // 通用的字典映射方法
  const getLabelFromDict = (dictName: string, code: string): string => {
    const dict =dictStore.find(dictName) || []
    if (!dict.length) {
      console.error(`字典 "${dictName}" 不存在或没有数据！`)  // 如果字典为空或不存在，输出错误日志
      return code  // 如果字典不存在，直接返回原始代码
    }
    const item = dict.find(item => item.value === code)  // 查找对应的字典项，注意这里使用 value 来匹配
    return item ? item.label : code  // 如果找到标签，返回标签，否则返回原始代码
  }

  return [
    // 多选列
    { type: 'selection', showOverflowTooltip: false, label: () => t('crud.selection') },
    // 索引序号列
    { type: 'index',label:'序号',width:'60px'},
    // 普通列
    { label: () => '主键ID', prop: 'id',width:'100px'},
    { label: () => '费用类型', prop: 'apply_fee_type',width:'100px' ,
                      cellRender: ({ row }) =>{
                        const label =  getLabelFromDict('rescue-fund-apply_fee_type', row.apply_fee_type)
                        return (
                          <ElTag
                            type="primary"
                          >
                            {label}
                          </ElTag>
                          )
                      }
                    },
                    { label: () => '事故时间', prop: 'sg_time' ,width:'100px',sortable:true },
                    { label: () => '事故地址',children:[
                        { label: () => '省', prop: 'sg_prov' ,width:'100px' },
                        { label: () => '市', prop: 'sg_city' ,width:'100px' },
                        { label: () => '区/县', prop: 'sg_area' ,width:'100px' },
                        { label: () => '详细地址', prop: 'sg_address' ,width:'100px' },
                      ] },
                    { label: () => '受害人姓名', prop: 'wounded', children:[
                        { label: () => '姓名', prop: 'shr_name' ,width:'100px' },
                        { label: () => '联系方式', prop: 'shr_phone' ,width:'100px' },
                        { label: () => '证件类型', prop: 'shr_credentials_type' ,width:'100px',
                          cellRender: ({ row }) => getLabelFromDict('rescue-fund-credentials_type', row.shr_credentials_type),  // 简化后的写法，直接返回标签
                        },
                        { label: () => '证件号', prop: 'shr_credentials_code' ,width:'100px' },
                        { label: () => '身份证地址', prop: 'identity_card_address' },
                        { label: () => '现居住地址', prop: 'current_residence_address' },
                     ]},
                    { label: () => '申请经办人', prop: 'sqjbr',children:[
                        { label: () => '姓名', prop: 'sqjbr_name' },
                        { label: () => '联系方式', prop: 'sqjbr_phone' },
                        { label: () => '证件类型', prop: 'sqjbr_credentials_type',
                          cellRender: ({ row }) =>{
                            const label =  getLabelFromDict('rescue-fund-credentials_type', row.sqjbr_credentials_type)
                            return (
                              <ElTag
                                type="primary"
                              >
                                {label}
                              </ElTag>
                            )
                          }
                        },
                        { label: () => '证件号', prop: 'sqjbr_credentials_code' },
                        { label: () => '与受害人关系', prop: 'shr_relationship_type',
                          cellRender: ({ row }) =>{
                            const label =  getLabelFromDict('rescue-fund-shr_relationship_type', row.shr_relationship_type)
                            return (
                              <ElTag
                                type="primary"
                              >
                                {label}
                              </ElTag>
                            )
                          }
                        },
                      ] },
                    { label: () => '亲属联系方式', prop: 'relatives_phone',width:'100px'  },
                    { label: () => '是否个人', prop: 'is_people',width:'100px' ,
                        cellRender:({row})=>{
                          return row.is_people == 1  ? '是':'否'
                        }
                     },
                    { label: () => '医疗/殡葬机构', prop: 'ent_name' ,width:'100px'},
                    { label: () => '来源渠道', prop: 'channel_type',width:'100px'  },
                    { label: () => '创建时间', prop: 'created_at',width:'100px'  },
                    { label: () => '更新时间', prop: 'updated_at',width:'100px'  },

    // 操作列
    {
      type: 'operation',
      label: () => t('crud.operation'),
      width: '200px',
      fixed:"right",
      operationConfigure: {
        type:'tile',
        actions: [
          {
            name: 'view',
            icon: 'i-heroicons:presentation-chart-line',
            // show: ({ row }) => showBtn('rescuefund:rescue_fund_applications:update', row),
            text: () => '审核',
            onClick: ({ row }) => {
              // router.push({ path: '/rescuefund/examine', query: { id: row.id } });
              router.push({
                path: `/rescuefund/examine/${row.id}`})
            },
          },
          {
            name: 'edit',
            icon: 'i-heroicons:pencil',
            show: ({ row }) => showBtn('rescuefund:rescue_fund_applications:update', row),
            text: () => t('crud.edit'),
            onClick: ({ row }) => {
              dialog.setTitle(t('crud.edit'))
              dialog.open({ formType: 'edit', data: row })
            },
          },
          {
            name: 'del',
            show: ({ row }) => showBtn('rescuefund:rescue_fund_applications:delete', row),
            icon: 'i-heroicons:trash',
            text: () => t('crud.delete'),
            onClick: ({ row }, proxy: MaProTableExpose) => {
              msg.delConfirm(t('crud.delDataMessage')).then(async () => {
                const response = await deleteByIds([row.id])
                if (response.code === ResultCode.SUCCESS) {
                  msg.success(t('crud.delSuccess'))
                  await proxy.refresh()
                }
              })
            },
          },
        ],
      },
    },
  ]
}
