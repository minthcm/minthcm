<template>
    <div v-if="props.kudos.current_user_access && props.kudos.private">
        <div v-if="!props.kudos.current_user_is_author" class="d-flex align-center flex-wrap mt-4">
            <MintAvatar
                :photo="props.kudos.assigned_user?.photo ? props.kudos.assigned_user.id + '_photo' : null"
                :width="'18px'"
                :height="'18px'"
            />
            <p
                class="text-secondary font-weight-bold ml-2 text-caption user"
                @click="store.openEmployeeDetailView(props.kudos.assigned_user.id)"
            >
                {{ assignedUserName }}
            </p>
        </div>
        <div class="mt-1 d-flex private">
            <v-icon icon="mdi-eye-off" size="medium" class="mr-2" /><span>{{ privateKudosNotification }}</span>
        </div>
    </div>
    <div v-if="!props.kudos.private">
        <div class="d-flex align-center flex-wrap mt-4">
            <MintAvatar
                :photo="props.kudos.assigned_user?.photo ? props.kudos.assigned_user.id + '_photo' : null"
                :width="'18px'"
                :height="'18px'"
            />
            <p
                class="text-secondary font-weight-bold ml-2 text-caption user"
                @click="store.openEmployeeDetailView(props.kudos.assigned_user.id)"
            >
                {{ assignedUserName }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useMintKudosStore } from '@/components/MintKudos/MintKudosStore'
import { useLanguagesStore } from '@/store/languages'
import MintAvatar from '../MintAvatar.vue'
import { decodeHtmlEntities } from '@/utils/html'

const props = defineProps(['kudos'])
const store = useMintKudosStore()
const languages = useLanguagesStore()

const assignedUserName = computed(() => decodeHtmlEntities(props.kudos.assigned_user.full_name))

const privateKudosNotification = computed(() => {
    if (props.kudos.current_user_is_gifted) {
        return languages.label('LBL_KUDOS_PRIVATE_TEXT_ONLY_YOU')
    } else {
        return languages.label('LBL_KUDOS_PRIVATE_TEXT_ON_MESSAGE', null, {
            name: decodeHtmlEntities(props.kudos.employee.first_name || props.kudos.employee.full_name),
        })
    }
})
</script>

<style scoped lang="scss">
.kudos-item {
    margin-bottom: 15px;
    padding: 12px;
    font-size: 14px;
    border-radius: 16px;
    color: #000000bc;
    background-color: #f5fbfa;
}
.private {
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    & .v-icon {
        margin-top: 2px;
    }
}
.user {
    cursor: pointer;
}
</style>
