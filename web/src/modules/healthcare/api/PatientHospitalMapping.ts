import type { ResponseStruct } from '#/global'

export interface PatientHospitalMappingVo {
  // 关联表ID
  id: string
  // 本地患者ID
  patient_id: string
  // 医院ID
  hospital_id: string
  // 医院患者ID 用来查询医院数据
  hospital_patient_id: string
  // 记录创建时间
  created_at: string
  // 记录更新时间
  updated_at: string
}

// 医院患者关联查询
export function page(params: PatientHospitalMappingVo): Promise<ResponseStruct<PatientHospitalMappingVo[]>> {
  return useHttp().get('/admin/healthcare/patient_hospital_mapping/list', { params })
}

// 医院患者关联新增
export function create(data: PatientHospitalMappingVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/healthcare/patient_hospital_mapping', data)
}

// 医院患者关联编辑
export function save(id: number, data: PatientHospitalMappingVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/healthcare/patient_hospital_mapping/${id}`, data)
}

// 医院患者关联删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/healthcare/patient_hospital_mapping', { data: ids })
}