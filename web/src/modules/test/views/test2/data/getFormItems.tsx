/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://github.com/mineadmin
 */
import type { MaFormItem } from '@mineadmin/form'
import type { RoleVo } from '~/base/api/role.ts'
import MaDictRadio from '@/components/ma-dict-picker/ma-dict-radio.vue'

import { h } from 'vue';
import { ElSelect, ElOption } from 'element-plus';
import { ElCheckboxGroup, ElCheckbox } from 'element-plus';


export default function getFormItems(formType: 'add' | 'edit' = 'add', t: any, model: RoleVo): MaFormItem[] {
  if (formType === 'add') {
    model.status = 1
    model.sort = 0
    model.accident_type = '简易程序'
  }

  const options = [
    { label: '简易程序', value: '简易程序' },
    // { label: '复杂程序', value: '复杂程序' },
  ];

  // // 动态获取复选框选项
  // const fetchCheckboxOptions = async () => {
  //   try {
  //     const response = await fetch('/api/checkbox-options'); // 将 `/api/checkbox-options` 替换为实际的 API 地址
  //     const data = await response.json();
  //     options.value = data.map((item: any) => ({
  //       label: item.label,
  //       value: item.value,
  //     }));
  //   } catch (error) {
  //     console.error('Error fetching checkbox options:', error);
  //   }
  // };

  // 在组件加载时调用获取数据的函数
  // onMounted(fetchCheckboxOptions);

  // fetchCheckboxOptions()

  const checkboxOptions = [
    { label: t('baseRoleManage.view'), value: 'view' },
    { label: t('baseRoleManage.edit'), value: 'edit' },
    { label: t('baseRoleManage.delete'), value: 'delete' },
  ];

  return [
    {
      label: () => '受案编号',
      prop: 'accident_number',
      render: 'input',
      renderProps: {
        placeholder: '受案编号',
      },
      itemProps: {
        rules: [{ required: true, message: '受案编号' }],
      },
      hide:formType != 'add'
    },
    {
      label: () => '事故时间',
      prop: 'event_date',
      render: 'DatePicker',
      renderProps: {
        type: 'datetime', // 日期时间选择
        placeholder: '事故时间',
        format: 'YYYY-MM-DD HH:mm:ss',       // 设置显示格式
        valueFormat: 'YYYY-MM-DD HH:mm:ss',  // 设置绑定的值格式
        style: { width: '100%' },
      },
      itemProps: {
        rules: [{ required: true, message: '事故时间' }],
      },
    },
    {
      label: () => '天气情况',
      prop: 'weather',
      render: 'input',
      renderProps: {
        placeholder: '天气情况',
      },
      itemProps: {
        rules: [{ required: true, message: '天气情况' }],
      },
    },
    {
      label: () => '事故地点',
      prop: 'location',
      render: 'input',
      renderProps: {
        placeholder: '事故地点',
      },
      itemProps: {
        rules: [{ required: true, message: '事故地点' }],
      },
    },
    {
      label: () => '事故情形',
      prop: 'accident_scenario',
      render: 'input',
      renderProps: {
        placeholder: '事故情形',
      },
      itemProps: {
        rules: [{ required: true, message: '事故情形' }],
      },
    },
    {
      label: () => '数据来源',
      prop: 'data_source',
      render: 'input',
      renderProps: {
        placeholder: '数据来源',
      },
      itemProps: {
        rules: [{ required: true, message: '数据来源' }],
      },
    },
    {
      label: () => '处理方式',
      prop: 'handling_method',
      render: 'input',
      renderProps: {
        placeholder: '处理方式',
      },
      itemProps: {
        rules: [{ required: true, message: '处理方式' }],
      },
    },
    {
      label: () => '管理部门',
      prop: 'management_department',
      render: 'input',
      renderProps: {
        placeholder: '管理部门',
      },
      itemProps: {
        rules: [{ required: true, message: '管理部门' }],
      },
    },
    // {
    //   label: '事故类型',
    //   prop: 'accident_type',
    //   render: () => (
    //     <el-checkbox-group v-model={model.accident_type} >
    //       {options.map(option => (
    //         <el-checkbox key={option.value} label={option.label} value={option.value} />
    //       ))}
    //     </el-checkbox-group>
    //   ),
    //   itemProps: {
    //     rules: [{ required: true, message: t('form.requiredSelect', { msg: t('baseRoleManage.permissions') }) }],
    //   },
    // }
    {
      label: '事故类型',
      prop: 'accident_type',
      cols: { md: 12, xs: 24 },
      render: () => MaDictRadio,
      renderProps: {
        renderMode: 'normal',
        data:options
      },
      itemProps: {
        rules: [{ required: true}],
      },
    },
    // {
    //   label: '事故类型',
    //   prop: 'accident_type',
    //   render: () => (
    //     <el-radio-group v-model={model.accident_type} >
    //       {options.map(option => (
    //         <el-radio key={option.value} label={option.label} value={option.value} />
    //       ))}
    //     </el-radio-group>
    //   ),
    //   itemProps: {
    //     rules: [{ required: true, message: '事故类型' }],
    //   },
    // }
    //
    // {
    //   label: () => t('baseRoleManage.code'),
    //   prop: 'code',
    //   render: 'input',
    //   renderProps: {
    //     placeholder: t('form.pleaseInput', { msg: t('baseRoleManage.code') }),
    //   },
    //   itemProps: {
    //     rules: [{ required: true, message: t('form.requiredInput', { msg: t('baseRoleManage.code') }) }],
    //   },
    // },
    // {
    //   label: () => t('crud.sort'),
    //   prop: 'sort',
    //   render: 'inputNumber',
    //   cols: { md: 12, xs: 24 },
    //   renderProps: {
    //     placeholder: t('form.pleaseInput', { msg: t('crud.sort') }),
    //     class: 'w-full',
    //   },
    // },
    // {
    //   label: () => t('crud.status'),
    //   prop: 'status',
    //   render: () => MaDictRadio,
    //   cols: { md: 12, xs: 24 },
    //   renderProps: {
    //     placeholder: t('form.pleaseInput', { msg: t('crud.status') }),
    //     dictName: 'system-status',
    //   },
    // },
    // {
    //   label: () => t('baseRoleManage.roleType'),
    //   prop: 'roleType',
    //   render: () =>
    //     h(
    //       ElSelect,
    //       {
    //         placeholder: t('form.pleaseSelect', { msg: t('baseRoleManage.roleType') }),
    //         modelValue: model.roleType,
    //         'onUpdate:modelValue': (value: string) => (model.roleType = value),
    //       },
    //       {
    //         default: () =>
    //           options.map(option =>
    //             h(ElOption, { label: option.label, value: option.value })
    //           ),
    //       }
    //     ),
    //   itemProps: {
    //     rules: [{ required: true, message: t('form.requiredSelect', { msg: t('baseRoleManage.roleType') }) }],
    //   },
    // },
    // {
    //   label: () => t('baseRoleManage.permissions'),
    //   prop: 'permissions',
    //   render: () => (
    //     <el-checkbox-group>
    //       <el-checkbox label={t('baseRoleManage.view')} value="view" />
    //       <el-checkbox label={t('baseRoleManage.edit')} value="edit" />
    //       <el-checkbox label={t('baseRoleManage.delete')} value="delete" />
    //     </el-checkbox-group>
    //   ),
    //   itemProps: {
    //     rules: [{ required: true, message: t('form.requiredSelect', { msg: t('baseRoleManage.permissions') }) }],
    //   },
    // },
    // {
    //   label: () => t('baseMenuManage.type'), prop: 'meta.type', render: () => (
    //     <el-radio-group>
    //       <el-radio-button label={t('baseMenuManage.typeItem.M')} value="M"></el-radio-button>
    //       <el-radio-button label={t('baseMenuManage.typeItem.B')} value="B"></el-radio-button>
    //       <el-radio-button label={t('baseMenuManage.typeItem.L')} value="L"></el-radio-button>
    //       <el-radio-button label={t('baseMenuManage.typeItem.I')} value="I"></el-radio-button>
    //     </el-radio-group>
    //   ),
    //   cols: { lg: 12, md: 24 },
    // },
    // {
    //   label: () => t('baseRoleManage.permissions'),
    //   prop: 'permissions',
    //   render: () =>
    //     h(
    //       ElCheckboxGroup,
    //       {
    //         modelValue: model.permissions,
    //         'onUpdate:modelValue': (value: string[]) => (model.permissions = value),
    //       },
    //       {
    //         default: () =>
    //           checkboxOptions.map(option =>
    //             h(ElCheckbox, { label: option.label, value: option.value })
    //           ),
    //       }
    //     ),
    //   itemProps: {
    //     rules: [{ required: true, message: t('form.requiredSelect', { msg: t('baseRoleManage.permissions') }) }],
    //   },
    // },
  ]
}
