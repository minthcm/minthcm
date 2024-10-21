<template>
    <div v-if="props.kudos.current_user_access && props.kudos.private">
        <div v-if="!props.kudos.current_user_is_author" class="d-flex align-center flex-wrap mt-4">
            <div class="photo-container-small">
                <img
                    v-if="props.kudos.assigned_user.photo"
                    :src="`legacy/index.php?entryPoint=download&type=Users&id=${props.kudos.assigned_user.id}_photo`"
                />
                <v-icon v-else icon="mdi-account" />
            </div>
            <p
                class="text-secondary font-weight-bold ml-2 text-caption user"
                @click="store.openEmployeeDetailView(props.kudos.assigned_user.id)"
            >
                {{ props.kudos.assigned_user.full_name }}
            </p>
        </div>
        <div class="mt-1 d-flex private">
            <v-icon icon="mdi-eye-off" size="medium" class="mr-2" /><span v-html="privateKudosNotification" />
        </div>
    </div>
    <div v-if="!props.kudos.private">
        <div class="d-flex align-center flex-wrap mt-4">
            <div class="photo-container-small">
                <img
                    v-if="props.kudos.assigned_user.photo"
                    :src="`legacy/index.php?entryPoint=download&type=Users&id=${props.kudos.assigned_user.id}_photo`"
                />
                <v-icon v-else icon="mdi-account" />
            </div>
            <p
                class="text-secondary font-weight-bold ml-2 text-caption user"
                @click="store.openEmployeeDetailView(props.kudos.assigned_user.id)"
            >
                {{ props.kudos.assigned_user.full_name }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useMintKudosStore } from '@/components/MintKudos/MintKudosStore'
import { useLanguagesStore } from '@/store/languages'

const props = defineProps(['kudos'])
const store = useMintKudosStore()
const languages = useLanguagesStore()

const privateKudosNotification = computed(() => {
    if (props.kudos.current_user_is_gifted) {
        return languages.label('LBL_KUDOS_PRIVATE_TEXT_ONLY_YOU')
    } else {
        return languages.label('LBL_KUDOS_PRIVATE_TEXT_ON_MESSAGE', null, {
            name: props.kudos.employee.first_name ? props.kudos.employee.first_name : props.kudos.employee.full_name,
        })
    }
})
</script>

<style scoped lang="scss">
.photo-container-small {
    width: 18px;
    height: 18px;
    display: flex;
    justify-content: center;
    & i {
        font-size: 18px;
    }
}

img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 50%;
}
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
