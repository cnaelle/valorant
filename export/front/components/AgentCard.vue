<template>
    <div>
        <div id="name" class="text-gray-100 text-2xl valorant text-center my-4">{{ data.displayName }}</div>
        <img id="image" class="w-30 mx-auto" :src="data.fullPortrait">
        <div id="description" class="text-gray-100 p-4">
            {{ data.description }}
        </div>
        <n-button strong secondary type="error" class="mx-25 text-gray-100">
            Voir les sorts de {{ data.displayName }}
        </n-button>
    </div>
    <n-modal v-model:show="showModal">
        <n-card style="width: 600px" preset="card" :bordered="false" size="huge" role="dialog" aria-modal="true"
            :close-on-esc="true">
            <div class="grid-cols-3 grid-rows-2">
                <div class="invert w-20">
                    <img :src="data.abilities[0].displayIcon">
                </div>
                <div class="pb-1 mx-auto valorant">
                    {{ data.abilities[0].displayName }}
                </div>
                <div class="pb-5 mx-auto">
                    {{ data.abilities[0].description }}
                </div>
            </div>
            <div>
                <div class="invert w-20">
                    <img :src="data.abilities[1].displayIcon">
                </div>
                <div class="pb-1 mx-auto valorant">
                    {{ data.abilities[1].displayName }}
                </div>
                <div class="pb-5 mx-auto">
                    {{ data.abilities[1].description }}
                </div>
            </div>
            <div>
                <div class="invert w-20">
                    <img :src="data.abilities[2].displayIcon">
                </div>
                <div class="pb-1 mx-auto valorant">
                    {{ data.abilities[2].displayName }}
                </div>
                <div class="pb-5 mx-auto">
                    {{ data.abilities[2].description }}
                </div>
            </div>
            <div>
                <div class="invert w-20">
                    <img :src="data.abilities[3].displayIcon">
                </div>
                <div class="pb-1 mx-auto valorant">
                    {{ data.abilities[3].displayName }}
                </div>
                <div class="pb-5 mx-auto">
                    {{ data.abilities[3].description }}
                </div>
            </div>
        </n-card>
    </n-modal>
</template>

<script setup>
const props = defineProps({
    data: Object
})

const showModal = ref(false)

defineExpose({
    showModal
})

</script>