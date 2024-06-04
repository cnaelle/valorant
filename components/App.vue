<template>
    <div class="relative bg-white/20 backdrop-blur h-fit mx-70">
        <div class="">
            <img class="w-225" :src="data.mainImg">
        </div>
        <div class="w-90 py-10 m-15 absolute inset-y-0 right-0">
            <div class="valorant px-5 text-right text-2xl my-4 border-b-2 text-white border-r-2 border-white">
                {{ data.name }}
            </div>
            <div class="text-right my-10 text-white">
                {{ data.description }}
            </div>
            <div class="my-10 text-right">
                <n-button color="#FD4556">
                    Voir la galerie
                    <Map ref="mapSlide" :data="map"></Map>
                </n-button>
            </div>
        </div>
    </div>
    <n-modal v-model:show="showModal">
        <n-carousel direction="vertical" dot-placement="right" style="width: 915px; height:515px;">
            <img :src="data.img1" class="carousel-img">
            <img :src="data.img3" class="carousel-img">
            <img :src="data.img4" class="carousel-img">
            <img :src="data.img5" class="carousel-img">
            <img :src="data.img2" class="minimap-img">
        </n-carousel>
    </n-modal>
</template>
<style scoped>
.carousel-img {
    width: 915px;
    height: 515px;
    object-fit: cover;
}

.minimap-img {
    width: 515px;
    height: 515px;
    object-fit: cover;
    margin-left: 200px;
    margin-right: 200px;

}
</style>
<script setup>
const props = defineProps({
    data: Object
})

const showModal = ref(false)

defineExpose({
    showModal
})
</script>