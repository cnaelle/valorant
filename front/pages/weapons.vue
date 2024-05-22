<template>
    <main>
        <div class="bg-scroll bg-contain"
            style="background-image: url(https://cdn0.gamesports.net/content_teasers/100000/100652.jpg?1612533002)">
            <section id="header" class="h-40 w-full bg-white/20 backdrop-blur">
                <div id="menu" class="container mx-auto flex h-full items-center justify-between px-4">
                    <div>
                        <img class="w-30" src="https://cdn10.idcgames.com/storage/image/1101/valorant-logo-idc/default.png">
                    </div>
                    <div id="nav" class="flex gap-x-2">
                        <button v-for="weapon, index in weaponCategories" @click="filterWeaponsByCategory(weapon)"
                            class="valorant text-white h-9 w-45 bg-emerald-500/40 m-4 backdrop-blur border-b-1 border-white/80 hover:bg-white/20 hover:border-emerald-300/80 hover:duration-500">
                            {{ nameCategories[index] }}
                        </button>
                    </div>
                </div>
            </section>
            <div class="pt-10">
                <router-link :to="{ path: '/' }">
                    <n-space justify="center">
                        <n-button strong secondary class="text-gray-100 text-4xl valorant animate-bounce">
                            <img class="w-10 invert"
                                src="https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-up-01-512.png">
                        </n-button>
                    </n-space>
                </router-link>
            </div>
            <div v-if="weaponToDisplay.length" id="cards"
                class="grid grid-cols-3 grid-rows-7 gap-4 px-auto justify-items-center h-full py-10 container mx-auto">
                <div id="card" v-for="(weapon, index) in weaponToDisplay" @click="showSpells(index)"
                    class="bg-white/20 py-8 w-96 h-fit m-10 backdrop-blur my-auto hover:bg-emerald-300/30 hover:border-2 hover:border-emerald-300/80 hover:duration-400">
                    <WeaponCard ref="weaponCard" :data="weapon" />
                </div>
            </div>
        </div>
    </main>
</template>
  
<script setup>
import WeaponCard from "/components/WeaponCard.vue"
import { onMounted } from 'vue'

const { data: weapons } = await useAsyncData(() => $fetch('https://valorant-api.com/v1/weapons'), {
    transform: (res) => res.data
})

useHead({
    title: 'VALORANT - Armes'
})

const weaponCard = ref(null)

function showSpells(index) {
    weaponCard.value[index].showModal = true
    console.log(weaponCard.value[index])
}

onMounted(() => {
    console.log(weaponCard.value)
})

const weaponToDisplay = ref(weapons.value)

function filterWeaponsByCategory(category) {
    weaponToDisplay.value = weapons.value.filter(weapon => weapon.category === category)
}

const weaponCategories = ref([])
const nameCategories = ref([])

function createWeaponCategories() {
    //on recupere l'url des categories dans weaponCategories
    weapons.value.forEach(weapon => {
        weaponCategories.value.push(weapon.category)
    })
    weaponCategories.value = [...new Set(weaponCategories.value)]
    var strSplit = [];
    //on remplit le tableau nameCategories
    weaponCategories.value.forEach(name => {
        strSplit = name.split("::");
        nameCategories.value.push(strSplit[1])
    })
    console.log(nameCategories)
    console.log(weaponCategories)
}
createWeaponCategories()
</script>