<template>
    <ma-form ref="maFormRef" v-model="formModel" :options="options" :items="items" />
</template>
<script setup lang="tsx">
import { ref, reactive, onMounted } from 'vue'
import {ElMessage} from "element-plus";
import type { MaFormExpose } from '@mineadmin/form'
import useForm from '@/hooks/useForm.ts'

const props = defineProps<{
  formType: string,
  data?: any
}>()

const formModel = ref({
  acceptPoint: '',
  accident_date: '',
  road: '',
  injured: '',
  type: '',
  reason: '',
  desc: '',
  relation_name: '',
  relation_phone: '',
  date: '',
})

// 监听props.data的变化，更新表单数据
onMounted(() => {
  if (props.data) {
    Object.keys(props.data).forEach(key => {
      if (key in formModel.value) {
        formModel.value[key] = props.data[key]
      }
    })
  }
})

const maFormRef = ref<MaFormExpose>()

// 使用useForm hook初始化表单
useForm('maFormRef').then((form: MaFormExpose) => {
  form.setItems(items.value)
  form.setOptions(options.value)
})

// 获取表单值的方法
const getElFormRef = () => {
  return {
    getFieldsValue: () => {
      const formatDateTime = (date: any) => {
        if (!date) return ''
        const d = new Date(date)
        const year = d.getFullYear()
        const month = String(d.getMonth() + 1).padStart(2, '0')
        const day = String(d.getDate()).padStart(2, '0')
        const hour = String(d.getHours()).padStart(2, '0')
        return `${year}-${month}-${day} ${hour}:00`
      }

      return {
        ...formModel.value,
        id: props.data?.id,
        accident_date: formatDateTime(formModel.value.accident_date),
        date: formModel.value.date
      }
    },
    validate: () => {
      return new Promise((resolve, reject) => {
        if (maFormRef.value) {
          maFormRef.value.getElFormRef().validate((valid: boolean) => {
            if (valid) {
              resolve(true)
            } else {
              reject(new Error('表单验证失败'))
            }
          })
        } else {
          reject(new Error('表单引用不存在'))
        }
      })
    }
  }
}

defineExpose({
  maForm: {
    getElFormRef
  }
})

const items = ref([
  { label: '受理网点', prop: 'acceptPoint', render: 'input',
    cols: { span: 12 },
    renderProps: { placeholder: '请输入受理网点' },
    itemProps: { rules: [{ required: true, message: '请输入受理网点' }] }
  },
  { label: '案发时间',
    prop: 'accident_date',
    cols: { span: 12 },
    itemProps: { rules: [{ required: true, message: '请选择案发时间' }] },
    render: ({ formData }) => {
      return (
        <el-date-picker
          v-model={formModel.accident_date}
          type="datetime"
          placeholder="请选择案发时间"
          format="YYYY-MM-DD HH:00"
          value-format="YYYY-MM-DD HH:00"
        />
      )
    }
  },
  { label: '案发地点',
    prop: 'road', 
    render: 'input', 
    cols: { span: 12 },
    renderProps: { placeholder: '请输入案发地点' },
    itemProps: { rules: [{ required: true, message: '请输入案发地点' }] }
  },
  { label: '伤者',
   prop: 'injured', 
   render: 'input', 
   cols: { span: 12 },
   renderProps: { placeholder: '请输入伤者' },
   itemProps: { rules: [{ required: true, message: '请输入伤者' }] }
  },
  { label: '事故类型',
   prop: 'type', 
   cols: { span: 12 },
   itemProps: { rules: [{ required: true, message: '请选择事故类型' }] },
   render: ({ formData }) => {
      return (
        <el-select v-model={formModel.type} placeholder="请选择事故类型">
          {[{ label: '伤', value: '1' }, { label: '亡', value: '2' }].map(item => {
            return <el-option label={item.label} value={item.value}></el-option>
          })}
        </el-select>
      )
    }
  },
  { label: '垫付原因',
   prop: 'reason', 
   cols: { span: 12 },
   itemProps: { rules: [{ required: true, message: '请选择垫付原因' }] },
   render: ({formData}) => {
    return (
      <el-select v-model={formModel.reason} placeholder="请选择垫付原因">
        {[{ label: '抢救费用超过交强险责任限额的', value: '1' }, { label: '肇事机动车未参加交强险的', value: '2' },{ label: '机动车肇事后逃逸的', value: '3' }].map(item => {
          return <el-option label={item.label} value={item.value}></el-option>
        })}
      </el-select>
    )
   }  
  },
  { label: '案件内容',
   prop: 'desc', 
   render: 'input', 
   renderProps: { type: 'textarea', placeholder: '请输入案件内容' }, 
   cols: { span: 24 },
   itemProps: { rules: [{ required: true, message: '请输入案件内容' }] }
  },
  { label: '联系人',
   prop: 'relation_name', 
   render: 'input', 
   cols: { span: 12 },
   renderProps: { placeholder: '请输入联系人' },
   itemProps: { rules: [{ required: true, message: '请输入联系人' }] }
  },
  { label: '联系电话',
   prop: 'relation_phone', 
   render: 'input', 
   cols: { span: 12 },
   renderProps: { placeholder: '请输入联系电话' },
   itemProps: { rules: [{ required: true, message: '请输入联系电话' }] }
  },
  { label: '日期',
   prop: 'date', 
   cols: { span: 12 },
   renderProps: { placeholder: '请选择日期' },
   render: ({ formData }) => {
      return (
        <el-date-picker
          v-model={formModel.date}
          type="date"
          placeholder="请选择日期"
          format="YYYY-MM-DD"
          value-format="YYYY-MM-DD"
        />
      )
    },
   itemProps: { rules: [{ required: true, message: '请选择日期' }] }
  }
])

const options = ref({
  title: '垫付申请单',
  width: '800px',
  height: '600px',
  top: '100px',
  left: '100px',    
  labelWidth: '100px',
  labelAlign: 'left',
})
</script>