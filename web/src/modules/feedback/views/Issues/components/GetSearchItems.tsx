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
      label: () => '投诉建议的内容',
      prop: 'content',
                      render: () => <el-input/>,
          },
                {
      label: () => '联系方式',
      prop: 'contact_info',
                  render: () => <el-input/>,
          },
                                          ]
}
