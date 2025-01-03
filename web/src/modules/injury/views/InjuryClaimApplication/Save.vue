<script setup lang="tsx">
import { ref, reactive } from 'vue'
import { ElMessage } from 'element-plus'
import { useMessage } from '@/hooks/useMessage.ts'
import { create, details,save } from "~/injury/api/InjuryClaimApplication"
import { page } from "~/injury/api/InjurySignature"
import { useRouter } from 'vue-router'
import dayjs from 'dayjs' // 如果项目中使用了 dayjs
import { getHospitalList } from "~/injury/api/InjuryClaimApplication"
import { saveAs } from 'file-saver'
import axios from 'axios'
import useDialog from '@/hooks/useDialog'
import { mergeSignature } from "~/injury/api/InjuryPartyInformation"
import type { InjurySignatureVo } from "~/injury/api/InjurySignature"


const msg = useMessage()
//拿路由参数 /injury/save/:id
const route = useRoute()
const id = route.params.id
const router = useRouter()

// 初始化数据模型
const model = ref({})

const partyList = ref([])  // 用于存储当事人信息
const activeTab = ref('party1')  // 当前活动标签页

// 动态表单数据
const dynamicModels = ref([])  // 用于保存每个当事人的表单数据
const formRefs = ref([])  // 用于存储每个当事人表单的引用
const formRef = ref()

// 签名对话框相关
const signatureDialogVisible = ref(false)
const signatureList = ref<InjurySignatureVo[]>([])
const currentDirectCompensationId = ref('')
const mergeLoading = ref(false)
const pdfUrl = ref('')

// 签名对话框配置
const signatureDialog = useDialog({
  ok: async (_, okLoadingState) => {
    okLoadingState(true)
    try {
      // TODO: 调用合成签名的接口
      msg.success('签名合成成功')
      signatureDialogVisible.value = false
    } catch (error) {
      console.error('签名合成失败:', error)
      msg.error('签名合成失败')
    }
    okLoadingState(false)
  }
})

// 初始化第一个当事人
const initFirstParty = () => {
  addPartyForm()
}

// 添加医院列表数据
const hospitalList = ref([])

// 获取医院列表
const fetchHospitalList = async () => {
  try {
    const res = await getHospitalList()
    hospitalList.value = res.data || []
  } catch (error) {
    console.error('获取医院列表失败:', error)
    msg.error('获取医院列表失败')
  }
}

// 处理标签页的编辑（删除）
const handleTabEdit = (targetName: string, action: 'remove' | 'add') => {
  if (action === 'remove') {
    const index = parseInt(targetName.replace('party', '')) - 1
    if (partyList.value.length <= 1) {
      ElMessage.warning('至少需要保留一个当事人')
      return
    }
    
    // 删除对应的数据
    partyList.value.splice(index, 1)
    dynamicModels.value.splice(index, 1)
    
    // 重新设置当事人序号
    partyList.value = partyList.value.map((item, idx) => ({
      id: idx + 1,
      name: `当事人${idx + 1}`
    }))
    
    // 如果删除的是当前激活的标签，则切换到前一个标签
    if (activeTab.value === targetName) {
      activeTab.value = index === 0 
        ? `party${index + 1}` 
        : `party${index}`
    }else{
      // 如果删除的不是当前激活的标签，切换到第一个标签
      activeTab.value = `party1`
    }
  }
}


// 添加当事人表单
const addPartyForm = () => {
  const newPartyForm = {
    name: '',
    gender: '男',
    phone: '',
    id_number: '',
    address: '',
    transportation_method: '',
  }

  dynamicModels.value.push(newPartyForm)
  partyList.value.push({ id: dynamicModels.value.length, name: `当事人${dynamicModels.value.length}` })
  //设置当前标签页
  activeTab.value = `party${dynamicModels.value.length}`
}

