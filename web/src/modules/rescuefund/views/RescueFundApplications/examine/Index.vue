<template>
  <div class="mine-layout p-5">
    <!-- 骨架屏加载 -->
    <el-skeleton
      v-if="loading"
      :rows="20"
      :loading="loading"
      animated
      class="skeleton-container"
    >
    </el-skeleton>

    <!-- 数据加载完成后显示的实际内容 -->
    <template v-if="application.id">
      <el-alert :closable="false" type="success" class="relative">
        <span class="text-base">申请费用类型：{{getDictLabel('rescue-fund-apply_fee_type', application.apply_fee_type)}}
          <el-link v-if="application.sqxx_id" @click="linkPage"
                class="ml-2 color-red font-bold">申请单号：{{application.sqxx_id}}
          </el-link>
        </span>
        <div  class="absolute right-0 top-1/2 transform -translate-y-1/2">

          <!-- 操作按钮 Section -->
            <template v-if="application.is_approved === 1">
              <el-button type="info"  disabled class="w-32">已审核</el-button>
              <el-button v-if="!application.sqxx_id" @click="applyFunc" type="primary" >提交申请</el-button>
              <el-button v-else @click="applyFunc" type="primary" disabled >已提交</el-button>
            </template>
            <template  v-else-if="application.is_approved === 0">
              <el-button type="primary" @click.once="approveApplication(1)" class="w-32">通过审核</el-button>
              <el-button type="danger" @click.once="approveApplication(2)" class="w-32">驳回</el-button>
            </template>
            <template  v-else-if="application.is_approved === 2">
              <el-button type="warning" class="w-32">已驳回</el-button>
            </template>
        </div >
      </el-alert>

      <el-tabs v-model="activeName" class="demo-tabs" @tab-change="handleClick">
        <el-tab-pane label="基础信息" name="info">
          <!-- 受害人信息 Section -->
          <el-descriptions title="受害人信息" border direction="vertical"  style="margin-top: 20px;">
            <el-descriptions-item label="姓名">{{ application.shr_name }}</el-descriptions-item>
            <el-descriptions-item label="联系方式">{{ application.shr_phone }}</el-descriptions-item>
            <el-descriptions-item label="证件类型">{{ getDictLabel('rescue-fund-credentials_type', application.shr_credentials_type) }}</el-descriptions-item>
            <el-descriptions-item label="证件号">{{ application.shr_credentials_code }}</el-descriptions-item>
            <el-descriptions-item label="身份证地址">{{ application.identity_card_address || 'N/A' }}</el-descriptions-item>
            <el-descriptions-item label="现居住地址">{{ application.current_residence_address || 'N/A' }}</el-descriptions-item>
          </el-descriptions>

          <!-- 事故信息 Section -->
          <el-descriptions title="事故信息" border direction="vertical" style="margin-top: 20px;">
            <el-descriptions-item label="事故时间">{{ application.sg_time }}</el-descriptions-item>
            <el-descriptions-item label="事故地点（省）">{{ application.sg_prov }}</el-descriptions-item>
            <el-descriptions-item label="事故地点（市）">{{ application.sg_city }}</el-descriptions-item>
            <el-descriptions-item label="事故地点（区/县）">{{ application.sg_area }}</el-descriptions-item>
            <el-descriptions-item label="事故详细地址">{{ application.sg_address }}</el-descriptions-item>
          </el-descriptions>

          <!-- 受害人关系和申请信息 Section -->
          <el-descriptions title="受害人关系和申请信息" border direction="vertical" style="margin-top: 20px;">
            <el-descriptions-item label="与受害人关系"> {{getDictLabel('rescue-fund-shr_relationship_type', application.shr_relationship_type)}}</el-descriptions-item>
            <el-descriptions-item label="亲属联系方式">{{ application.relatives_phone || '' }}</el-descriptions-item>
            <el-descriptions-item label="是否个人">{{ application.is_people === '1' ? '是' : '否' }}</el-descriptions-item>
            <el-descriptions-item label="医疗/殡葬机构">{{ application.ent_name }}</el-descriptions-item>
            <el-descriptions-item label="来源渠道"> {{ getDictLabel('rescue-fund-channel_type', application.channel_type) }} </el-descriptions-item>
          </el-descriptions>

          <!-- 申请经办人信息 Section -->
          <el-descriptions title="申请经办人信息" border direction="vertical" style="margin-top: 20px;">
            <el-descriptions-item label="经办人姓名">{{ application.sqjbr_name || '' }}</el-descriptions-item>
            <el-descriptions-item label="经办人联系方式">{{ application.sqjbr_phone }}</el-descriptions-item>
            <el-descriptions-item label="经办人证件类型">{{ getDictLabel('rescue-fund-credentials_type', application.sqjbr_credentials_type) }}</el-descriptions-item>
            <el-descriptions-item label="经办人证件号">{{ application.sqjbr_credentials_code }}</el-descriptions-item>
          </el-descriptions>

          <!-- 时间信息 Section -->
          <el-descriptions title="时间信息" border direction="vertical" style="margin-top: 20px;">
            <el-descriptions-item label="创建时间">{{ application.created_at }}</el-descriptions-item>
            <el-descriptions-item label="更新时间">{{ application.updated_at }}</el-descriptions-item>
          </el-descriptions>

          <div class="h-[3rem]"></div>

        </el-tab-pane>

        <el-tab-pane label="文件列表" name="file">
          <!-- 将 applicationId 传递给子组件 -->
          <file-manager :applicationId="applicationId" />
        </el-tab-pane>

