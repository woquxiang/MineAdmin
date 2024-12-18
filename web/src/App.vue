<!--
 - MineAdmin is committed to providing solutions for quickly building web applications
 - Please view the LICENSE file that was distributed with this source code,
 - For the full copyright and license information.
 - Thank you very much for using MineAdmin.
 -
 - @Author X.Mo<root@imoi.cn>
 - @Link   https://github.com/mineadmin
-->
<script setup lang="ts">
import MineProviderPage from '@/layouts/provider.tsx'

import { onMounted } from 'vue';

onMounted(() => {
  func()
});

const func = ()=>{
  // 判断当前页面是否在 iframe 中嵌套
  if (window.self !== window.top) {

    /***
    // 如果是嵌套的，检查 referrer 是否来自允许的域名
    const referrer = document.referrer;
    if (referrer.indexOf('your-allowed-domain.com') === -1) {
      // 如果 referrer 不包含允许的域名，重定向或禁止访问
      window.alert('非法访问2！');
      window.location.href = 'https://yct.ycjjwx.com'; // 重定向到安全页面
    }

     **/
  } else {
    // 如果不是嵌套页面，直接拒绝访问
    window.alert('非法访问1！');
    window.location.href = 'https://yct.ycjjwx.com'; // 重定向到安全页面
  }
}

</script>

<template>
  <MineProviderPage>
    <RouterView />
  </MineProviderPage>
</template>
