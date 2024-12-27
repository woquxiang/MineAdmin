import type { ResponseStruct } from '#/global'

export interface RescueFundApplicationsVo {
  // 主键ID
  id: number
  // 申请费用类型
  apply_fee_type: string
  // 事故时间
  sg_time: string
  // 事故地点（省）
  sg_prov: string
  // 事故地点（市）
  sg_city: string
  // 事故地点（区/县）
  sg_area: string
  // 事故详细地址
  sg_address: string
  // 受害人姓名
  shr_name: string
  // 受害人联系方式
  shr_phone: string
  // 受害人证件类型
  shr_credentials_type: string
  // 受害人证件号
  shr_credentials_code: string
  // 受害人身份证地址
  identity_card_address: string
  // 受害人现居住地址
  current_residence_address: string
  // 申请经办人姓名
  sqjbr_name: string
  // 申请经办人联系方式
  sqjbr_phone: string
  // 申请经办人证件类型
  sqjbr_credentials_type: string
  // 申请经办人证件号
  sqjbr_credentials_code: string
  // 与受害人关系
  shr_relationship_type: string
  // 亲属联系方式（默认空字符串）
  relatives_phone: string
  // 是否个人（默认0）
  is_people: string
  // 医疗/殡葬机构
  ent_name: string
  // 来源渠道（默认unknown）
  channel_type: string
  // 创建时间
  created_at: string
  // 更新时间
  updated_at: string
  // 更新时间
  is_approved: number
}

// 道路基金查询
export function page(params: RescueFundApplicationsVo): Promise<ResponseStruct<RescueFundApplicationsVo[]>> {
  return useHttp().get('/admin/rescuefund/rescue_fund_applications/list', { params })
}

// 道路基金详情
export function detail(id: number): Promise<ResponseStruct<null>> {
  return useHttp().get(`/admin/rescuefund/rescue_fund_applications/${id}`)
}

// 道路基金新增
export function create(data: RescueFundApplicationsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/rescuefund/rescue_fund_applications', data)
}

// 道路基金编辑
export function save(id: number, data: RescueFundApplicationsVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/rescuefund/rescue_fund_applications/${id}`, data)
}

// 道路基金审核
export function approve(id: number, data: RescueFundApplicationsVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/rescuefund/rescue_fund_applications/${id}/approve`, data)
}

// 道路基金删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/rescuefund/rescue_fund_applications', { data: ids })
}

// 道路基金查看文件
export function view_files(id: number): Promise<ResponseStruct<null>> {
  return useHttp().get(`/admin/rescuefund/rescue_fund_applications/${id}/view_files`)
}

// 道路基金传文件 设置10分钟超时
export function sync_file(data: RescueFundApplicationsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/rescuefund/rescue_fund_applications/sync_file', data, {
    timeout: 10 * 60 * 1000
  })
}

// 道路基金申请
export function apply(data: RescueFundApplicationsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/rescuefund/rescue_fund_applications/apply', data)
}
