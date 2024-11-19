<template>
  <el-alert :title="`事故编号 ${data?.accident_number}`" type="success" :closable="false"  />

  <!-- 渲染表格 -->
    <el-table :data="partyData">
      <!-- 动态渲染列 -->
      <el-table-column
        v-for="col in tableColumns"
        :key="col.prop"
        :prop="col.prop"
        :label="col.label"
      />
    </el-table>
</template>

<script setup lang="ts">
import { defineProps, ref } from 'vue'
import { ElMessage } from 'element-plus'
import { ResultCode } from '@/utils/ResultCode.ts'
// import { deletePerson } from '~/test/api/test'  // 这个接口假设是删除人员的接口
import { J123Event } from '~/test/api/test'
import { useTrans } from '@/hooks/auto-imports/useTrans'

const t = useTrans().globalTrans

// 通过 props 接收事故数据
const { data } = defineProps<{
  data: any
}>()

// 事故的相关人员数据
const partyData = ref<any[]>(data?.people || [])

// 假设你从后端接口拿到字段名称和字段备注
const tableColumns = ref([
  // { prop: 'accident_number', label: '受案编号' },
  { prop: 'name', label: '姓名' },
  { prop: 'id_number', label: '身份证' },
  { prop: 'vehicle_type', label: '交通方式' },
  { prop: 'phone', label: '联系方式' },
  { prop: 'car_type', label: '号牌种类' },
  { prop: 'license_plate', label: '号牌号码' },
  { prop: 'insurance_company', label: '保险公司' },
  { prop: 'responsibility', label: '事故责任' }
])
</script>



<style scoped>
/* 你可以根据实际需求定制样式 */
</style>
