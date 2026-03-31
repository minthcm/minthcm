<template>
    <div>
        <div v-if="isLoading" class="loading-screen">
            <v-progress-circular color="primary" indeterminate></v-progress-circular>
        </div>
        <div v-if="isError" class="error-message">
            <p class="text-red-lighten-1 text-center">
                {{ languages.label('LBL_ESLIST_ERROR_MASSUPDATE') }}
            </p>
        </div>
        <div v-if="store.defs && !isLoading">
            <div v-if="massUpdateRows?.length" class="fields-rows">
                <ListViewMassUpdateRow 
                v-for="(row, index) in massUpdateRows" 
                :key="row" 
                :index="index" 
                :row="row"
                v-model:field="row.field"
                @update:field="(newValue) => (row.field = newValue)"
                v-model:inputs="row.inputs"
                @update:inputs="(newValue) => (row.inputs = newValue)"
                />
            </div>
        </div>
        <div class="list-mass-update">
            <MintButton
                variant="regular"
                icon="mdi-plus"
                :text="languages.label('LBL_ESLIST_ADD_MASSUPDATE_ROW')"
                @click="store.addMassUpdateRow"
                :disabled="isLoading"
            />
            <div class="main-buttons">
                <MintButton 
                    icon="mdi-close"
                    :text="languages.label('LBL_ESLIST_CANCEL')"
                    variant="regular"
                    @click="handleClose()"
                    :disabled="isLoading"
                />
                <MintButton 
                    icon="mdi-check"
                    :text="languages.label('LBL_ESLIST_SAVE')"
                    variant="primary"
                    @click="handleSave()"
                    :disabled="store.massUpdateRows.length === 0 || isLoading"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { useListViewStore } from './ListViewStore'
    import { useLanguagesStore } from '@/store/languages'
    import { ref } from 'vue'
    import MintButton from '@/components/MintButtons/MintButton.vue'
    import ListViewMassUpdateRow from './ListViewMassUpdateRow.vue'
    import { storeToRefs } from 'pinia'
    import { Update } from '@/business/MassActions/Actions/Update'

    const store = useListViewStore()
    const languages = useLanguagesStore()

    const isLoading = ref(false)
    const isError = ref(false)
    
    const { massUpdateRows, module, selected } = storeToRefs(useListViewStore())

    const handleClose = () => {
        store.setMassUpdate(false)
    }

    const handleSave = async () => {
        isLoading.value = true
        isError.value = false
        const result = await (new Update(module.value, selected.value)).executeMassUpdate()
        isLoading.value = false
        if (result.data.success) {
            store.setMassUpdate(false)
            store.getData()
        } else {
            isError.value = true
        }
    }
</script>

<style scoped lang="scss">
.list-mass-update {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    justify-content: space-between;
}

.main-buttons {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    justify-content: right;
}

.loading-screen {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
}

.fields-rows {
    display: flex;
    flex-direction: column;
}
</style>