// 当事人表单验证规则
const partyRules = {
  name: [{ required: true, message: '请输入姓名', trigger: 'blur' }],
  gender: [{ required: true, message: '请选择性别', trigger: 'change' }],
  phone: [
    { required: true, message: '请输入联系电话', trigger: 'blur' },
   //  { pattern: /^[0-9]{11}$/, message: '请输入有效的手机号码', trigger: 'blur' }
  ],
  id_number: [{ required: true, message: '请输入身份证号', trigger: 'blur' }],
  address: [{ required: true, message: '请输入住址', trigger: 'blur' }],
  transportation_method: [{ required: true, message: '请输入交通方式', trigger: 'blur' }],
  //就诊医院
  hospital_id: [{ required: true, message: '请选择就诊医院', trigger: 'change' }],
}

// 基本信息表单验证规则
const basicInfoRules = {
  emergency_phone: [
    { required: true, message: '请输入报警电话', trigger: 'blur' },
    // { pattern: /^[0-9]{11}$/, message: '请输入有效的报警电话', trigger: 'blur' }
  ],
  application_date: [{ required: true, message: '请选择申请时间', trigger: 'change' }],
  accident_time: [{ required: true, message: '请选择事故时间', trigger: 'change' }],
  weather_condition: [{ required: true, message: '请输入天气情况', trigger: 'blur' }],
  accident_location: [{ required: true, message: '请输入事故地点', trigger: 'blur' }],
  accident_description: [{ required: true, message: '请输入事故描述', trigger: 'blur' }],
  handling_method: [{ required: true, message: '请输入处理方式', trigger: 'blur' }],
}

// 提交表单
const submit = async () => {
  try {
    // 验证基本信息表单
    await formRef.value.getElFormRef().validate()
    
    // 验证所有当事人表单
    for (let i = 0; i < formRefs.value.length; i++) {
      const form = formRefs.value[i]
      try {
        if (!form?.getElFormRef()) {
          throw new Error('表单验证方法不存在')
        }
        await form.getElFormRef().validate()
      } catch (error) {
        // 切换到对应的 tab
        activeTab.value = `party${i + 1}`
        // 抛出包含当事人信息的错误
        throw new Error(`当事人${i + 1}的信息填写有误，请检查`)
      }
    }
    
    // 准备提交的数据
    const submitData = {
      ...model.value,
      party_information: dynamicModels.value,
    }

    // 根据是否有 id 判断是创建还是更新
    let res
    if (id && id !== 'add') {
      res = await save(id, submitData)
      msg.success('更新成功')
    } else {
      res = await create(submitData)
      msg.success('创建成功')
    }

    // 成功后返回列表页
    goBack()

  } catch (error) {
    console.error('表单验证失败:', error)
    msg.error('请检查表单填写是否完整')
    return false
  }
}




// 获取人伤直赔记录
const getInjuryClaimApplicationById = async (id: string) => {
  try {
    // 获取人伤直赔记录
    const res = await details(id)

    // 设置基本信息
    Object.assign(model.value, {
      emergency_phone: res.data.emergency_phone || '',
      application_date: res.data.application_date || '',
      accident_time: res.data.accident_time || '',
      weather_condition: res.data.weather_condition || '',
      accident_location: res.data.accident_location || '',
      accident_description: res.data.accident_description || '',
      handling_method: res.data.handling_method || '',
      case_code: res.data.case_code || '',
    })

    // 设置当事人信息
    if (res.data.party_information && res.data.party_information.length > 0) {
      dynamicModels.value = res.data.party_information
      //设置就诊医院 如果是0 就变成空字符串
      dynamicModels.value.forEach(item => {
        item.hospital_id = item.hospital_id == 0 ? '' : item.hospital_id
      })

      // 重新生成当事人列表
      partyList.value = res.data.party_information.map((_, index) => ({
        id: index + 1,
        name: `当事人${index + 1}`
      }))
      activeTab.value = 'party1'
    } else {
      // 如果没有当事人数据，初始化一个
      addPartyForm()
    }
  } catch (error) {
    console.error('获取数据失败:', error)
    msg.error('获取数据失败')
    // 如果获取数据失败，至少初始化一个当事人
    addPartyForm()
  }
}

