import type { ResponseStruct } from '#/global'

export interface PatientsVo {
  // patients患者ID
  id: string
  // 患者姓名
  name: string
  // 患者性别
  sex: string
  // 患者身份证号
  id_card: string
  // 患者出生日期
  birthday: string
  // 患者联系电话
  phone: string
}

// 患者管理查询
export function page(params: PatientsVo): Promise<ResponseStruct<PatientsVo[]>> {
  return useHttp().get('/admin/healthcare/patients/list', { params })
}

// 患者管理新增
export function create(data: PatientsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/healthcare/patients', data)
}

// 患者管理编辑
export function save(id: number, data: PatientsVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/healthcare/patients/${id}`, data)
}

// 患者管理删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/healthcare/patients', { data: ids })
}