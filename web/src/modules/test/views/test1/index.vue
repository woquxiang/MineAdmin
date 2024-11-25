<template>
  <xy-wang-editor v-model="editorContent" :height="400" />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import  XyWangEditor from '@/components/xy-wang-editor/index.vue'

const editorContent = ref('') // 用来存储编辑器内容
</script>
