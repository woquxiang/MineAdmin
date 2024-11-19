<template>
  <div class="mine-layout p-6 space-y-8">

    <!-- 批量提交按钮 -->
<!--    <el-button-->
<!--      type="primary"-->
<!--      @click="handleBatchSubmit"-->
<!--      :disabled="selectedFiles.length === 0"-->
<!--    >-->
<!--      批量提交-->
<!--    </el-button>-->

    <!-- 文件分类展示 -->

    <template v-if="Object.keys(groupedFiles).length > 0">
      <div v-for="(group, categoryId) in groupedFiles" :key="categoryId" class="space-y-6">
        <h6 class="text-2xl">{{getDictLabel('rescue-fund-file_type',categoryId)}}</h6>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

          <div v-for="(file, index) in group" :key="file.id" class="transition-shadow duration-300">
            <el-card
              class="file-card  rounded-lg shadow-lg"
              shadow="hover"
            >
              <div class="space-y-3">
                <!-- 文件基本信息 -->
                <div>
                  <el-tooltip
                    class="box-item"
                    effect="dark"
                    :content="file.file_name"
                    placement="top-start"
                  >
                    <span class="text-lg block w-full overflow-hidden text-ellipsis whitespace-nowrap">
                      {{ file.file_name }}
                    </span>
                  </el-tooltip>
                  <p class="text-sm text-gray-500">大小: {{ formatSize(file.file_size) }}</p>
                  <p class="text-sm text-gray-500">类型: {{ file.mime_type }}</p>
                </div>

                <!-- 文件状态 -->
                <div v-if="file.is_returned === 1">
                  <el-tooltip
                    effect="dark"
                    :content="file.file_id"
                    placement="top-start"
                  >
                    <el-tag type="success" class="mr-2">已回传</el-tag>
                  </el-tooltip>
                </div>
                <el-tag type="error" v-else>未回传</el-tag>

                <div class="flex justify-evenly items-center space-x-4">
                  <!-- 预览按钮 -->
                  <el-button type="success" @click="handlePreview(file)">
                    预览
                  </el-button>
                  <!-- 下载按钮 -->
                  <el-button type="info" @click="handleDownload(file)">
                    下载
                  </el-button>
                  <!-- 提交按钮 -->
                  <el-button type="primary" @click="handleSubmit(file)">提交</el-button>
                </div>

                <!-- 文件选择用于批量提交 -->
                <el-checkbox-group v-model="selectedFiles" class="mt-2" v-if="file.is_returned !== 1">
                  <el-checkbox :label="file">
                    选择文件
                  </el-checkbox>
                </el-checkbox-group>
              </div>
            </el-card>
          </div>

<!--          &lt;!&ndash; 文件卡片展示 &ndash;&gt;-->
<!--          <div v-for="(file, index) in group" :key="file.id" class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">-->
<!--            <div class="space-y-3">-->
<!--              &lt;!&ndash; 文件基本信息 &ndash;&gt;-->
<!--              <div>-->
<!--                <el-tooltip-->
<!--                  class="box-item"-->
<!--                  effect="dark"-->
<!--                  :content="file.file_name"-->
<!--                  placement="top-start"-->
<!--                >-->
<!--                  <strong class="text-lg text-gray-900 block w-full overflow-hidden text-ellipsis whitespace-nowrap">-->
<!--                    {{ file.file_name }}-->
<!--                  </strong>-->
<!--                </el-tooltip>-->
<!--                <p class="text-sm text-gray-500">大小: {{ formatSize(file.file_size) }}</p>-->
<!--                <p class="text-sm text-gray-500">类型: {{ file.mime_type }}</p>-->
<!--              </div>-->

<!--              &lt;!&ndash; 文件状态 &ndash;&gt;-->

<!--              <div v-if="file.is_returned === 1">-->
<!--                <el-tooltip-->
<!--                  effect="dark"-->
<!--                  :content="file.file_id"-->
<!--                  placement="top-start"-->
<!--                >-->
<!--                  <el-tag type="success"  class="mr-2">已回传</el-tag>-->
<!--                </el-tooltip>-->
<!--              </div>-->
<!--              <el-tag type="error" v-else>未回传</el-tag>-->

