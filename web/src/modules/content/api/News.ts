import type { ResponseStruct } from '#/global'

export interface NewsVo {
  // 自增ID，唯一标识每条资讯
  id: string
  // 标题，不能为空
  title: string
  // 简短描述，资讯的简要内容，不能为空
  short_description: string
  // 详情，富文本内容，不能为空
  content: string
  // 创建时间，默认当前时间
  created_at: string
  // 更新时间，自动更新为当前时间
  updated_at: string
  // 创建者，记录创建者的ID，默认为0
  created_by: number
  // 更新者，记录更新者的ID，默认为0
  updated_by: number
  // 排序，控制资讯的显示顺序，默认值为0
  sort_order: string
  // 作者，不能为空
  author: string
}

// 内容管理查询
export function page(params: NewsVo): Promise<ResponseStruct<NewsVo[]>> {
  return useHttp().get('/admin/content/news/list', { params })
}

// 内容管理新增
export function create(data: NewsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/content/news', data)
}

// 内容管理编辑
export function save(id: number, data: NewsVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/content/news/${id}`, data)
}

// 内容管理删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/content/news', { data: ids })
}