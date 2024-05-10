import { LegacyEvent } from './LegacyEvent'

const files = import.meta.glob('./LegacyEvents/*.ts', {
    eager: true,
})

interface LegacyEvents {
    [key: string]: new (eventId: string, data: any) => LegacyEvent
}

const legacyEvents: LegacyEvents = {}

for (const path in files) {
    const file = files[path] as LegacyEvents
    const name = path.match(/\.\/LegacyEvents\/(.*)\.ts$/)?.[1]
    if (name && file) {
        legacyEvents[name] = file.default ?? file[name]
    }
}

export default legacyEvents
