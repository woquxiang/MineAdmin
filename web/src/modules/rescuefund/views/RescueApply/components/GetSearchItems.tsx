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
      label: () => '主键ID',
      prop: 'id',
      render: () => <el-input/>,
          },
                {
      label: () => '关联的申请ID（主表的主键）',
      prop: 'application_id',
      render: () => <el-input/>,
          },
                {
      label: () => '创建时间',
      prop: 'created_at',
      render: () => <el-input/>,
          },
                {
      label: () => '更新时间',
      prop: 'updated_at',
      render: () => <el-input/>,
          },
                {
      label: () => '创建者',
      prop: 'created_by',
      render: () => <el-input/>,
          },
                {
      label: () => '更新者',
      prop: 'updated_by',
      render: () => <el-input/>,
          },
                {
      label: () => '服务站 受理网点',
      prop: 'acceptPoint',
      render: () => <el-input/>,
          },
                {
      label: () => '案发时间 2024-12-20 15',
      prop: 'accident_date',
      render: () => <el-date-picker />,
          },
                {
      label: () => '事发地点',
      prop: 'road',
      render: () => <el-input/>,
          },
                {
      label: () => '伤者',
      prop: 'injured',
      render: () => <el-input/>,
          },
                {
      label: () => '1伤2亡',
      prop: 'type',
      render: () => <el-input/>,
          },
                {
      label: () => '123',
      prop: 'reason',
      render: () => <el-input/>,
          },
                {
      label: () => '内容',
      prop: 'desc',
      render: () => <el-input/>,
          },
                {
      label: () => '联系人',
      prop: 'relation_name',
      render: () => <el-input/>,
          },
                {
      label: () => '联系电话',
      prop: 'relation_phone',
      render: () => <el-input/>,
          },
                {
      label: () => '日期',
      prop: 'date',
      render: () => <el-input/>,
          },
          ]
}
