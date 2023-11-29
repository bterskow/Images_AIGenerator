<script setup>
import Loader from '@/Components/Loader.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import axios from 'axios';

const props = defineProps({
  delete_image_url: String,
  images: Array
});

var currentImage = 0;

const slider = () => {
  var imageTag = document.querySelector('img');
  imageTag.setAttribute('src', props.images[currentImage]);
}

const openImage = () => {
  var href = props.images[currentImage];
  window.open(href);
}

const prevPage = () => {
  currentImage !== 0 ? (currentImage = currentImage - 1) : (currentImage = props.images.length - 1);
  slider();
};

const nextPage = () => {
  currentImage !== props.images.length - 1 ? (currentImage = currentImage + 1) : (currentImage = 0);
  slider();
};

const loading = () => {
  const spinner = document.querySelector('.spinner-block');
  const main = document.querySelector('.main-block');

  spinner.classList.toggle('d-none');
  spinner.classList.toggle('d-flex');
  main.classList.toggle('d-block');
  main.classList.toggle('d-none');
};

const deleteImage = async () => {
  loading();

  try {
    const url = `${props.delete_image_url}/${currentImage}`;
    const request = await axios.delete(url);
    const status = request['status'];
    const message = request['message'];

    if(status === 200) {
      return window.location.reload();
    } else {
      alert(message);
    }
  } catch (error) {
    console.error(error);
    alert('Something went wrong!');
  }

  return loading();
}
</script>

<template>
  <div>
    <AuthenticatedLayout />
    <div class="container main-block d-block">
      <div class="w-100 d-flex align-items-center justify-content-center">
        <div v-if="props.images.length !== 0" class="d-flex align-items-center justify-content-center">
          <button class="btn btn-outline-dark" @click.prevent="prevPage">Prev</button>
          <div class="images-carousel mx-4">
            <img :src="props.images[currentImage]" @click.prevent="openImage" title="OPEN IMAGE"/>
            <div class="w-100 d-flex align-items-center justify-content-center mt-3">
              <button class="btn btn-danger" @click.prevent="deleteImage">🗑</button>
            </div>
          </div>
          <button class="btn btn-outline-dark" @click.prevent="nextPage">Next</button>
        </div>

        <div v-else>
          <h3>No images!</h3>
        </div>
      </div>
    </div>

    <Loader />
  </div>
</template>

<style>
  img {
    height: 50vh;
    cursor: pointer;
  }
</style>
