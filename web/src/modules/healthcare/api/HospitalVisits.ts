import type { ResponseStruct } from '#/global'

export interface HospitalVisitsVo {
  // 就诊记录ID
  id: string
  // 就诊类型
  visits_type: string
  // 事故ID（可能为空）
  accident_id: string
  // 患者ID（可能为空）
  patient_id: string
  // 医院ID
  hospital_id: string
  // 诊断信息
  diagnosis: string
  // 科室
  section: string
  // 病区
  sickarea: string
  // 床位号
  bedno: string
  // 主治医生
  doctor: string
  // 就诊时间
  i_time: string
  // 出院时间
  o_time: string
}

// 就诊记录查询
export function page(params: HospitalVisitsVo): Promise<ResponseStruct<HospitalVisitsVo[]>> {
  return useHttp().get('/admin/healthcare/hospital_visits/list', { params })
}

// 就诊记录新增
export function create(data: HospitalVisitsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/healthcare/hospital_visits', data)
}

// 就诊记录编辑
export function save(id: number, data: HospitalVisitsVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/healthcare/hospital_visits/${id}`, data)
}

// 就诊记录删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/healthcare/hospital_visits', { data: ids })
}