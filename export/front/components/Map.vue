<template>
    <swiper-slide>
        <img id="image" class="w-30 mx-auto" :src="data.img1" />
        <div id="description">{{ data.description }}</div>
    </swiper-slide>
    <n-modal v-model:show="showModal">
        <n-card style="width: 915px" preset="card" :bordered="false" size="huge" role="dialog" aria-modal="true"
            :close-on-esc="true">
            <swiper-slide><img :src="data.img1"></swiper-slide>
            <swiper-slide><img :src="data.img2"></swiper-slide>
            <swiper-slide><img :src="data.img3"></swiper-slide>
            <swiper-slide><img :src="data.img4"></swiper-slide>
            <swiper-slide><img :src="data.img5"></swiper-slide>
        </n-card>
    </n-modal>
</template>


<script setup>
import { ref } from 'vue';
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';

import 'swiper/css/pagination';
import 'swiper/css/navigation';

import '.\style.css';

// import required modules
import { Autoplay, Pagination, Navigation } from 'swiper';
const props = defineProps({
    data: Object
})

const showModal = ref(false)

defineExpose({
    showModal
})


</script>