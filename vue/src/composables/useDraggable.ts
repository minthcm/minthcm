export const useDraggable = (draggable: HTMLElement, dragged?: HTMLElement) => {
    let prevCursorPosition = { left: 0, top: 0 }

    function init() {
        if (!dragged) {
            dragged = draggable
        }
        draggable.addEventListener('mousedown', dragStart)
    }

    function dragStart(e: MouseEvent) {
        prevCursorPosition = {
            left: e.clientX,
            top: e.clientY,
        }
        window.addEventListener('mouseup', dragEnd)
        window.addEventListener('mousemove', onMouseMoveHandler)
    }

    function dragEnd() {
        window.removeEventListener('mouseup', dragEnd)
        window.removeEventListener('mousemove', onMouseMoveHandler)
        validateVisibility()
    }

    function onMouseMoveHandler(e: MouseEvent) {
        if (!dragged) {
            return
        }
        const cursorPosition = {
            left: e.clientX,
            top: e.clientY,
        }
        const computedPosition = {
            left: parseInt(dragged.style.left, 10) + cursorPosition.left - prevCursorPosition.left,
            top: parseInt(dragged.style.top, 10) + cursorPosition.top - prevCursorPosition.top,
        }
        dragged.style.left = computedPosition.left + 'px'
        dragged.style.top = computedPosition.top + 'px'
        prevCursorPosition = cursorPosition
    }

    function validateVisibility() {
        if (!dragged) {
            return
        }
        const draggableComputedStyle = window.getComputedStyle(draggable)
        const draggedComputedStyle = window.getComputedStyle(dragged)
        const width = parseInt(draggableComputedStyle.width, 10)
        const height = parseInt(draggableComputedStyle.height, 10)
        const left = parseInt(draggedComputedStyle.left, 10)
        const top = parseInt(draggedComputedStyle.top, 10)

        const widthBoundary =
            draggedComputedStyle.position === 'fixed' ? window.innerWidth : document.documentElement.scrollWidth
        const heightBoundary =
            draggedComputedStyle.position === 'fixed' ? window.innerHeight : document.documentElement.scrollHeight

        // Specifies the number of pixels that draggable element sticks out from the window edge
        const w = width >= 100 ? 100 : width
        const h = height >= 50 ? 50 : height

        if (left < w - width) {
            dragged.style.left = w - width + 'px'
        } else if (left > widthBoundary - w) {
            dragged.style.left = widthBoundary - w + 'px'
        }
        if (top < h - height) {
            dragged.style.top = h - height + 'px'
        } else if (top > heightBoundary - h) {
            dragged.style.top = heightBoundary - h + 'px'
        }
    }

    return {
        init,
    }
}
