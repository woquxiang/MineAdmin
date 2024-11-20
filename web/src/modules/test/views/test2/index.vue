<script setup lang="tsx">
import {onMounted, reactive, ref, inject, h} from 'vue'
import { MaProTableSchema, MaProTableOptions, MaProTableExpose } from '@mineadmin/pro-table'
import {ElMessage} from "element-plus";
import {UserLoginLog} from "~/base/api/log";
import {J123Event} from "~/test/api/test";
import {TransType} from "@/hooks/auto-imports/useTrans";
import useDialog, {UseDialogExpose} from "@/hooks/useDialog";
import {ResultCode} from "@/utils/ResultCode";
import {useMessage} from "@/hooks/useMessage";

import getSearchItems from './data/getSearchItems.tsx'
import getTableColumns from './data/getTableColumns.tsx'


const tableRef = ref<MaProTableExpose>()
const partyRef = ref()

const i18n = useTrans() as TransType
const t = i18n.globalTrans
const msg = useMessage()

import XForm from './form.vue'
import {deleteByIds} from "~/base/api/role";
import Party from "~/test/views/test2/party.vue";
const selections = ref<any[]>([])

const router = useRouter()


const options = reactive<MaProTableOptions>({
  header: {
    mainTitle: '案件管理',
    // subTitle: '',
  },
  requestOptions: {
    api: J123Event.page,
  },
  // 表格参数
  tableOptions: {
    on: {
      // 表格选择事件
      onSelectionChange: (selection: any[]) => selections.value = selection,
      // 监听表格排序事件
      onSortChange: (sortField: string, sortOrder: string) => {
        // 获取当前的搜索表单数据
        const currentSearchForm = tableRef.value.getSearchForm() || {}

        // 更新排序字段和排序方向
        const updatedSearchForm = {
          ...currentSearchForm,  // 合并已有的表单数据
          order_by: sortField.prop,  // 排序字段
          order_by_direction: sortField.order === 'ascending' ? 'asc' : 'desc',  // 排序方向
        }

        tableRef.value.setRequestParams(updatedSearchForm,true)
      },

    },
  },
})
const formRef = ref()

// 弹窗配置
const maDialog: UseDialogExpose = useDialog({
  // 保存数据
  ok: ({ formType }, okLoadingState: (state: boolean) => void) => {
    okLoadingState(true)
    if (['add', 'edit'].includes(formType)) {
      // console.log('当前表单数据:', formRef.value.userModel)  // userModel.value 即为表单的数据
      // return
      // console.log(formRef.value.maForm.model)
      const elForm = formRef.value.maForm.getElFormRef()
      // 验证通过后
      elForm.validate().then(() => {
        switch (formType) {
          // 新增
          case 'add':
            formRef.value.add().then((res: any) => {
              res.code === ResultCode.SUCCESS ? msg.success(t('crud.createSuccess')) : msg.error(res.message)
              maDialog.close()
              tableRef.value.refresh()
            }).catch((err: any) => {
              msg.alertError(err)
            })
            break
          // 修改
          case 'edit':
            formRef.value.edit().then((res: any) => {
              res.code === 200 ? msg.success(t('crud.updateSuccess')) : msg.error(res.message)
              maDialog.close()
              tableRef.value.refresh()
            }).catch((err: any) => {
              msg.alertError(err)
            })
            break
        }
      }).catch()
    }
    else {

      maDialog.close()

      // alert(444)

      // const elForm = setFormRef.value.maForm.getElFormRef()
      // // 验证通过后
      // elForm.validate().then(() => {
      //   // 设置角色
      //   setFormRef.value.saveUserRole().then((res: any) => {
      //     res.code === ResultCode.SUCCESS ? msg.success(t('baseUserManage.setRoleSuccess')) : msg.error(res.message)
      //     maDialog.close()
      //   }).catch((err: any) => {
      //     msg.alertError(err)
      //   })
      // })
    }
    okLoadingState(false)
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
    const response = await J123Event.delete(ids)
    if (response.code === ResultCode.SUCCESS) {
      msg.success(t('crud.delSuccess'))
      tableRef.value.refresh()
    }
  })
}
</script>

<template>

    <div class="pt-3">
      <MaProTable ref="tableRef" :options="options" :schema="schema"
                  @row-drag-sort="(v: any[]) => {
       ElMessage.success('排序完成：' + v)
    }"
      >
        <template #actions>

          <el-button
            type="danger"
            @click="() =>router.push({'path':'/test/second/create'}) "
          >
            新页面
          </el-button>

          <el-button
            v-auth="['permission:role:save']"
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
            v-auth="['permission:role:delete']"
            type="danger"
            plain
            :disabled="selections.length < 1"
            @click="handleDelete"
          >
            {{ t('crud.delete') }}
          </el-button>
        </template>
<!--        <template #toolbar>-->
<!--        </template>-->


      </MaProTable>

      <component :is="maDialog.Dialog">
        <template #default="{ formType, data }">
          <!-- 新增、编辑表单 -->
          <XForm v-if="formType !== 'party'" ref="formRef" :form-type="formType" :data="data" />
          <Party v-else ref="partyRef" :data="data" />

        </template>
      </component>
    </div>


</template>

<style scoped>

</style>
