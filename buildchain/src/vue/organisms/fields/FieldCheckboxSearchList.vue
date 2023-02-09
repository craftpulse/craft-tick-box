<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'

interface Props {
                options: any,
                translations: any
}

const props = defineProps<Props>()

const selectAll = ref(false)
const search = ref('')
const checkboxes = ref(props.options)
const checked = ref([])

const toggle = () => {
    selectAll.value != selectAll.value

    if (selectAll.value) {
        checked.value = checkboxes.value
    } else {
        checked.value = []
    }

    checkboxes.value = checkboxes.value.map(option => {
        option.checked = selectAll.value
        return option
    })
}

const id = computed(() => Math.floor(Date.now() / 1000))
const filteredOptions = computed(() => {
    if (search.value === '') {
        return checkboxes.value
    }

    return checkboxes.value.filter(option => option.label.toLowerCase().includes(search.value.toLowerCase()))
})

const onUpdateChecked = evt => {
    const isChecked = checked.value.find(option => option.value === evt.currentTarget.value)

    if (isChecked) {
        checked.value = checked.value.filter(option => option.value !== evt.currentTarget.value)
    } else {
        checked.value.push(props.options.find(option => option.value === evt.currentTarget.value))
    }

    if (checked.value.length === props.options.length) {
        selectAll.value = true
    } else {
        selectAll.value = false
    }
}

onMounted(() => {
    checked.value = props.options.filter(option => option.checked)

    if (checked.value.length === props.options.length) {
        selectAll.value = true
    }
})
</script>

<template>
    <section class="w-full pb-2 bg-gray-100 rounded-md">
        <div class="p-2 rounded-md">
            <input 
                v-model="search" 
                type="text" 
                class="bg-white w-full py-2 px-2 rounded-md border border-sate-200"
                :placeholder="translations['placeholder'] ?? ''"
            >
        </div>
        <div
            v-if="filteredOptions.length > 0"
            class="max-h-[400px] overflow-scroll"
        >
            <div class="px-2">
                <label class="flex items-center p-1 cursor-pointer rounded-md hover:bg-slate-200">
                    <input 
                        v-model="selectAll" 
                        type="checkbox"
                        @change="toggle"
                    >
                    <span>Select all</span>
                </label>
            </div>

            <div
                v-for="(option, i) in filteredOptions"
                :key="option.value"
                class="px-2"
            >
                <label class="flex items-center p-1 cursor-pointer rounded-md hover:bg-slate-200">
                    <!-- :checked="checked.find(checkbox => checkbox.value === option.value)" -->
                    <input
                        :id="`field-checkbox${id}-${i}`"
                        type="checkbox"
                        :name="`fields[${option.name}][]`"
                        :checked="checked.find(checkbox => checkbox.value === option.value)"
                        :value="option.value"
                        @change="onUpdateChecked"
                    >
                    <span>{{ option.label }}</span>
                </label>
            </div>
        </div>

        <div
            v-if="filteredOptions.length === 0"
            class="px-2 pt-2"
        >
            <span class="p-1">{{ translations['noResults'] ?? '-' }}</span>
        </div>
    </section>
</template>
