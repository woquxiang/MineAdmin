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
import type { RescueFundApplicationsVo } from '~/rescuefund/api/RescueFundApplications.ts'
import MaDictRadio from "@/components/ma-dict-picker/ma-dict-radio.vue";
import MaDictSelect from '@/components/ma-dict-picker/ma-dict-select.vue'


export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: RescueFundApplicationsVo): MaFormItem[] {
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
      label: '申请费用类型',
      prop: 'apply_fee_type',
      render: () => MaDictRadio,
      renderProps: {
        renderMode: 'normal',
        dictName:'rescue-fund-apply_fee_type',
        onChange: (val) => {
          console.log(val)
          if(val == 2010003){
            model.sg_city = '3333'
          }
        }
      },
      itemProps: {
        rules: [{ required: true, message: '申请费用类型' }],
      },
    },
                    {
      label: '事故时间',
      prop: 'sg_time',
                      render: 'DatePicker',
                      renderProps: {
                        type: 'datetime', // 日期时间选择
                        placeholder: '事故时间',
                        format: 'YYYY-MM-DD HH:mm:ss',       // 设置显示格式
                        valueFormat: 'YYYY-MM-DD HH:mm:ss',  // 设置绑定的值格式
                        style: { width: '100%' },
                      },
                      itemProps: {
                        rules: [{ required: true, message: '事故时间' }],
                      },
                },
      {
        label: '事故地点（省）',
        prop: 'sg_prov',
        render: () =><el-input/>,
        renderProps:{
          disabled:formType === 'edit'
        }
      },
                    {
      label: '事故地点（市）',
      prop: 'sg_city',
      render: () => <el-input/>,
      renderProps:{
        disabled:formType === 'edit'
      }
                },
                    {
      label: '事故地点（区/县）',
      prop: 'sg_area',
      render: () => <el-input/>,
      renderProps:{
        disabled:formType === 'edit'
      }
                },
                    {
      label: '事故详细地址',
      prop: 'sg_address',
      render: () => <el-input/>
                },
                    {
      label: '受害人姓名',
      prop: 'shr_name',
      render: () => <el-input/>
                },
                    {
      label: '受害人联系方式',
      prop: 'shr_phone',
      render: () => <el-input/>
                },
                    {
      label: '受害人证件类型',
      prop: 'shr_credentials_type',
      render: () => <el-input/>
                },
                    {
      label: '受害人证件号',
      prop: 'shr_credentials_code',
      render: () => <el-input/>
                },
                    {
      label: '受害人身份证地址',
      prop: 'identity_card_address',
      render: () => <el-input/>
                },
                    {
      label: '受害人现居住地址',
      prop: 'current_residence_address',
      render: () => <el-input/>
                },
                    {
      label: '申请经办人姓名',
      prop: 'sqjbr_name',
      render: () => <el-input/>
                },
                    {
      label: '申请经办人联系方式',
      prop: 'sqjbr_phone',
      render: () => <el-input/>
                },
                    {
      label: '申请经办人证件类型',
      prop: 'sqjbr_credentials_type',
      render: () => MaDictSelect,
      renderProps: {
        renderMode: 'normal',
        dictName:'rescue-fund-credentials_type'
      },
      itemProps: {
        rules: [{ required: true, message: '申请经办人证件类型' }],
      },
      },
                    {
      label: '申请经办人证件号',
      prop: 'sqjbr_credentials_code',
      render: () => <el-input/>
                },
                    {
      label: '与受害人关系',
      prop: 'shr_relationship_type',
      render: () =><el-input/>
                },
                    {
      label: '亲属联系方式',
      prop: 'relatives_phone',
      render: () => <el-input/>
                },
                    {
      label: '是否个人',
      prop: 'is_people',
      render: () => (
        <el-radio-group >
          <el-radio value="0">非个人</el-radio>
          <el-radio value="1">个人</el-radio>
        </el-radio-group>
      ),
      itemProps: {
            rules: [{ required: true, message: '是否个人' }],
      },
                },
                    {
      label: '医疗/殡葬机构',
      prop: 'ent_name',
      render: () => <el-input/>
                },
                    {
      label: '来源渠道',
      prop: 'channel_type',
      render: () => <el-input/>
                },
                                ]
}
