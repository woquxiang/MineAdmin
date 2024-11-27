import type { ResponseStruct } from '#/global'

export interface J123AccidentNumberVo {
  // 主键ID
  id: string
  // 案件编号
  accident_number: string
  // 状态 (0: 不需要, 1: 需要抓取)
  status: string
  // 创建时间
  created_at: string
  // 更新时间
  updated_at: string
  // 创建者
  created_by: number
  // 更新者
  updated_by: number
}

// 案件号管理查询
export function page(params: J123AccidentNumberVo): Promise<ResponseStruct<J123AccidentNumberVo[]>> {
  return useHttp().get('/admin/accident/j123_accident_number/list', { params })
}

// 案件号管理新增
export function create(data: J123AccidentNumberVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/accident/j123_accident_number', data)
}

// 案件号管理编辑
export function save(id: number, data: J123AccidentNumberVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/accident/j123_accident_number/${id}`, data)
}

// 案件号管理删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/accident/j123_accident_number', { data: ids })
}
