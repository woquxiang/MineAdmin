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
import type { MaProTableExpose, MaProTableOptions, MaProTableSchema } from '@mineadmin/pro-table'
import type { Ref } from 'vue'
import type { TransType } from '@/hooks/auto-imports/useTrans.ts'
import type { UseDialogExpose } from '@/hooks/useDialog.ts'

import { deleteByIds, page } from '~/rescuefund/api/RescueFundApplications.ts'
import { create } from '~/rescuefund/api/RescueApply.ts'
import getSearchItems from './components/GetSearchItems.tsx'
import getTableColumns from './components/GetTableColumns.tsx'
import useDialog from '@/hooks/useDialog.ts'
import { useMessage } from '@/hooks/useMessage.ts'
import { ResultCode } from '@/utils/ResultCode.ts'

import Form from './Form.vue'
import {watch} from "vue/dist/vue";
// 引入Aform
import Aform from './examine/Aform.vue'
defineOptions({ name: 'rescuefund:rescue_fund_applications' })

const proTableRef = ref<MaProTableExpose>() as Ref<MaProTableExpose>
const formRef = ref()
const setFormRef = ref()
const selections = ref<any[]>([])
const i18n = useTrans() as TransType
const t = i18n.globalTrans
const local = i18n.localTrans
const msg = useMessage()

// 弹窗配置
const maDialog: UseDialogExpose = useDialog({
  // 保存数据
  ok: ({ formType }, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    console.log(2222222,formType)
    if (['add', 'edit','Aform'].includes(formType)) {
      const elForm = formRef.value.maForm.getElFormRef()
      // 验证通过后
      elForm.validate().then(() => {
        switch (formType) {
          // 新增
          case 'add':
            formRef.value.add().then((res: any) => {
              res.code === ResultCode.SUCCESS ? msg.success(t('crud.createSuccess')) : msg.error(res.message)
              maDialog.close()
              proTableRef.value.refresh()
            }).catch((err: any) => {
              msg.alertError(err)
            })
            break
          // 修改
          case 'edit':
            formRef.value.edit().then((res: any) => {
              res.code === 200 ? msg.success(t('crud.updateSuccess')) : msg.error(res.message)
              maDialog.close()
              proTableRef.value.refresh()
            }).catch((err: any) => {
              msg.alertError(err)
            })
            break
          case 'Aform':
            //获取form数据
            const formData = formRef.value.maForm.getElFormRef().getFieldsValue()
            //去掉id并赋值给application_id
            formData.application_id = formData.id
            delete formData.id
            console.log(111,formData)
            create(formData).then((res: any) => {
              res.code === ResultCode.SUCCESS ? msg.success(t('crud.createSuccess')) : msg.error(res.message)
              maDialog.close()
              const a = document.createElement('a');
              a.href = res.data;  // 服务器返回的文件链接
              a.download = '直赔申请书.pdf';  // 设置下载文件的名称
              a.target = '_blank';  // 在新标签页中打开文件
              a.style.display = 'none';  // 隐藏链接
              document.body.appendChild(a);  // 将链接添加到 DOM 中
              a.click();  // 触发点击事件
              document.body.removeChild(a);  // 下载后移除链接

              proTableRef.value.refresh()
            }).catch((err: any) => {
              msg.alertError(err)
            })
            break
        }
      }).catch()
    }
    okLoadingState(false)
  },
})

// 参数配置
const options = ref<MaProTableOptions>({
  // 表格距离底部的像素偏移适配
  adaptionOffsetBottom: 161,
  header: {
    mainTitle: () => '道路基金',
  },
  // 表格参数
  tableOptions: {
    border:true,
    on: {
      // 表格选择事件
      onSelectionChange: (selection: any[]) => selections.value = selection,
      onSortChange: (sortField: string, sortOrder: string) => {
        // 获取当前的搜索表单数据
        const currentSearchForm = proTableRef.value.getSearchForm() || {}

        // 更新排序字段和排序方向
        const updatedSearchForm = {
          ...currentSearchForm,  // 合并已有的表单数据
          order_by: sortField.prop,  // 排序字段
          order_by_direction: sortField.order === 'ascending' ? 'asc' : 'desc',  // 排序方向
        }

        proTableRef.value.setRequestParams(updatedSearchForm,true)
      },
    },
  },
  // 搜索参数
  searchOptions: {
    foldRows:6,
    fold: true,
    text: {
      searchBtn: () => t('crud.search'),
      resetBtn: () => t('crud.reset'),
      isFoldBtn: () => t('crud.searchFold'),
      notFoldBtn: () => t('crud.searchUnFold'),
    },
    on:{
      reset:()=>{
        console.log(333)
      }
    }
  },
  // 搜索表单参数
  searchFormOptions: { labelWidth: '100px' },
  // 请求配置
  requestOptions: {
    api: page,
  },
})
// 架构配置
const schema = ref<MaProTableSchema>({
  // 搜索项
  searchItems: getSearchItems(t),
  // 表格列
  tableColumns: getTableColumns(maDialog, formRef, t),
})

// 批量删除
function handleDelete() {
  const ids = selections.value.map((item: any) => item.id)
  msg.confirm(t('crud.delMessage')).then(async () => {
    const response = await deleteByIds(ids)
    if (response.code === ResultCode.SUCCESS) {
      msg.success(t('crud.delSuccess'))
      proTableRef.value.refresh()
    }
  })
}
</script>

<template>
  <div class="mine-layout pt-3">
    <MaProTable ref="proTableRef" :options="options" :schema="schema">
      <template #actions>
        <el-button
          v-auth="['rescuefund:rescue_fund_applications:create']"
          type="primary"
          @click="() => {
            maDialog.setTitle(t('crud.add'))
            maDialog.open({ formType: 'add' })
          }"
        >
          {{ t('crud.add') }}
        </el-button>
      </template>

      <template #toolbarLeft>
        <el-button
          v-auth="['rescuefund:rescue_fund_applications:delete']"
          type="danger"
          plain
          :disabled="selections.length < 1"
          @click="handleDelete"
        >
          {{ t('crud.delete') }}
        </el-button>
      </template>
      <!-- 数据为空时 -->
      <template #empty>
        <el-empty>
          <el-button
            v-auth="['rescuefund:rescue_fund_applications:save']"
            type="primary"
            @click="() => {
              maDialog.setTitle(t('crud.add'))
              maDialog.open({ formType: 'add' })
            }"
          >
            {{ t('crud.add') }}
          </el-button>
        </el-empty>
      </template>
    </MaProTable>

    <component :is="maDialog.Dialog">
      <template #default="{ formType, data }">
        <!-- 新增、编辑表单 -->
        <Form v-if="formType !== 'Aform'" ref="formRef" :form-type="formType" :data="data" />
        <Aform v-else ref="formRef" :form-type="formType" :data="data" />
      </template>
    </component>
  </div>
</template>

<style scoped lang="scss">

</style>
