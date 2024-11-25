import type { ResponseStruct } from '#/global'

export interface IssuesVo {
  // 自增ID，唯一标识每条记录
  id: string
  // 投诉或建议的内容，纯文本
  content: string
  // 联系方式，用户提供的联系信息，例如电话或邮箱
  contact_info: string
  // 创建时间，记录提交时间
  created_at: string
  // 更新时间，记录最后更新时间
  updated_at: string
  // 创建者，记录提交投诉或建议的用户ID
  created_by: number
  // 更新者，记录最后处理该投诉或建议的用户ID
  updated_by: number
}

// 用户反馈查询
export function page(params: IssuesVo): Promise<ResponseStruct<IssuesVo[]>> {
  return useHttp().get('/admin/feedback/issues/list', { params })
}

// 用户反馈新增
export function create(data: IssuesVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/feedback/issues', data)
}

// 用户反馈编辑
export function save(id: number, data: IssuesVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/feedback/issues/${id}`, data)
}

// 用户反馈删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/feedback/issues', { data: ids })
}