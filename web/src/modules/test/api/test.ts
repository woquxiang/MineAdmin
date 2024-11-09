/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { ResponseStruct } from '#/global'
import useHttp from '@/hooks/auto-imports/useHttp.ts'
import {RoleVo} from "~/base/api/role";

export interface J123EventVo {
  id:number,
  accident_number:string
  event_date:string
  weather:string
  location:string
  accident_scenario:string
  accident_type:string
  data_source:string
  handling_method:string
  management_department:string,
  accident_status:string,
  //
  //
  // id: number // 主键
  // username: string
  // ip: string
  // os: string
  // browser: string
  // status: number // 登录状态 (1成功 2失败)
  // message: string
  // login_time: string
  // remark: string
}

export class J123Event {
  public static page(params: J123EventVo): Promise<ResponseStruct<J123EventVo[]>> {
    return useHttp().get('/admin/test/first', { params })
  }

  public static delete(ids: number[]): Promise<ResponseStruct<null>> {
    return useHttp().delete('/admin/test/delete', { data: { ids } })
  }

  public static create(data: J123EventVo): Promise<ResponseStruct<null>> {
    return useHttp().post('/admin/test/create', data)
  }

  public static save(id: number, data: J123EventVo): Promise<ResponseStruct<null>> {
    return useHttp().put(`/admin/test/${id}`, data)
  }
}