<!--        <el-tab-pane label="审核结果" name="state" v-if="application.sqxx_id">-->
<!--          &lt;!&ndash; 将 applicationId 传递给子组件 &ndash;&gt;-->
<!--          <file-manager :applicationId="applicationId" />-->
<!--        </el-tab-pane>-->

      </el-tabs>

    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'; // 获取路由信息
import {apply, approve, detail, view_files} from '~/rescuefund/api/RescueFundApplications.ts'; // API 请求方法
import { ElMessage } from 'element-plus';
import {useMessage} from "@/hooks/useMessage";
import FileManager from "~/rescuefund/views/RescueFundApplications/examine/fileManager.vue";
import useTabStore from '@/store/modules/useTabStore'
import Divider from "$/mine-admin/basic-ui/components/dropdown/divider.vue";


// 定义 application 数据和加载状态
const dictStore = useDictStore();
const application = ref<any>({});
const files_data = ref<any>({});
const loading = ref(true);
const msg = useMessage()
const activeName = ref('info')

// 获取路由中的应用ID
const route = useRoute();
const router = useRouter()
const tabStore = useTabStore()


const applicationId = route.params.id as string;

// 请求数据
const fetchApplicationDetail = async (id: string) => {
  try {
    const response = await detail(id);
    if (response.code === 200 && response.data) {
      application.value = response.data; // 设置返回的数据
      loading.value = false; // 加载完成
    } else {
      msg.error('无法获取到数据');
    }
  } catch (error) {
    console.error('获取申请详情失败:', error);
    msg.error('请求失败，请稍后再试');
  }
};

//请求文件数据
const view_files_func = async (id: string) => {
  try {
    const response = await view_files(id);
    if (response.code === 200 && response.data) {
      files_data.value = response.data; // 设置返回的数据
      loading.value = false; // 加载完成
     // msg.success('文件上传完成')
    } else {
      msg.error('无法获取到文件数据');
    }
  } catch (error) {
    msg.error('请求失败，请稍后再试');
  }
};

//请求文件数据
const applyFunc = async () => {
  try {
    const response = await apply({
      id:applicationId
    });
    if (response.code === 200 && response.data) {
      files_data.value = response.data; // 设置返回的数据
      loading.value = false; // 加载完成
      msg.success('提交完成')
      fetchApplicationDetail(applicationId);
    } else {
      msg.error('提交失败');
    }
  } catch (error) {
    msg.error('请求失败，请稍后再试');
  }
};

// 组件挂载后请求数据
onMounted(() => {
  if (applicationId) {
    fetchApplicationDetail(applicationId);
  } else {
    msg.error('Missing application ID');
  }
});

// 通用的获取字典标签的函数
const getDictLabel = (dictName: string, value: any, attrName: string = 'label'): string => {
  const label = dictStore.t(dictName, value, attrName);
  return label || '';
};

// 审批操作
// 定义审批操作
const approveApplication = async (val) => {
  try {
    // 获取数据
    const data = {is_approved: val};

    // 执行 API 调用
    const response = await approve(Number.parseInt(applicationId), data);

    // 根据 API 返回值处理
    if (response.code === 200) {
      msg.success('审核通过');
      // 可以根据需要刷新页面或者做其他处理
      loading.value = false; // 加载完成
      fetchApplicationDetail(applicationId)
    }
  } catch (error) {
    msg.error('请求失败，请稍后再试');
  }
};

const handleClick = (name)=>{
  if(name == 'file'){
    view_files_func(applicationId)
  }else if(name == 'info'){
    fetchApplicationDetail(applicationId)
  }
}

// 拒绝操作
const rejectApplication = () => {
  console.log('拒绝申请');
};
const linkPage=()=>{
  //这里写 判断是否有/rescuefund/RescueFundStatus标签页。
  const targetPath = '/rescuefund/RescueFundStatus'

  // 查找是否已有目标标签页
  const existingTab = tabStore.tabList.find(tab => tab.path === targetPath)

  if (existingTab) {
    // 如果存在，则先关闭该标签页
    tabStore.closeTab(existingTab)
  }

  router.push({
    path: `/rescuefund/RescueFundStatus`, query: {application_id:applicationId}}
  )
}
</script>

<style scoped>
.skeleton-container {
  margin-bottom: 20px;
}
.audit-layout {
  max-width: 800px;
  margin: 0 auto;
}
</style>
