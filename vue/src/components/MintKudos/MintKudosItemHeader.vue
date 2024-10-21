<template>
    <div v-if="!props.kudos.current_user_is_gifted" class="d-flex align-center flex-wrap">
        <div class="photo-container">
            <img
                v-if="props.kudos.employee.photo"
                :src="`legacy/index.php?entryPoint=download&type=Users&id=${props.kudos.employee.id}_photo`"
            />
            <v-icon v-else icon="mdi-account" />
        </div>
        <p v-if="!props.kudos.current_user_is_author">
            <span
                @click="store.openEmployeeDetailView(props.kudos.employee.id)"
                :class="{
                    'text-secondary': true,
                    'font-weight-bold': true,
                    'ml-1': props.kudos.employee.photo,
                    user: true,
                }"
                >{{ props.kudos.employee.full_name }}</span
            >, kudos!
        </p>
        <div v-else class="d-flex">
            <p>
                <span
                    @click="store.openEmployeeDetailView(props.kudos.employee.id)"
                    class="text-secondary font-weight-bold mx-1 user"
                    v-html="props.kudos.employee.full_name"
                />
                <span v-html="languages.label('LBL_USER_RECEIVED_KUDOS')" />
            </p>
        </div>
        <MintButton v-if="canEdit" @click="editKudos" icon="mdi-pencil" size="small" variant="nav" class="ml-auto" />
    </div>
    <p v-else v-html="`${languages.label('LBL_YOU_RECEIVED_KUDOS')} 🎉`" class="ml-1" />
</template>

<script setup lang="ts">
import MintButton from '../MintButtons/MintButton.vue'
import { useMintKudosStore } from '@/components/MintKudos/MintKudosStore'
import { useLanguagesStore } from '@/store/languages'
import { computed } from 'vue'

const store = useMintKudosStore()
const languages = useLanguagesStore()

const props = defineProps(['kudos'])
const canEdit = computed(
    () => !props.kudos.announcement_date && (props.kudos.current_user_is_author || props.kudos.current_user_is_admin),
)

function editKudos() {
    store.activeView = 'form'
    store.form.user.first_name = props.kudos.employee.first_name
    store.form.user.full_name = props.kudos.employee.full_name
    store.form.user.id = props.kudos.employee.id
    store.form.text = props.kudos.description
    store.form.user = props.kudos.employee
    store.form.kudosId = props.kudos.id
}
</script>

<style scoped lang="scss">
.photo-container {
    width: 32px;
    height: 32px;
    & i {
        font-size: 32px;
    }
    &-small {
        width: 18px;
        height: 18px;
        display: flex;
        justify-content: center;
        i {
            font-size: 18px;
        }
    }
}

img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 50%;
}
.user {
    cursor: pointer;
}
</style>