// 页面加载时根据路由参数决定是否获取数据
onMounted(async () => {
  await fetchHospitalList()

  if (id && id !== 'add') {
    await getInjuryClaimApplicationById(id)
  } else {
    // 新增模式，设置默认时间为当前时间
    model.value.application_date = dayjs().format('YYYY-MM-DD HH:mm:ss')
    // 初始化一个当事人
    addPartyForm()
  }
})

// 返回列表页
const goBack = () => {
  router.push({
    path: '/injury/InjuryClaimApplication',
  })
}

// 下载直赔通知书
const downloadAttachment = async (url: string) => {
  try {
    if (!url) {
      msg.error('文件路径无效');
      return
    }

    const response = await axios.get(url, {
      responseType: 'blob',
    });

    // 文件名 改随时间
    const fileName = `直赔通知书_${dayjs().format('YYYY-MM-DD_HH-mm-ss')}.pdf`
    saveAs(response.data, fileName); // 使用 file-saver 的 saveAs 方法
  } catch (error) {
    console.error('下载失败:', error)
    msg.error('下载失败，请稍后再试');
    // 下载失败就用浏览器打开
    window.open(url, '_blank')
  }
}

// 打开签名对话框
const generateSignature = async (index) => {
  if (!dynamicModels.value[index].direct_compensation_id) {
    msg.error('请提交后生成')
    return
  }
  currentDirectCompensationId.value = dynamicModels.value[index].direct_compensation_id
  signatureDialogVisible.value = true
  await fetchSignatureList()
}

// 获取签名列表
const fetchSignatureList = async () => {
  try {
    const res = await page({ direct_compensation_id: currentDirectCompensationId.value })
    if (res.code === 200) {
      signatureList.value = res.data.list || []
    } else {
      msg.error(res.message || '获取签名列表失败')
    }
  } catch (error) {
    console.error('获取签名列表失败:', error)
    msg.error('获取签名列表失败')
  }
}

// 合成签名
const mergeSignatures = async () => {
  if (signatureList.value.length === 0) {
    msg.warning('没有可合成的签名')
    return
  }
  
  mergeLoading.value = true
  try {
    // 调用合成签名的接口
    const response = await mergeSignature(currentDirectCompensationId.value)

    if (response.code === 200 && response.data?.url) {
      msg.success('合成成功')
      pdfUrl.value = response.data.url
      // 尝试下载文件
      try {
        const downloadResponse = await axios.get(response.data.url, {
          responseType: 'blob'
        })
        // 文件名使用时间戳
        const fileName = `直赔申请书_${dayjs().format('YYYY-MM-DD_HH-mm-ss')}.pdf`
        saveAs(downloadResponse.data, fileName)
        msg.success('下载成功')
        signatureDialogVisible.value = false
      } catch (downloadError) {
        console.error('下载失败，尝试打开链接:', downloadError)
        // 下载失败就用浏览器打开
        window.open(response.data.url, '_blank')
      }
    } else {
      msg.error(response.message || '合成签名失败')
    }
  } catch (error) {
    console.error('签名合成失败:', error)
    msg.error('签名合成失败')
  } finally {
    mergeLoading.value = false
  }
}

</script>