<!--              <div class="flex justify-between items-center space-x-4">-->
<!--                &lt;!&ndash; 预览按钮 &ndash;&gt;-->
<!--                <el-button type="success" @click="handlePreview(file)">-->
<!--                  预览-->
<!--                </el-button>-->
<!--                &lt;!&ndash; 下载按钮 &ndash;&gt;-->
<!--                <el-button type="info" @click="handleDownload(file)">-->
<!--                  下载-->
<!--                </el-button>-->
<!--                &lt;!&ndash; 提交按钮 &ndash;&gt;-->
<!--                <el-button type="primary" @click="handleSubmit(file)">提交</el-button>-->
<!--              </div>-->

<!--              &lt;!&ndash; 文件选择用于批量提交 &ndash;&gt;-->
<!--              <el-checkbox-group v-model="selectedFiles" class="mt-2" v-if="file.is_returned !== 1">-->
<!--                <el-checkbox :label="file">-->
<!--                  选择文件-->
<!--                </el-checkbox>-->
<!--              </el-checkbox-group>-->
<!--            </div>-->
<!--          </div>-->


        </div>
      </div>
    </template>
    <el-empty description="还没有上传文件" v-else />

    <!-- 预览弹窗 -->
    <el-dialog v-model="previewVisible" width="60%" title="文件预览" :modal="false">
      <div v-if="previewFile.mime_type.includes('pdf')">
        <embed :src="previewFile.local_url" width="100%" height="500px" type="application/pdf" />
      </div>
      <div v-else>
        <img :src="previewFile.local_url" alt="file preview" class="rounded-lg shadow-md" />
      </div>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import {sync_file, view_files} from "~/rescuefund/api/RescueFundApplications";
import { saveAs } from 'file-saver';
import axios from "axios";
import {useMessage} from "@/hooks/useMessage";

const dictStore = useDictStore();
const msg = useMessage()


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
  local_url: string;
  is_returned: number;
}

// 传递进来的 applicationId
const props = defineProps<{
  applicationId: string; // 父组件传递的 applicationId
}>();

// 文件数据和选中的文件
const filesData = ref<Record<string, FileItem[]>>({});
const selectedFiles = ref<FileItem[]>([]);

// 格式化文件大小
const formatSize = (size: string): string => {
  const numSize = parseInt(size, 10);
  if (numSize < 1024) return `${numSize} B`;
  else if (numSize < 1048576) return `${(numSize / 1024).toFixed(2)} KB`;
  else return `${(numSize / 1048576).toFixed(2)} MB`;
};

// 将文件按类别分组
const groupedFiles = computed(() => {
  const grouped: Record<string, FileItem[]> = {};
  Object.keys(filesData.value).forEach(key => {
    grouped[key] = filesData.value[key];
  });
  return grouped;
});

// 预览弹窗控制
const previewVisible = ref(false);
// 全选状态
const selectAll = ref(false);
const previewFile = ref<FileItem | null>(null);

// 预览文件
const handlePreview = (file: FileItem) => {
  previewFile.value = file;
  previewVisible.value = true;
};

const handleDownload = async (file: FileItem) => {
  try {
    if (!file.local_url) {
      msg.error('文件路径无效');
      return
    }

    const response = await axios.get(file.local_url, {
      responseType: 'blob',
    });

    saveAs(response.data, file.file_name); // 使用 file-saver 的 saveAs 方法
  } catch (error) {
    msg.error('下载失败，请稍后再试');
  }
};

// 单个文件提交
const handleSubmit = async (file: FileItem) => {
  try {
    // 模拟请求
    const response = await sync_file({id:file.id});
    if (response.code === 200 && response.data) {
      file.is_returned = 1;
      file.file_id = response.data.file_id
      msg.success('上传成功');
    }
  } catch (error) {
    file.is_returned = 0;
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

// 根据 applicationId 获取文件数据
const fetchFilesData = async (applicationId: string) => {
  try {
    const response = await view_files(applicationId);
    if (response.code === 200 && response.data) {
      filesData.value = response.data;
    } else {
      console.error('无法获取文件数据');
    }
  } catch (error) {
    console.error('请求失败，请稍后再试');
  }
};

// 组件挂载时获取文件数据
onMounted(() => {
  fetchFilesData(props.applicationId);
});

// 监听 applicationId 变化，重新获取数据
watch(() => props.applicationId, (newId) => {
  fetchFilesData(newId);
});

// 通用的获取字典标签的函数
const getDictLabel = (dictName: string, value: any, attrName: string = 'label'): string => {
  const label = dictStore.t(dictName, value, attrName);
  return label || '';
};

</script>

<style scoped>
/* Tailwind CSS 已经自动处理样式 */
</style>
