<template>
    <v-data-table-server
        class="list-table"
        :headers="store.headers"
        :items="store.results"
        :items-length="store.itemsLength || 0"
        :loading="store.isLoading || store.initialLoading"
        fixed-header
        must-sort
        :height="store.mode === 'relate' ? 'calc(100vh - 400px)' : null"
        :show-select="store.itemsSelectable"
        v-model="store.selected"
        @update:options="store.options = $event"
        :no-data-text="languages.label('LBL_ESLIST_NO_DATA_AVAILABLE')"
    >
        <template v-slot:item.name="{ item }">
            <a @click="store.handleNameClick(item)" class="list-table-name-link">
                {{ item.name || item.full_name }}
            </a>
        </template>
        <template
            v-for="link in store.customFields.links"
            v-slot:[`item.${link.nameField}`]="{ item }"
            :key="link.nameField"
        >
            <router-link
                v-if="item[link.urlField]"
                :to="url.fromLegacyUrl(item[link.urlField])"
                :target="store.mode === 'relate' ? '_blank' : null"
                v-text="item[link.nameField]"
            />
            <span v-else v-text="item[link.nameField]" />
        </template>
        <template v-for="bool in store.customFields.booleans" v-slot:[`item.${bool}`]="{ item }" :key="bool">
            <v-icon
                color="secondary"
                :icon="item[bool] && item[bool] !== '0' ? 'mdi-checkbox-marked-circle' : 'mdi-close'"
            />
        </template>
        <template v-for="list in store.customFields.lists" v-slot:[`item.${list.field}`]="{ item }" :key="list.field">
            <div
                v-if="list.colors"
                class="enum-chip"
                :style="list.colors[item[list.field]]"
                v-text="list.options[item[list.field]]"
            />
            <span v-else v-text="list.options[item[list.field]]" />
        </template>
        <template
            v-for="multienum in store.customFields.multienums"
            v-slot:[`item.${multienum.field}`]="{ item }"
            :key="multienum.field"
        >
            <span v-text="formatMultienum(item[multienum.field], multienum.options)" />
        </template>
        <template v-for="date in store.customFields.dates" v-slot:[`item.${date.field}`]="{ item }" :key="date.field">
            <span v-text="item[date.field]" :style="date.style" />
        </template>
        <template
            v-for="currency in store.customFields.currencies"
            v-slot:[`item.${currency}`]="{ item }"
            :key="currency"
        >
            <span v-text="NumberUtils.formatCurrency(item[currency], item.currency_id)" />
        </template>
        <template v-slot:item.actions="{ item }">
            <div class="d-flex justify-end" style="gap: 8px">
                <v-icon
                    v-for="action in getItemActions(item)"
                    v-show="action.icon"
                    :key="action.icon"
                    @click="action.onClick(item)"
                    color="secondary"
                    size="small"
                    :icon="action.icon"
                />
            </div>
        </template>
        <template #bottom>
            <VDataTableFooter
                :items-per-page-options="store.config?.config?.itemsPerPageOptions"
                v-bind:items-per-page-text="languages.label('LBL_ESLIST_ITEMS_PER_PAGE')"
                :page-text="pageText"
            />
        </template>
    </v-data-table-server>
</template>

<script setup lang="ts">
import axios from 'axios'
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useUrlStore } from '@/store/url'
import { usePopupsStore } from '@/store/popups'
import NumberUtils from '@/utils/numbers'

const router = useRouter()
const store = useListViewStore()
const url = useUrlStore()
const languages = useLanguagesStore()
const popups = usePopupsStore()

const pageText = computed(() => {
    const isOverflow = store.itemsLength > store.options.page * store.options.itemsPerPage
    const pageText = `{0} - {1} ${languages.label('LBL_ESLIST_PAGE_TEXT')} {2}`
    return isOverflow ? `${pageText}+` : pageText
})

const coreActions = {
    edit: {
        icon: 'mdi-pencil',
        onClick: (item) => router.push(`/modules/${url.module}/EditView/${item.id}`),
    },
    view: {
        icon: 'mdi-eye',
        onClick: (item) => router.push(`/modules/${url.module}/DetailView/${item.id}`),
    },
    delete: {
        icon: 'mdi-delete',
        onClick: async (item) => {
            const confirmMessage = `${languages.label('LBL_ESLIST_DELETE_RECORD_CONFIRM_BODY')} ${item.name}?`
            if (await popups.confirm(confirmMessage)) {
                await axios.delete(`api/${url.module}/${item.id}`)
                store.getData()
            }
        },
    },
}

function getItemActions(item: any) {
    return store.config.config.actions
        .filter((action) => typeof action !== 'string' || item.acl_access[action])
        .map((action) => {
            if (typeof action === 'string') {
                return coreActions[action] ?? {}
            }
            return {
                ...action,
                onClick: (item) => eval(action.onClick)(item),
            }
        })
}

function formatMultienum(value, labels) {
    return value
        .replaceAll('^', '')
        .split(',')
        .filter((label) => label in labels)
        .map((label) => labels[label])
        .join(', ')
}
</script>

<style scoped lang="scss">
.list-table {
    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-pagination__first),
    :deep(.v-pagination__last) {
        display: none;
    }
    .enum-chip {
        display: flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        font-size: 13px;
        padding: 4px 12px;
        font-weight: bold;
        text-transform: uppercase;
        border-radius: 5px;
        letter-spacing: 0.09px;
    }
    .list-table-name-link {
        cursor: pointer;
    }
}
</style>
