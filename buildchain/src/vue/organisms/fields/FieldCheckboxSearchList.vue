<script setup lang="ts">
import { computed, ref } from 'vue'

interface Props {
    options: any
}

const props = defineProps<Props>()

const selectAll = ref(false)
const search = ref('')

const toggle = () => {
    selectAll.value != selectAll.value
}

const id = computed(() => Math.floor(Date.now() / 1000))
const filteredOptions = computed(() => {
    if (search.value === '') {
        return props.options
    }

    return props.options.filter(option => option.label.toLowerCase().includes(search.value.toLowerCase()))
})
</script>

<template>
    <section class="w-full pb-2 bg-gray-100 rounded-md">
        <div class="p-2 rounded-md">
            <input 
                type="text" 
                class="bg-white w-full py-2 px-2 rounded-md border border-sate-200" 
                placeholder="Search for an option"
                v-model="search"
            >
        </div>
        <div>
            <div class="px-2">
                <label class="flex items-center py-1 px-1 cursor-pointer rounded-md hover:bg-slate-200">
                    <input 
                        type="checkbox" 
                        @change="toggle"
                        v-model="selectAll"
                    >
                    <span>Select all</span>
                </label>
            </div>

            <div
                v-for="(option, i) in filteredOptions"
                :key="option.value"
                class="px-2"
            >
                <label class="flex items-center py-1 px-1 cursor-pointer rounded-md hover:bg-slate-200">
                    <input
                        type="checkbox"
                        :id="`field-checkbox${id}-${i}`"
                        :name="`fields[${option.name}]`"
                        v-bind:checked="selectAll || option.checked"
                        :value="option.value"
                    >
                    <span>{{ option.label }}</span>
                </label>
            </div>
        </div>
    </section>
</template>
