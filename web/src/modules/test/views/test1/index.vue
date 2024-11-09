<template>
  <el-container>
    <el-header>
      <h1>User Information</h1>
    </el-header>
    <el-main>
      <el-form :model="user" ref="userForm" label-width="120px">
        <el-form-item label="Name" prop="name" :rules="[{ required: true, message: 'Please input your name', trigger: 'blur' }]">
          <el-input v-model="user.name"></el-input>
        </el-form-item>

        <el-form-item label="Email" prop="email" :rules="[{ required: true, message: 'Please input your email', trigger: 'blur' }]">
          <el-input v-model="user.email"></el-input>
        </el-form-item>

        <el-form-item label="Age" prop="age" :rules="[{ required: true, message: 'Please input your age', trigger: 'blur' }]">
          <el-input-number v-model="user.age" :min="1"></el-input-number>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="submitForm">Submit</el-button>
        </el-form-item>
      </el-form>

      <el-divider></el-divider>

      <h2>User Data:</h2>
      <pre>{{ user }}</pre>
    </el-main>
  </el-container>
</template>

<script setup>
import { ref } from 'vue';

const user = ref({
  name: '',
  email: '',
  age: null,
});

const submitForm = () => {
  console.log('User Info:', user.value);
  // 可以添加提交到服务器的逻辑
};
</script>

<style>

</style>
