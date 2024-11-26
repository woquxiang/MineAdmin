import type { ResponseStruct } from '#/global'

export interface RescueFundStatusVo {
  // 主键ID
  id: number
  // 关联的申请ID（主表的主键）
  application_id: number
  // 提交的ID
  sqxx_id: string
  // 案件状态
  wechat_sqxx_state: string
  // 审核人用户账号
  approve_user_name: string
  // 支付时间
  give_money_time: string
  // 费用类型
  apply_fee_type: string
  // 垫付金额
  adjustment_money: string
  // 审核状态
  wechat_approve_state: string
  // 退回原因
  return_reason: string
  // 银行回单列表（JSON格式）
  file_list: string
}

// 垫付管理查询
export function page(params: RescueFundStatusVo): Promise<ResponseStruct<RescueFundStatusVo[]>> {
  return useHttp().get('/admin/rescuefund/rescue_fund_status/list', { params })
}

// 垫付管理新增
export function create(data: RescueFundStatusVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/rescuefund/rescue_fund_status', data)
}

// 垫付管理编辑
export function save(id: number, data: RescueFundStatusVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/rescuefund/rescue_fund_status/${id}`, data)
}

// 垫付管理删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/rescuefund/rescue_fund_status', { data: ids })
}