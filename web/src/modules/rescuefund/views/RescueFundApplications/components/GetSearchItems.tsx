/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo <root@imoi.cn>
 * @Link   https://github.com/mineadmin
*/

import type { MaSearchItem } from '@mineadmin/search'
import MaDictRadio from "@/components/ma-dict-picker/ma-dict-radio.vue";
import MaDictSelect from '@/components/ma-dict-picker/ma-dict-select.vue'


export default function getSearchItems(t: any): MaSearchItem[] {

  // const is_approved_options = [
  //   { label: '未审核', value: 0 },
  //   { label: '已审核', value: 1 },
  //   { label: '驳回', value: 2 },
  // ];


  return [
    {
      label: () => '审核状态',
      prop: 'is_approved',
      render: () => (
        <el-select clearable>
          <el-option label="未审核" value="0" />
          <el-option label="已审核" value="1" />
          <el-option label="已驳回" value="2" />
        </el-select>
      ),
      renderProps: {
        // 如果需要其他配置，可以添加在这里
      },
    },
    {
      label: () => '申请费用类型',
      prop: 'apply_fee_type',
      render: () => MaDictSelect,
      renderProps: {
        renderMode: 'normal',
        dictName:'rescue-fund-apply_fee_type',
        clearable:true,
      },
    },
    {
      label: '受害人姓名',
      prop: 'shr_name',
      render: 'input',
      renderProps: {
        placeholder:'请输入姓名',
      },
    },
    {
      label: '受害人证件号',
      prop: 'shr_credentials_code',
      render: 'input',
      renderProps: {
        placeholder:'请输入证件号',
      },
    },
    {
      label: '受害人手机号',
      prop: 'shr_phone',
      render: 'input',
      renderProps: {
        placeholder:'请输入手机号',
      },
    },
  ]
}
