<template>
    <div v-if="!isHidden" class="mint-comments-users-hint">
        <div
            v-for="user in usersList"
            :key="user.id"
            class="mint-comments-users-hint-user"
            v-ripple
            @click="emit('user-click', user.user_name)"
        >
            <div class="mint-comments-users-hint-avatar">
                <img v-if="user.photo" :src="`legacy/index.php?entryPoint=download&type=Users&id=${user.id}_photo`" />
                <v-icon v-else icon="mdi-account" />
            </div>
            <div>{{ user.name }}</div>
        </div>
        <div
            v-if="!usersList?.length"
            class="mint-comments-users-hint-message"
            v-text="languages.label('LBL_MINT4_COMMENTS_USERS_HINT_NOT_FOUND')"
        />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useMintCommentsStore } from './MintCommentsStore'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    query: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
    (event: 'user-click', username: string): void
}>()

const store = useMintCommentsStore()
const languages = useLanguagesStore()

// don't show list if there is a space at the end and there is only one possibility
const isHidden = computed(
    () =>
        props.query.match(/\s/g)?.length &&
        store.users.find((user) => user.user_name.trim().toLocaleLowerCase() === props.query.split(' ')[0].trim()) &&
        usersList.value.length <= 1,
)

const standardizedQuery = computed(() => {
    return props.query.trim().toLowerCase()
})

const activeUsers = computed(() => store.users.filter((user) => user.status === 'Active'))

const usersList = computed(() => {
    return activeUsers.value
        .filter(
            (user) =>
                !standardizedQuery.value ||
                [user.user_name, user.name, ...user.name.split(' '), user.name.split(' ').reverse().join(' ')].some(
                    (name) => name.trim().toLowerCase().startsWith(standardizedQuery.value),
                ),
        )
        .slice(0, 6)
})
</script>

<style scoped lang="scss">
.mint-comments-users-hint {
    padding: 8px 0px;
    background: white;
    border: thin solid #0003;
    min-width: 270px;
    height: 100%;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;

    .mint-comments-users-hint-message {
        padding: 16px;
    }

    .mint-comments-users-hint-user {
        display: flex;
        gap: 16px;
        align-items: center;
        padding: 8px 12px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.43px;
        color: rgb(var(--v-theme-secondary));

        &:hover {
            background: #0001;
            cursor: pointer;
        }
    }

    .mint-comments-users-hint-avatar {
        display: flex;
        height: fit-content;
        > * {
            font-size: 20px;
            background: #eee;
            color: #444;
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
        }
    }
}
</style>
