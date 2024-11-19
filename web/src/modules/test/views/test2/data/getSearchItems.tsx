/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */

import type { MaSearchItem } from '@mineadmin/search'
import MaDictSelect from '@/components/ma-dict-picker/ma-dict-select.vue'

export default function getSearchItems(t: any): MaSearchItem[] {
  return [
    { label: '受案编号', prop: 'accident_number', render: 'input',
      renderProps: {
        placeholder:'请输入受案编号',
      }
    },
    { label: '事故地点', prop: 'location', render: 'input',
      renderProps: {
        placeholder:'请输入事故地点',
      }
    },
    { label: '当事人', prop: 'dsr_name', render: 'input' ,
      renderProps: {
        placeholder:'请输入当事人姓名',
      }
    },
  ]
}
