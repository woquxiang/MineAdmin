<template>
  <div class="p-6 space-y-8">
    <!-- 批量提交按钮 -->
    <el-button
      type="primary"
      @click="handleBatchSubmit"
      :disabled="selectedFiles.length === 0"
      class="mb-6 px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300"
    >
      批量提交
    </el-button>

    <!-- 文件分类展示 -->
    <div v-for="(group, categoryId) in groupedFiles" :key="categoryId" class="space-y-6">
      <h3 class="text-3xl font-semibold text-gray-800">类型：{{ categoryId }}</h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- 文件卡片展示 -->
        <div v-for="(file, index) in group" :key="file.id" class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="space-y-3">
            <!-- 文件基本信息 -->
            <div>
              <strong class="text-lg text-gray-900">{{ file.file_name }}</strong>
              <p class="text-sm text-gray-500">大小: {{ formatSize(file.file_size) }}</p>
              <p class="text-sm text-gray-500">类型: {{ file.mime_type }}</p>
            </div>

            <!-- 文件预览 -->
            <div class="flex justify-center">
              <button
                class="text-blue-600 text-sm hover:underline"
                @click="handlePreview(file)"
              >
                预览
              </button>
            </div>

            <!-- 文件状态 -->
            <div>
              <span :class="getStatusClass(file.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                {{ file.status }}
              </span>
            </div>

            <!-- 提交按钮 -->
            <button
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300"
              :disabled="file.status !== 'success'"
              @click="handleSubmit(file)"
            >
              提交到第三方
            </button>

            <!-- 文件选择用于批量提交 -->
            <div class="mt-2">
              <input
                type="checkbox"
                :value="file"
                @change="toggleFileSelection(file)"
                class="mr-2"
              />
              选择文件
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 预览弹窗 -->
    <el-dialog :visible.sync="previewVisible" width="60%" title="文件预览">
      <div v-if="previewFile.mime_type.includes('pdf')">
        <embed :src="previewFile.file_path" width="100%" height="500px" type="application/pdf" />
      </div>
      <div v-else>
        <img :src="previewFile.file_path" alt="file preview" class="w-full rounded-lg shadow-md" />
      </div>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import axios from 'axios';

// 定义文件类型
interface FileItem {
  id: string;
  application_id: string;
  attachment_id: string;
  file_id: string;
  file_name: string;
  file_path: string;
  file_type: string;
  file_type_id: string;
  file_size: string;
  uploaded_at: string;
  mime_type: string;
  status: 'success' | 'error' | 'pending' | 'processing'; // 状态
}

// 模拟从后端获取的数据
const rawData = ref({
  "40020088120": [
    {
      "id": "5",
      "application_id": "23",
      "attachment_id": 853,
      "file_id": "",
      "file_name": "SH665kVC340k63783397ea1c38c3b0ea2143ccc5ebd5.jpg",
      "file_path": "/path/to/SH665kVC340k63783397ea1c38c3b0ea2143ccc5ebd5.jpg",
      "file_type": "4002008",
      "file_type_id": "40020088120",
      "file_size": "201642",
      "uploaded_at": "2024-11-18 18:01:15",
      "mime_type": "image/png",
      "status": "success"
    }
  ],
  "40020088110": [
    {
      "id": "4",
      "application_id": "23",
      "attachment_id": 852,
      "file_id": "",
      "file_name": "MDWm4JLnUQl6169ad86ff1e9e24ee6c924e5c41fea25.png",
      "file_path": "/path/to/MDWm4JLnUQl6169ad86ff1e9e24ee6c924e5c41fea25.png",
      "file_type": "4002008",
      "file_type_id": "40020088110",
      "file_size": "2457",
      "uploaded_at": "2024-11-18 17:38:32",
      "mime_type": "image/png",
      "status": "pending"
    }
  ],
  "40020088100": [
    {
      "id": "3",
      "application_id": "23",
      "attachment_id": 850,
      "file_id": "",
      "file_name": "78SB7MVCUvrd4a1d98c95fb45f70d017a8376e0acf3b.png",
      "file_path": "/path/to/78SB7MVCUvrd4a1d98c95fb45f70d017a8376e0acf3b.png",
      "file_type": "4002008",
      "file_type_id": "40020088100",
      "file_size": "21209",
      "uploaded_at": "2024-11-18 17:28:13",
      "mime_type": "image/jpeg",
      "status": "processing"
    }
  ]
});

// 选中的文件
const selectedFiles = ref<FileItem[]>([]);

// 格式化文件大小
const formatSize = (size: string): string => {
  const numSize = parseInt(size, 10);
  if (numSize < 1024) return `${numSize} B`;
  else if (numSize < 1048576) return `${(numSize / 1024).toFixed(2)} KB`;
  else return `${(numSize / 1048576).toFixed(2)} MB`;
};

// 获取文件状态对应的类名
const getStatusClass = (status: 'success' | 'error' | 'pending' | 'processing'): string => {
  switch (status) {
    case 'success':
      return 'bg-green-100 text-green-600';
    case 'error':
      return 'bg-red-100 text-red-600';
    case 'pending':
      return 'bg-yellow-100 text-yellow-600';
    case 'processing':
      return 'bg-blue-100 text-blue-600';
    default:
      return 'bg-gray-100 text-gray-600';
  }
};

// 将文件按类别分组
const groupedFiles = computed(() => {
  const grouped: Record<string, FileItem[]> = {};
  Object.keys(rawData.value).forEach(key => {
    grouped[key] = rawData.value[key];
  });
  return grouped;
});

// 预览弹窗控制
const previewVisible = ref(false);
const previewFile = ref<FileItem | null>(null);

// 预览文件
const handlePreview = (file: FileItem) => {
  previewFile.value = file;
  previewVisible.value = true;
};

// 单个文件提交
const handleSubmit = async (file: FileItem) => {
  try {
    // 模拟请求
    const response = await axios.post('https://third-party-api.com/upload', { file });
    if (response.status === 200) {
      file.status = 'success';
    }
  } catch (error) {
    file.status = 'error';
  }
};

// 批量提交
const handleBatchSubmit = async () => {
  if (selectedFiles.value.length > 0) {
    try {
      const response = await axios.post('https://third-party-api.com/batch-upload', { files: selectedFiles.value });
      if (response.status === 200) {
        selectedFiles.value.forEach(file => file.status = 'success');
      }
    } catch (error) {
      selectedFiles.value.forEach(file => file.status = 'error');
    }
  }
};

// 选择文件用于批量提交
const toggleFileSelection = (file: FileItem) => {
  if (selectedFiles.value.includes(file)) {
    selectedFiles.value = selectedFiles.value.filter(f => f !== file);
  } else {
    selectedFiles.value.push(file);
  }
};
</script>

<style scoped>
/* Tailwind CSS 已经自动处理样式 */
</style>
