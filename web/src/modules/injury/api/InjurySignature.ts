import type { ResponseStruct } from '#/global'

export interface InjurySignatureVo {
  // 签名ID
  id: string
  // 赔偿申请ID
  application_id: string
  // 直赔ID
  direct_compensation_id: string
  // 姓名
  name: string
  // 签名数据（Base64编码）
  signature_data: string
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

// 签名信息查询
export function page(params: InjurySignatureVo): Promise<ResponseStruct<InjurySignatureVo[]>> {
  return useHttp().get('/admin/injury/injury_signature/list', { params })
}

// 签名信息新增
export function create(data: InjurySignatureVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/injury/injury_signature', data)
}

// 签名信息编辑
export function save(id: number, data: InjurySignatureVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/injury/injury_signature/${id}`, data)
}

// 签名信息删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/injury/injury_signature', { data: ids })
}