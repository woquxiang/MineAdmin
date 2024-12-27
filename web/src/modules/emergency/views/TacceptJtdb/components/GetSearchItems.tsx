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

export default function getSearchItems(t: any): MaSearchItem[] {
  return [
                    {
      label: () => '急救编号',
      prop: 'sjbm',
      render: () =><el-input/>,
                      renderProps: {
                        placeholder:'请输入急救编号',
                      },
          },
                                        {
      label: () => '患者姓名',
      prop: 'hzxm',
      render: () => <el-input/>,
      renderProps: {
        placeholder:'患者姓名',
      },
          },
                                                                                                                  ]
}