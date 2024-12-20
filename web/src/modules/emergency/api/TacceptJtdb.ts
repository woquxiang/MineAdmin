import type { ResponseStruct } from '#/global'

export interface TacceptJtdbVo {
  // 自增ID，唯一标识每条记录
  id: string
  // 急救事件唯一标识（关联急救事件数据事件编码字段）
  sjbm: string
  // 呼救电话
  hjdj: string
  // 事发地址
  xcdz: string
  // 患者病情描述
  zs: string
  // 患者姓名
  hzxm: string
  // 患者性别
  xb: string
  // 患者年龄
  nl: string
  // 患者民族
  mz: string
  // 患者国籍
  gj: string
  // 联系人
  lxr: string
  // 患者联系方式
  lxdh: string
  // 送往地点
  swdd: string
  // 车牌号码
  cphm: string
  // 随车电话
  scdh: string
  // 司机电话
  sjhm: string
  // 医生电话
  yshm: string
  // 护士电话
  hshm: string
  // 更新时间
  gxsj: string
}

// 车辆任务查询
export function page(params: TacceptJtdbVo): Promise<ResponseStruct<TacceptJtdbVo[]>> {
  return useHttp().get('/admin/emergency/taccept_jtdb/list', { params })
}

// 车辆任务新增
export function create(data: TacceptJtdbVo): Promise<ResponseStruct<null>> {
  return useHttp().post('/admin/emergency/taccept_jtdb', data)
}

// 车辆任务编辑
export function save(id: number, data: TacceptJtdbVo): Promise<ResponseStruct<null>> {
  return useHttp().put(`/admin/emergency/taccept_jtdb/${id}`, data)
}

// 车辆任务删除
export function deleteByIds(ids: number[]): Promise<ResponseStruct<null>> {
  return useHttp().delete('/admin/emergency/taccept_jtdb', { data: ids })
}