<template>
    <div v-if="!props.kudos.current_user_is_gifted" class="d-flex align-center flex-wrap">
        <MintAvatar
            :photo="props.kudos.employee.photo ? props.kudos.employee.id + '_photo' : null"
        />
        <p v-if="!props.kudos.current_user_is_author">
            <span
                @click="store.openEmployeeDetailView(props.kudos.employee.id)"
                :class="{
                    'text-secondary': true,
                    'font-weight-bold': true,
                    'ml-1': props.kudos.employee.photo,
                    user: true,
                }"
                >{{ employeeName }}</span
            >, kudos!
        </p>
        <div v-else class="d-flex">
            <p>
                <span
                    @click="store.openEmployeeDetailView(props.kudos.employee.id)"
                    class="text-secondary font-weight-bold mx-1 user"
                    >{{ employeeName }}</span
                >
                <span v-html="languages.label('LBL_USER_RECEIVED_KUDOS')" />
            </p>
        </div>
        <MintButton v-if="canEdit" @click="editKudos" icon="mdi-pencil" size="small" variant="nav" class="ml-auto" />
    </div>
    <div class="d-flex justify-space-between" v-else>
        <p v-html="`${languages.label('LBL_YOU_RECEIVED_KUDOS')} 🎉`" class="ml-1" />
        <v-fade-transition>
            <MintUnreadDot v-show="showDot" />
        </v-fade-transition>
    </div>
</template>

<script setup lang="ts">
import MintButton from '../MintButtons/MintButton.vue'
import { useMintKudosStore } from '@/components/MintKudos/MintKudosStore'
import { useLanguagesStore } from '@/store/languages'
import { computed } from 'vue'
import MintUnreadDot from '../MintUnreadDot.vue'
import MintAvatar from '../MintAvatar.vue'
import { decodeHtmlEntities } from '@/utils/html'

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

const employeeName = computed(() => decodeHtmlEntities(props.kudos.employee.full_name))
const showDot = computed(() => props.kudos.is_read == 0)
</script>

<style scoped lang="scss">
.user {
    cursor: pointer;
}
</style>
