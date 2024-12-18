import type { ResponseStruct } from '#/global'

export interface MedicalExpensesVo {
  // 费用ID
  id: string
  // 就诊记录ID OR 案件ID
  visit_id: string
  // 费用名称（例如：住院费、检查费等）
  expense_name: string
  // 费用金额
  price: string
  // 服务数量
  quantity: string
}

// 费用管理查询
export function page(params: MedicalExpensesVo): Promise<ResponseStruct<MedicalExpensesVo[]>> {
  return useHttp().get('/admin/healthcare/medical_expenses/list', { params })
}

// 费用管理新增
export function create(data: MedicalExpensesVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/healthcare/medical_expenses', data)
}

// 费用管理编辑
export function save(id: number, data: MedicalExpensesVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/healthcare/medical_expenses/${id}`, data)
}

// 费用管理删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/healthcare/medical_expenses', { data: ids })
}