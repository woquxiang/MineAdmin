import type { ResponseStruct } from '#/global'

export interface InjuryClaimApplicationVo {
  // 申请ID
  id: string
  // 直赔编号
  claim_code: string
  // 报警电话
  emergency_phone: string
  // 申请时间
  application_date: string
  // 医院名称
  hospital_name: string
  // 赔偿总金额
  total_compensation: string
  // 赔偿状态
  claim_status: string
  // 创建时间
  created_at: string
  // 更新时间
  updated_at: string
  // 创建者
  created_by: number
  // 更新者
  updated_by: number
  // 删除时间
  deleted_at: string
}

// 人伤直赔记录查询
export function page(params: InjuryClaimApplicationVo): Promise<ResponseStruct<InjuryClaimApplicationVo[]>> {
  return useHttp().get('/admin/injury/injury_claim_application/list', { params })
}

// 人伤直赔记录新增
export function create(data: InjuryClaimApplicationVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/injury/injury_claim_application', data)
}

// 人伤直赔记录编辑
export function save(id: number, data: InjuryClaimApplicationVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/injury/injury_claim_application/${id}`, data)
}

// 人伤直赔记录删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/injury/injury_claim_application', { data: ids })
}

// 人伤直赔记录详情
export function details(id: number): Promise<ResponseStruct<InjuryClaimApplicationVo>> {
  return useHttp().get(`/admin/injury/injury_claim_application/${id}`)
}

// 获取医院列表
export function getHospitalList(): Promise<ResponseStruct<InjuryClaimApplicationVo[]>> {
  return useHttp().get('/admin/Jj/sys_user/getUsersByDeptId', { params: { dept_id:204 } })
}
