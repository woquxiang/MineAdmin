import type { ResponseStruct } from '#/global'

export interface RescueApplyVo {
  // 主键ID
  id: number
  // 关联的申请ID（主表的主键）
  application_id: number
  // 创建时间
  created_at: string
  // 更新时间
  updated_at: string
  // 创建者
  created_by: number
  // 更新者
  updated_by: number
  // 服务站 受理网点
  acceptPoint: string
  // 案发时间 2024-12-20 15
  accident_date: string
  // 事发地点
  road: string
  // 伤者
  injured: string
  // 1伤2亡
  type: string
  // 123
  reason: string
  // 内容
  desc: string
  // 联系人
  relation_name: string
  // 联系电话
  relation_phone: string
  // 日期
  date: string
}

// 直赔申请书查询
export function page(params: RescueApplyVo): Promise<ResponseStruct<RescueApplyVo[]>> {
  return useHttp().get('/admin/rescuefund/rescue_apply/list', { params })
}

// 直赔申请书新增 并生成pdf 响应时间30s

export function create(data: RescueApplyVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/rescuefund/rescue_apply', data,{timeout:50000})
}
// 直赔申请书编辑
export function save(id: number, data: RescueApplyVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/rescuefund/rescue_apply/${id}`, data)
}

// 直赔申请书删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/rescuefund/rescue_apply', { data: ids })
}