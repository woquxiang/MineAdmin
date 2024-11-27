<!--
 - MineAdmin is committed to providing solutions for quickly building web applications
 - Please view the LICENSE file that was distributed with this source code,
 - For the full copyright and license information.
 - Thank you very much for using MineAdmin.
 -
 - @Author X.Mo<root@imoi.cn>
 - @Link   https://github.com/mineadmin
-->
<script setup lang="tsx">
import type { MaTableExpose } from '@mineadmin/table'
import useTable from '@/hooks/useTable.ts'
import getOnlyWorkAreaHeight from '@/utils/getOnlyWorkAreaHeight.ts'


defineOptions({ name: 'welcome' })
const userinfo = useUserStore().getUserInfo()

useTable('table').then((table: MaTableExpose) => {
  table.setColumns([
    { label: '成员', prop: 'member', width: 150, align: 'center' },
    { label: '动态', prop: 'dynamic', align: 'center' },
    { label: '时间', prop: 'timer', width: 180, align: 'center',
      cellRender: ({ row }) => {
        return useDayjs(row.timer).fromNow()
      },
    },
  ])

  table.setData([
    { member: 'IT界-风清扬', dynamic: '上班不摸鱼，与咸鱼有什么区别。', timer: '2024-09-25 17:10:20' },
    { member: 'Anna', dynamic: '技术部那几位童鞋，再次警告，不要摸鱼，不要摸鱼，不要摸鱼啦！', timer: '2024-09-15 16:34:56' },
    { member: '小李子', dynamic: '中午吃什么嘞，好烦呐！那个谁，来一段唱、跳、rap和篮球吧', timer: '2024-09-09 12:01:10' },
    { member: '咩咩羊', dynamic: '向 MineAdmin 提交了一个bug，抽时间看看吧！', timer: '2024-09-05 14:20:07' },
    { member: '李大', dynamic: '刚才把工作台页面随便写了一些，凑合能看了！', timer: '2024-09-03 10:43:36' },
  ])
})
</script>

<template>


</template>

<style lang="scss" scoped>
.run-list {
  @apply b-1 b-solid b-gray-1 dark-b-dark-3 p-3 b-l-0 b-t-0 b-r-0 lg:b-r-1
  transition-all duration-300
  hover-shadow dark-hover-shadow-dark-3
  ;

  .desc {
    @apply mt-3 text-sm leading-6 dark-text-[#888] text-gray-5
  }
}

.ma-link li {
  @apply flex items-center py-1.5;
}
</style>
