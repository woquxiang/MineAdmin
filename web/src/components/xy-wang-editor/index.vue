<script setup lang="ts">
import '@wangeditor/editor/dist/css/style.css'
import { ref, onBeforeUnmount, shallowRef, defineProps, defineEmits, computed } from 'vue'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue'

defineOptions({ name: 'XyWangEditor' })

// Props 定义
const props = defineProps({
  modelValue: { type: String, default: '' }, // 通过 v-model 传递内容
  height: { type: Number, default: 300 }, // 编辑器高度
})

const emit = defineEmits(['update:modelValue', 'change']) // 定义 emit

// Editor 内容
const content = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value)
    emit('change', value)
  },
})

// WangEditor 引用
const editorRef = shallowRef(null) // 使用 shallowRef 替代 ref，避免深度响应

// 工具栏配置
const toolbarConfig = {
  excludeKeys: ["insertImage", "insertVideo"],
}

// 编辑器配置
const editorConfig = {
  placeholder: '请输入内容...',
  MENU_CONF: {
    uploadImage: {
      show: false, // 禁用图片上传按钮
      server: 'http://127.0.0.1:9501/v1/front-machine/i4', // 配置图片上传接口
      fieldName: 'file', // 接口接收图片的字段名
      headers: {
        Authorization: 'Bearer your-token', // 如果需要携带认证信息
      },
      maxFileSize: 2 * 1024 * 1024, // 限制文件大小 (2 MB)
      allowedFileTypes: ['image/*'], // 限制文件类型
      meta: { source: 'editor' }, // 自定义元数据，传给后端
      metaWithUrl: false,
      timeout: 3000, // 超时时间
      withCredentials: false, // 是否携带跨域凭证
    },
  },
}

// 编辑器创建回调
const handleCreated = (editor) => {
  editorRef.value = editor
  // 编辑器创建成功后，手动设置内容
  if (editorRef.value && content.value) {
    editorRef.value.txt.html(content.value) // 设置编辑器内容
  }
}

// 销毁编辑器
onBeforeUnmount(() => {
  if (editorRef.value) {
    editorRef.value.destroy()
  }
})

const logContent = () => {
  // 确保 editorRef 已经创建
  if (editorRef.value) {
    // 获取当前编辑器内容
    console.log('当前内容:', content.value)
  } else {
    console.error('编辑器未创建，无法获取内容')
  }
}

</script>

<template>
  <div style="border: 1px solid #ccc; width: 100%; z-index: 100;">

    <Toolbar :editor="editorRef" :defaultConfig="toolbarConfig" style="border-bottom: 1px solid #ccc;" />
    <Editor
      :style="{ height: props.height + 'px', overflowY: 'hidden' }"
      v-model="content"
      :defaultConfig="editorConfig"
      @onCreated="handleCreated"
    />
  </div>
</template>


<style scoped>

</style>
