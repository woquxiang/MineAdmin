import type { ResponseStruct } from '#/global'

export interface InjuryPartyInformationVo {
  // 当事人ID
  id: string
  // 赔偿申请ID
  application_id: string
  // 姓名
  name: string
  // 联系电话
  phone: string
  // 性别
  gender: string
  // 身份证号码
  id_number: string
  // 住址
  address: string
  // 交通方式
  transportation_method: string
  // 车辆所有人
  vehicle_owner: string
  // 车牌号
  license_plate: string
  // 车辆所有人住址
  vehicle_owner_address: string
  // 被保险人
  insured_person: string
  // 保险公司
  insurance_company: string
  // 投保险别
  insurance_type: string
  // 投保险金额
  insurance_amount: string
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

// 人伤当事人查询
export function page(params: InjuryPartyInformationVo): Promise<ResponseStruct<InjuryPartyInformationVo[]>> {
  return useHttp().get('/admin/injury/injury_party_information/list', { params })
}

// 人伤当事人新增
export function create(data: InjuryPartyInformationVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/injury/injury_party_information', data)
}

// 人伤当事人编辑
export function save(id: number, data: InjuryPartyInformationVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/injury/injury_party_information/${id}`, data)
}

// 人伤当事人删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/injury/injury_party_information', { data: ids })
}