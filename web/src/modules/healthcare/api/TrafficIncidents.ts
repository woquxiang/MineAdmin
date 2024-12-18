import type { ResponseStruct } from '#/global'

export interface TrafficIncidentsVo {
  // 主键
  id: string
  // 事故ID
  accident_id: string
  // 患者ID（可能为空）
  patient_id: string
  // 事故发生时间
  accident_time: string
  // 事故发生地点
  accident_location: string
  // 事故描述
  description: string
  // 事故原因
  cause: string
}

// 交通事故查询
export function page(params: TrafficIncidentsVo): Promise<ResponseStruct<TrafficIncidentsVo[]>> {
  return useHttp().get('/admin/healthcare/traffic_incidents/list', { params })
}

// 交通事故新增
export function create(data: TrafficIncidentsVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/healthcare/traffic_incidents', data)
}

// 交通事故编辑
export function save(id: number, data: TrafficIncidentsVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/healthcare/traffic_incidents/${id}`, data)
}

// 交通事故删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/healthcare/traffic_incidents', { data: ids })
}