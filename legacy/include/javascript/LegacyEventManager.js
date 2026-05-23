window.LegacyEventManager = class LegacyEventManager {
    constructor() {
        this.events = {}
    }

    init() {
        window.addEventListener('message', this.resolveEvent.bind(this))
    }

    resolveEvent(event) {
        const eventId = event.data?.eventId
        if (eventId && this.events[eventId]) {
            this.events[eventId](event.data.data)
            delete this.events[eventId]
        }
    }

    async emit(eventName, data) {
        if (window.parent === window) {
            return null
        }
        const eventId = LegacyEventManager.generateEventId()
        const message = {
            eventId,
            eventName,
            data
        }
        return await new Promise((resolve) => {
            this.events[eventId] = resolve
            window.parent.postMessage(message)
        })
    }

    static generateEventId() {
        return Math.random().toString(36).substring(7)
    }
}

window.LegacyEventManager = new LegacyEventManager()
window.LegacyEventManager.init()
