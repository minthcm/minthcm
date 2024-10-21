const files = import.meta.glob(['../drawers/*.ts', '../custom/drawers/*.ts'], {
    eager: true,
})

const drawers: any = []

for (const path in files) {
    const file = files[path] as any
    const isCustomPath = path.includes('custom/')
    const prefix = isCustomPath ? 'custom-' : 'mint-'
    drawers.push({
        ...file.default,
        key: prefix + path.split('/').pop()?.replace('.drawer.ts', ''),
    })
}

export default drawers
