<template>
    <main>
        <div class="bg-scroll bg-contain"
            style="background-image: url(https://wallpapers.com/images/featured/305kescxw5dpup7y.jpg)">
            <section id="header" class="h-40 w-full bg-black/20 backdrop-blur">
                <div id="menu" class="container mx-auto flex h-full items-center justify-between px-4">
                    <div>
                        <img class="w-20" src="https://seeklogo.com/images/V/valorant-logo-FAB2CA0E55-seeklogo.com.png">
                    </div>
                    <router-link :to="{ path: '/cartes' }">
                        <n-button strong secondary type="error" class=" text-gray-100 valorant">
                            Toutes les cartes
                        </n-button>
                    </router-link>
                    <div id="nav" class="flex gap-x-4">
                        <button @click="filterWeaponsByCategory('Sidearm')" class="text-white valorant">
                            Armes de poing
                        </button>
                        <button class="text-white valorant" @click="filterWeaponsByCategory('SMG')">
                            PM
                        </button>
                        <button class="text-white valorant" @click="filterWeaponsByCategory('Shotgun')">
                            Pompes
                        </button>
                        <button class="text-white valorant" @click="filterWeaponsByCategory('Rifle')">
                            Fusils
                        </button>
                        <button class="text-white valorant" @click="filterWeaponsByCategory('Sniper')">
                            Snipers
                        </button>
                        <button class="text-white valorant" @click="filterWeaponsByCategory('Heavy')">
                            Mitrailleuses
                        </button>
                    </div>
                </div>
            </section>
            <n-button @click="createWeaponCategories" class="text-white">ici</n-button>
            <div id="cards"
                class="grid grid-cols-3 grid-rows-7 gap-4 px-auto justify-items-center h-full py-20 container mx-auto">
                <div id="card" v-for="(weapon, index) in weaponToDisplay" @click="showSpells(index)"
                    class="rounded bg-black/40 py-8 w-96 h-fit m-10 backdrop-blur my-auto hover:bg-white/30 duration-400">
                    <span class="text-white">{{ weapon.displayName }}</span>
                </div>
            </div>
        </div>
    </main>
</template>
  
<script setup>
const { data: weapons } = await useAsyncData(() => $fetch('https://valorant-api.com/v1/weapons'), {
    transform: (result) => {
        return result.data
    }
})

const weaponToDisplay = ref(weapons.value)

function filterWeaponsByCategory(category) {
    if (weaponCategories.value.includes(category)) {
        weaponToDisplay.value = weapons.value.filter(weapon => weapon.category === category)
    }
}

const weaponCategories = ref([{}])

function createWeaponCategories() {
    weapons.value.forEach(weapon => {
        weaponCategories.value.push(weapon.category)
    })
    weaponCategories.value = [...new Set(weaponCategories.value)]
}
createWeaponCategories()

console.log(weaponCategories)

useHead({
    title: 'VALORANT - Armes'
})
</script>