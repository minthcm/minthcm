export abstract class LegacyEvent {
    private eventId: string
    protected data: any

    constructor(eventId: string, data: any) {
        this.eventId = eventId
        this.data = data
    }

    public async resolveEvent() {
        const result = await this.execute()
        const legacyFrame = document.querySelector('iframe.legacy-view') as HTMLIFrameElement
        if (legacyFrame) {
            legacyFrame.contentWindow?.postMessage({ eventId: this.eventId, data: result })
        }
    }

    protected abstract execute(): Promise<any>
}
