<script setup>
import Loader from '@/Components/Loader.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import axios from 'axios';

const props = defineProps({
  generate_image_url: String
});

const form = {
  prompt: null
}

const loading = () => {
  const spinner = document.querySelector('.spinner-block');
  const main = document.querySelector('.main-block');

  spinner.classList.toggle('d-none');
  spinner.classList.toggle('d-flex');
  main.classList.toggle('d-block');
  main.classList.toggle('d-none');
};

const submit = async () => {
  const prompt = form.prompt;

  if(prompt !== null && prompt.replaceAll(' ', '').length !== 0) {
    loading();

    try {
      const request = await axios.get(`${props.generate_image_url}/${prompt}`);
      const response = await request.data;
      const message = response.message;
      const status = response.status;

      if(status === 200) {
        window.open(message)
      } else {
        alert(message);
      }
    } catch(error) {
      console.error(error);
      alert('Something went wrong!');
    }

    return loading();
  }

  return alert('You have to write prompt before generate image!');
}
</script>

<template>
    <AuthenticatedLayout />
    <div class="container main-block d-block">
      <div class="w-100 d-flex align-items-center justify-content-center">
        <div class="form p-2 border border-rounded shadow">
          <input class="form-control" type="text" v-model="form.prompt">
          <button class="btn btn-primary w-100 my-2" @click.prevent="submit">Generate!</button>
        </div>
      </div>
    </div>

    <Loader />
</template>

