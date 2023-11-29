<script setup>
import GoogleIcon from '@/Components/GoogleIcon.vue';

const props = defineProps({
  auth: String,
  error: String
});

const showDismissibleAlert = false;

</script>

<template>
  <div class="w-100 position-fixed p-2" v-if="props.error !== null">
    <div class="alert alert-danger" role="alert">
      {{ props.error }}
    </div>
  </div>

  <div class="auth-block w-100 d-flex align-items-center justify-content-center">
    <a class="btn btn-outline-dark text-decoration-none d-flex align-items-center" :href="props.auth">
      <GoogleIcon />
      Auth using Google
    </a>
  </div>
</template>

<style>
  .auth-block {
    height: 100vh;
  }
</style>
