<template>
    <form ref="dropzoneForm" class="dropzone" id="dropzone-form"></form>
</template>

<script setup lang="ts">
import { onMounted, ref, defineProps } from 'vue'
import Dropzone from "dropzone"
import MintDropzone from './MintDropzone'
import { useDropzoneStore } from './MintDropzoneStore'
import { useUrlStore } from '@/store/url'

const dropzoneForm = ref(null)
const dropzoneStore = useDropzoneStore()
const url = useUrlStore()
const props = defineProps({
    module: String,
    record: String,
})

onMounted(() => {
    if(dropzoneForm.value && props.module && props.record) {
        let dropzoneClass = new MintDropzone(props.record, props.module, {
            module: props.module,
            record_id: props.record,
        })
        dropzoneClass.init(dropzoneForm.value)
    }
})

</script>

<style scoped lang="scss">

.dropzone, .dropzone * {
    box-sizing: border-box;
}

.dropzone {
    min-height: 150px;
    display: flex;
    width: 100%;
    position: relative;
    flex-wrap: wrap;

    :deep(.dz-preview) {
        z-index: 1;
        margin-right: 10px;
        padding: 10px;
        border-radius: 16px;
        position: relative;
        display: inline-block;
        vertical-align: top;
        min-height: 100px;
        width: 100px;
        font-size: 11px;
        align-items: center;
        justify-content: center;

        a {
            color: rgb(20, 92, 122);
            text-decoration: none;
            font-weight: bold;
        }

        .dz-image {
            height: 60px;
            width: 60px;
            margin: 0 auto;
            margin-bottom: 10px;
            border-radius: 16px;
            background: #999;
            background: linear-gradient(to bottom, #eee, #ddd);

            img {
                border-radius: 16px !important;
                height: 60px;
                width: 60px;
            }

            i {
                font-size: 60px;
                line-height: 0.9;
                background-color: rgba(255, 255, 255);
            }
        }

        &:not(:hover) {
            .dz-remove {
                display: none;
            }
        }

        &:hover {
            z-index: 1000;
            .dz-remove {
                display: block;
            }
            .dz-image {
                background: rgba(255, 255, 255, 0);
            }
        }

        .dz-filename {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            position: relative;
        }

        .dz-filename-tooltip {
            display: none;
            position: absolute;
            z-index: 1000;
            background: rgba(0,0,0,0.7);
            color: white;
            border-radius: 3px;
            padding: 5px;
            box-shadow: 0px 1px 12px #00997619;
            top: 80%;
            left: 50%;
            transform: translateX(-50%);
            padding-left: 10px;
            padding-right: 10px;
        }

        .dz-filename:hover + .dz-filename-tooltip {
            display: block;
        }

        .dz-success-mark, .dz-error-mark {
            display: none;
        }
    }

    :deep(.dz-message) {
        position: absolute;
        z-index: 0;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    :deep(.smudge-effect) {
        filter: blur(1px);
    }
}
</style>