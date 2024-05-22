<template>
    <n-modal v-model:show="showModal">
        <n-card style="width: 915px" preset="card" :bordered="false" size="huge" role="dialog" aria-modal="true"
            :close-on-esc="true">
            <n-carousel direction="vertical" dot-placement="right" style="width: 100%; height: 515px">
                <img :src="map.img1" class="carousel-img">
                <img :src="map.img3" class="carousel-img">
                <img :src="map.img4" class="carousel-img">
                <img :src="map.img5" class="carousel-img">
            </n-carousel>
        </n-card>
    </n-modal>
</template>
<style scoped>
.carousel-img {
    width: 100%;
    height: 515px;
    object-fit: cover;
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