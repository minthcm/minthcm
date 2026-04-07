/**
 * Safely decodes HTML entities without executing HTML or scripts.
 * Uses a textarea element which only decodes character references (e.g. &amp; → &)
 * but never renders or executes any HTML tags or scripts — XSS-safe.
 */
export function decodeHtmlEntities(str: string): string {
    if (!str || typeof str !== 'string') return str
    const textarea = document.createElement('textarea')
    textarea.innerHTML = str
    return textarea.value
}

/**
 * Decodes HTML entities in all string values of a flat record object.
 * Non-string values (numbers, booleans, null) are passed through unchanged.
 */
export function decodeRecordStrings(record: Record<string, unknown>): Record<string, unknown> {
    const decoded: Record<string, unknown> = {}
    for (const [key, value] of Object.entries(record)) {
        decoded[key] = typeof value === 'string' ? decodeHtmlEntities(value) : value
    }
    return decoded
}
