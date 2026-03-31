import { useElementSize } from '@vueuse/core'
import { computed, onMounted, Ref, ref, toValue } from 'vue'

interface MoveArgs {
    element: Ref<HTMLElement | null>
    steps: Ref<number> | number
    handleSelector?: string
    leftHandleSelector?: string
    rightHandleSelector?: string
    onMoveEnd: () => void
}

type MoveEdge = 'left' | 'right' | 'both'

export const useMove = (args: MoveArgs) => {
    const handle = ref<HTMLElement | null>(null)
    const leftHandle = ref<HTMLElement | null>(null)
    const rightHandle = ref<HTMLElement | null>(null)
    const moveEdge = ref<MoveEdge>('both')

    const container = computed(() => {
        if (args.element.value) {
            return args.element.value.parentNode as HTMLElement
        }
        return null
    })
    const { width } = useElementSize(container)

    const stepWidthPx = computed(() => {
        if (container.value) {
            return width.value / toValue(args.steps)
        }
        return 0
    })
    const movedPixels = ref(0)
    const movedSteps = computed(() => {
        return Math.round(movedPixels.value / stepWidthPx.value)
    })

    onMounted(() => {
        if (!args.element.value) {
            return console.warn('useMove: element is null')
        }
        handle.value = args.handleSelector ? args.element.value.querySelector(args.handleSelector) : args.element.value
        if (!handle.value) {
            return console.warn('useMove: handle element not found')
        }
        handle.value.addEventListener('mousedown', (e: MouseEvent) => onMoveStart(e, 'both'))
        if (args.leftHandleSelector) {
            leftHandle.value = args.element.value.querySelector(args.leftHandleSelector)
            leftHandle.value?.addEventListener('mousedown', (e: MouseEvent) => onMoveStart(e, 'left'))
        }
        if (args.rightHandleSelector) {
            rightHandle.value = args.element.value.querySelector(args.rightHandleSelector)
            rightHandle.value?.addEventListener('mousedown', (e: MouseEvent) => onMoveStart(e, 'right'))
        }
    })

    const initPositionX = ref(0)

    function updateCursor() {
        const cursor = moveEdge.value === 'both' ? 'grabbing' : 'ew-resize'
        document.body.style.cursor = cursor
        if (handle.value) {
            handle.value.style.cursor = cursor
        }
        if (leftHandle.value) {
            leftHandle.value.style.cursor = cursor
        }
        if (rightHandle.value) {
            rightHandle.value.style.cursor = cursor
        }
    }

    function resetCursor() {
        document.body.style.cursor = null!
        if (handle.value) {
            handle.value.style.cursor = null!
        }
        if (leftHandle.value) {
            leftHandle.value.style.cursor = null!
        }
        if (rightHandle.value) {
            rightHandle.value.style.cursor = null!
        }
    }

    function onMoveStart(e: MouseEvent, edge: MoveEdge) {
        moveEdge.value = edge
        if (['left', 'right'].includes(edge)) {
            e.stopPropagation()
        }
        initPositionX.value = e.clientX
        updateCursor()
        if (handle.value) {
            document.addEventListener('mousemove', onMove)
            document.addEventListener('mouseup', onDragEnd)
        }
    }

    function onMove(e: MouseEvent) {
        if (handle.value) {
            movedPixels.value = e.clientX - initPositionX.value
        }
    }

    async function onDragEnd() {
        resetCursor()
        document.removeEventListener('mousemove', onMove)
        document.removeEventListener('mouseup', onDragEnd)
        if (handle.value) {
            handle.value.style.cursor = 'grab'
        }
        await args.onMoveEnd()
        movedPixels.value = 0
        initPositionX.value = 0
    }

    return {
        stepWidthPx,
        moveEdge,
        movedSteps,
    }
}
