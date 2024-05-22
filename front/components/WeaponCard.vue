<template>
    <div>
        <img v-if="data?.shopData?.newImage" id="image" class="w-30 mx-auto" :src="data?.shopData?.newImage">
        <img v-else class="w-30 mx-auto" :src="data?.killStreamIcon">
        <div v-if="data?.shopData?.cost" id="cost" class="text-gray-100 p-4">
            {{ data?.shopData?.cost }} ¤
        </div>
        <div id="name" class="text-gray-100 p-4 valorant">{{ data.displayName }}</div>
        <n-space justify="center">
            <n-button v-if="data.weaponStats" strong secondary class="text-gray-100">
                Voir les caracteristiques de {{ data.displayName }}
            </n-button>
        </n-space>

    </div>
    <n-modal v-model:show="showModal">
        <n-card style="width: 600px" preset="card" :bordered="false" size="large" role="dialog" aria-modal="true"
            :close-on-esc="true">
            <n-table :bordered="false" :single-line="false" size="large">
                <thead>
                    <tr>
                        <th class="bg-cyan-500">Range</th>
                        <th>Body Part</th>
                        <th>Default Damage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ data?.weaponStats?.damageRanges[0].rangeStartMeters }} - {{
                            data?.weaponStats?.damageRanges[0].rangeEndMeters }}
                        </td>
                        <td>Head<br>Body<br>Legs</td>
                        <td>{{ data?.weaponStats?.damageRanges[0].headDamage }}<br>{{
                            data?.weaponStats?.damageRanges[0].bodyDamage
                        }}<br>{{
    data?.weaponStats?.damageRanges[0].legDamage }}</td>
                    </tr>
                    <tr v-if="data.weaponStats.damageRanges.length > 1">
                        <td>{{ data?.weaponStats?.damageRanges[1].rangeStartMeters }} - {{
                            data?.weaponStats?.damageRanges[1].rangeEndMeters }}
                        </td>
                        <td>Head<br>Body<br>Legs</td>
                        <td>{{ data?.weaponStats?.damageRanges[1].headDamage }}<br>{{
                            data?.weaponStats?.damageRanges[1].bodyDamage }}<br>{{
        data?.weaponStats?.damageRanges[1].legDamage }}</td>
                    </tr>
                    <tr v-if="data.weaponStats.damageRanges.length > 2">
                        <td>{{ data?.weaponStats?.damageRanges[2].rangeStartMeters }} - {{
                            data?.weaponStats?.damageRanges[2].rangeEndMeters }}
                        </td>
                        <td>Head<br>Body<br>Legs</td>
                        <td>{{ data?.weaponStats?.damageRanges[2].headDamage }}<br>{{
                            data?.weaponStats?.damageRanges[2].bodyDamage }}<br>{{
        data?.weaponStats?.damageRanges[2].legDamage }}</td>
                    </tr>
                </tbody>
            </n-table>
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