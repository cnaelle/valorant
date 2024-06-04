<template>
    <main>
      <div style="background-image: url(https://wallpapers.com/images/featured/305kescxw5dpup7y.jpg)">
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
              <button v-for="category, index in agentCategories" class="text-white valorant" @click="afficher(category)">
                <img :src="icons[index]" class="w-10 mx-auto pb-3">
                {{ category }}
              </button>
              <input class="bg-slate-900/0 text-white border-1 h-9 w-40 pl-2 my-auto rounded-md" id="searchBar" type="text"
                v-model="input" placeholder="Search an agent..." />
            </div>
          </div>
        </section>
        <div class="grid grid-cols-3 grid-rows-7 gap-4 px-auto justify-items-center h-max py-20 container mx-auto">
          <div id="card" v-for="(agent, index) in filteredList()" @click="showSpells(index)"
            class="rounded bg-black/40 py-8 w-96 h-fit m-10 backdrop-blur my-auto hover:bg-white/30 duration-400">
            <TestAgentCard ref="agentCard" :data="agent" />
          </div>
        </div>
      </div>
    </main>
  </template>
  
  <script setup>
  import TestAgentCard from "/components/TestAgentCard.vue"
  import { onMounted } from 'vue'
  import { ref } from "vue";
  
  let input = ref("");
  let isActive = false;
  
  function filteredList() {
    return agentsToDisplay.value.filter((agent) =>
      agent.displayName.toLowerCase().includes(input.value.toLowerCase())
    );
  }
  function afficher(category) {
    agentsToDisplay.value = agents.value.filter(agent => agent.role.displayName === category)
  }
  const index = 0
  const { data: agents } = await useAsyncData(() => $fetch('https://valorant-api.com/v1/agents', {
    params: {
      //language: "fr-FR",
      isPlayableCharacter: true
    }
  }), {
    transform: (res) => res.data,
  })
  
  useHead({
    title: 'VALORANT - Agents'
  })
  
  const agentCard = ref(null)
  
  const agentsToDisplay = ref(agents.value)
  
  function showSpells(index) {
    agentCard.value[index].showModal = true
  }
  
  const agentCategories = ref([])
  
  const icons = ref([])
  
  function createAgentCategories() {
    agents.value.forEach(agent => {
      agentCategories.value.push(agent.role?.displayName)
      icons.value.push(agent.role.displayIcon)
    })
    agentCategories.value = [...new Set(agentCategories.value)]
    icons.value = [...new Set(icons.value)]
  
  }
  
  createAgentCategories()
  
  
  </script>