<template>
  <div class="mine-layout">
    <div class="p-2">
      <!-- 添加标题显示 -->

      <div class="flex justify-between items-center mb-8 px-5">
        <div class="text-xl font-bold text-white">
          {{ id && id !== 'add' ? '编辑人伤直赔申请' : '新增人伤直赔申请' }}
        </div>
        <div class="text-right">
          <el-button type="primary" @click="submit">
            {{ id && id !== 'add' ? '保存' : '提交' }}
          </el-button>
          <el-button @click="goBack">取消</el-button>
        </div>
      </div>
      <!-- 基本信息表单 -->
      <div class="flex">
        <!-- 设置 背景颜色设置#0A214C border 设置1px solid #0278c6 box-shadow 设置0 0 .625vw rgba(0,159,255,.54)-->
        <div class="w-1/2 mx-5 px-5 h-[500px]  shadow-lg rounded-lg !bg-[#0A214C] !border border-[#0278c6] border-solid	 !shadow-[0_0_.625vw_rgba(0,159,255,1)] ">
          <div class="text-lg font-bold py-5 text-white">基本信息</div>
          <ma-form ref="formRef" v-model="model" :rules="basicInfoRules" label-width="100px">
            <div class="flex">
              <div class="w-1/2 mr-2">
                <el-form-item label="报警电话" prop="emergency_phone">
                  <el-input v-model="model.emergency_phone" placeholder="请输入报警电话"></el-input>
                </el-form-item>

                <el-form-item label="案件编号" prop="case_code">
                  <el-input v-model="model.case_code" placeholder="请输入案件编号"></el-input>
                </el-form-item>

                <el-form-item label="事故地点" prop="accident_location">
                  <el-input v-model="model.accident_location" placeholder="请输入事故地点"></el-input>
                </el-form-item>

                <el-form-item label="事故描述" prop="accident_description">
                  <el-input v-model="model.accident_description" placeholder="请输入事故描述"></el-input>
                </el-form-item>
              </div>
              <div class="w-1/2">
              
                <el-form-item label="申请时间" prop="application_date">
                  <el-date-picker readonly="true" v-model="model.application_date" type="datetime" placeholder="请选择申请时间" format="YYYY-MM-DD HH:mm:ss" value-format="YYYY-MM-DD HH:mm:ss"></el-date-picker>
                </el-form-item>

                <el-form-item label="事故时间" prop="accident_time">
                  <el-date-picker v-model="model.accident_time" type="datetime" placeholder="请选择事故时间" format="YYYY-MM-DD HH:mm:ss" value-format="YYYY-MM-DD HH:mm:ss"></el-date-picker>
                </el-form-item>

                <el-form-item label="天气情况" prop="weather_condition">
                  <el-input v-model="model.weather_condition" placeholder="请输入天气情况"></el-input>
                </el-form-item>

                <el-form-item label="处理方式" prop="handling_method">
                  <el-input v-model="model.handling_method" placeholder="请输入处理方式"></el-input>
                </el-form-item>
              </div>
            </div>
          </ma-form>
        </div>

        <!-- 当事人信息 -->
        <div class="w-1/2 px-5 w-1/2 mx-5 px-5 h-[500px]  shadow-lg rounded-lg !bg-[#0A214C] !border border-[#0278c6] border-solid	 !shadow-[0_0_.625vw_rgba(0,159,255,1)] ">
          <div class="text-lg font-bold py-5 text-white">当事人信息</div>
          <el-button type="primary" class="w-full" @click="addPartyForm">添加当事人</el-button>

          <el-tabs v-model="activeTab" closable @edit="handleTabEdit">
            <el-tab-pane
              v-for="(item, index) in partyList"
              :key="index"
              :label="item.name"
              :name="'party' + (index + 1)"
            >
              <!-- 动态表单 -->
              <ma-form
                class="h-[340px] overflow-y-auto"
                ref="formRefs"
                v-model="dynamicModels[index]"
                :rules="partyRules"
                label-width="100px"
              >
                <div class="flex">
                  <div class="w-1/2">
                    <el-form-item label="姓名" prop="name">
                      <el-input v-model="dynamicModels[index].name" placeholder="请输入姓名"></el-input>
                    </el-form-item>
                    <el-form-item label="性别" prop="gender">
                      <el-select v-model="dynamicModels[index].gender" placeholder="请选择性别">
                        <el-option label="男" value="男"></el-option>
                        <el-option label="女" value="女"></el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item label="住址" prop="address">
                      <el-input v-model="dynamicModels[index].address" placeholder="请输入住址"></el-input>
                    </el-form-item>
                    <el-form-item label="车辆所有人" prop="vehicle_owner">
                      <el-input v-model="dynamicModels[index].vehicle_owner" placeholder="请输入车辆所有人"></el-input>
                    </el-form-item>
                    <el-form-item label="车辆所有人地址" prop="vehicle_owner_address">
                      <el-input v-model="dynamicModels[index].vehicle_owner_address" placeholder="请输入车辆所有人地址"></el-input>
                    </el-form-item>
                    <el-form-item label="保险公司" prop="insurance_company">
                      <el-input v-model="dynamicModels[index].insurance_company" placeholder="请输入保险公司"></el-input>
                    </el-form-item>

                    <!-- 投保金额 -->
                    <el-form-item label="投保金额" prop="insurance_amount">
                      <el-input v-model="dynamicModels[index].insurance_amount" placeholder="请输入投保金额"></el-input>
                    </el-form-item>

                  </div>
                  <div class="w-1/2">
                    <el-form-item label="联系电话" prop="phone">
                      <el-input v-model="dynamicModels[index].phone" placeholder="请输入联系电话"></el-input>
                    </el-form-item>
                    <el-form-item label="身份证号" prop="id_number">
                      <el-input v-model="dynamicModels[index].id_number" placeholder="请输入身份证号"></el-input>
                    </el-form-item>
                    <el-form-item label="交通方式" prop="transportation_method">
                      <el-input v-model="dynamicModels[index].transportation_method" placeholder="请输入交通方式"></el-input>
                    </el-form-item>
                    <el-form-item label="车牌号" prop="license_plate">
                      <el-input v-model="dynamicModels[index].license_plate" placeholder="请输入车牌号"></el-input>
                    </el-form-item>
                    <el-form-item label="被保险人" prop="insured_person">
                      <el-input v-model="dynamicModels[index].insured_person" placeholder="请输入被保险人"></el-input>
                    </el-form-item>
                    <el-form-item label="保险险别" prop="insurance_type">
                      <el-input v-model="dynamicModels[index].insurance_type" placeholder="请输入保险险别"></el-input>
                    </el-form-item>

                    <!-- 是否是伤者 -->
                    <el-form-item label="是否是伤者" prop="is_injured">
                      <el-switch v-model="dynamicModels[index].is_injured" active-text="是" inactive-text="否" :active-value="1" :inactive-value="0" />
                    </el-form-item>
                  </div>
                </div>


              <!-- 如果是伤者 -->
              <div v-if="dynamicModels[index].is_injured == 1">

                <!-- 直赔文书 -->
                <div class="flex">
                  <div class="w-1/2">
                    <el-form-item label="直赔通知书">
                      <el-button type="primary" @click="downloadAttachment(dynamicModels[index].attachment?.url)" :disabled="!dynamicModels[index].attachment">
                        {{ dynamicModels[index].attachment ? '直赔通知书' : '正在生成中...' }}
                      </el-button>
                    </el-form-item>
                  </div>
                  <div class="w-1/2">
                    <el-form-item label="直赔申请书">
                      <el-button type="primary" @click="generateSignature(index)">直赔申请书</el-button>
                    </el-form-item>
                  </div>
                </div>

                <!-- 住院信息 -->
               <div class="text-lg font-bold text-white py-3 text-center">住院信息</div>

                <div class="flex">

                <div class="w-1/2">
                  <el-form-item label="伤者病区号" prop="ward_number" label-width="150px">
                    <el-input v-model="dynamicModels[index].ward_number" placeholder="请输入伤者病区号"></el-input>
                  </el-form-item>

                  <el-form-item label="总医疗费用（保险直赔金额）" prop="total_medical_expenses_insurance" label-width="150px">
                    <el-input v-model="dynamicModels[index].total_medical_expenses_insurance" placeholder="请输入总医疗费用"></el-input>
                  </el-form-item>

                 <el-form-item label="总医疗费用（自费金额）" prop="total_medical_expenses_self_pay" label-width="150px">
                    <el-input v-model="dynamicModels[index].total_medical_expenses_self_pay" placeholder="请输入总医疗费用"></el-input>
                  </el-form-item>

                    <el-form-item label="住院科别" prop="hospitalization_department" label-width="150px">
                    <el-input v-model="dynamicModels[index].hospitalization_department" placeholder="请输入住院科别"></el-input>
                  </el-form-item>



                   <el-form-item label="总医疗费用（路就基金垫付金额）" prop="total_medical_expenses_road_fund" label-width="150px">
                    <el-input v-model="dynamicModels[index].total_medical_expenses_road_fund" placeholder="请输入总医疗费用"></el-input>
                  </el-form-item>

                </div>

                <div class="w-1/2">
                  <el-form-item label="伤者病床号" prop="bed_number" label-width="150px">
                    <el-input v-model="dynamicModels[index].bed_number" placeholder="请输入伤者病床号"></el-input>
                  </el-form-item>

                   <el-form-item label="伤情诊断" prop="injury_diagnosis" label-width="150px">
                    <el-input v-model="dynamicModels[index].injury_diagnosis" placeholder="请输入伤情诊断"></el-input>
                  </el-form-item>



                    <el-form-item label="住院号" prop="hospitalization_number" label-width="150px">
                    <el-input v-model="dynamicModels[index].hospitalization_number" placeholder="请输入住院号"></el-input>
                  </el-form-item>

                    <el-form-item label="是否出院" prop="discharge_status" label-width="150px">
                    <el-radio-group v-model="dynamicModels[index].discharge_status" placeholder="请选择是否出院">
                      <el-radio label="住院">住院</el-radio>
                      <el-radio label="出院">出院</el-radio>
                    </el-radio-group>
                  </el-form-item>

                  <!-- 就诊医院 -->
                  <el-form-item label="就诊医院" prop="hospital_id" label-width="150px">
                    <el-select  v-model="dynamicModels[index].hospital_id" placeholder="请选择就诊医院" clearable>
                      <el-option v-for="item in hospitalList" :key="item.user_id" :label="item.nick_name" :value="item.user_id"></el-option>
                    </el-select>
                  </el-form-item>

                </div>

                </div>  

              </div>

              </ma-form>
            </el-tab-pane>
          </el-tabs>
        </div>
      </div>

      <!-- 签名对话框 -->
      <el-dialog
        v-model="signatureDialogVisible"
        title="签名列表"
        width="500px"
        class="!bg-[#0A214C]"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        destroy-on-close
      >
        <div class="p-4">
          <!-- 签名列表 -->
          <div class="mb-4 max-h-[60vh] overflow-y-auto">
            <div v-for="(item, index) in signatureList" :key="index" class="mb-4 p-4 border rounded !bg-[#0A214C] !border-[#0278c6]">
              <div class="flex justify-between items-center mb-2 text-white">
                <span>签名人：{{ item.name }}</span>
                <span>{{ item.created_at }}</span>
              </div>
              <img :src="item.signature_data" alt="签名" class="w-full h-32 object-contain border rounded bg-white" />
            </div>
          </div>

          <!-- PDF URL 显示区域 -->
          <div v-if="pdfUrl" class="mb-4 p-4 border rounded !bg-[#0A214C] !border-[#0278c6]">
            <div class="text-white mb-2">PDF 链接：</div>
            <el-link type="primary" :href="pdfUrl" target="_blank" class="break-all">{{ pdfUrl }}</el-link>
          </div>
        </div>
        
        <!-- 底部按钮 -->
        <template #footer>
          <div class="dialog-footer">
            <el-button @click="signatureDialogVisible = false">取 消</el-button>
            <el-button type="primary" :loading="mergeLoading" @click="mergeSignatures">
              {{ mergeLoading ? '合成中...' : '签名合成' }}
            </el-button>
          </div>
        </template>
      </el-dialog>
    </div>
  </div>
</template>

<style scoped lang="scss">
.mine-layout {
  padding: 20px;
}

:deep(.el-dialog) {
  background: #0A214C !important;
  
  .el-dialog__header {
    padding: 16px 20px !important;
    margin: 0 !important;
    
    .el-dialog__title {
      color: white !important;
    }
  }
  
  .el-dialog__body {
    padding: 0 !important;
    color: white !important;
  }
  
  .el-dialog__footer {
    padding: 16px 20px !important;
  }
}
</style>
