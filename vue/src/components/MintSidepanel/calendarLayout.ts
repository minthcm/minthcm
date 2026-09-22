/**
 * Assigns each interval a column (and its cluster's column count) so that
 * time-overlapping items can be laid out side-by-side without visual overlap.
 *
 * Algorithm (assumes `items` are pre-sorted by `startMin`):
 * 1. Split the sequence into maximal "overlap clusters" — a cluster ends once
 *    an item starts at or after every interval opened so far has closed.
 * 2. Within each cluster, greedily pack intervals into the fewest columns
 *    (classic interval graph coloring): an item reuses the first column whose
 *    previous occupant already ended, otherwise it opens a new column.
 * 3. Every item in a cluster shares that cluster's final column count, so the
 *    caller can render each item at `column / columnCount` width.
 */
export function layoutActivities<T extends { startMin: number; endMin: number }>(
    items: T[],
): (T & { column: number; columnCount: number })[] {
    return splitIntoOverlapClusters(items).flatMap(packCluster)
}

function splitIntoOverlapClusters<T extends { startMin: number; endMin: number }>(items: T[]): T[][] {
    const clusters: T[][] = []
    let current: T[] = []
    let clusterEnd = -Infinity

    for (const item of items) {
        if (current.length && item.startMin >= clusterEnd) {
            clusters.push(current)
            current = []
        }
        current.push(item)
        clusterEnd = Math.max(clusterEnd, item.endMin)
    }
    if (current.length) clusters.push(current)

    return clusters
}

function packCluster<T extends { startMin: number; endMin: number }>(
    cluster: T[],
): (T & { column: number; columnCount: number })[] {
    const columnEnds: number[] = []

    const placed = cluster.map((item) => {
        let column = columnEnds.findIndex((end) => end <= item.startMin)
        if (column === -1) {
            column = columnEnds.length
            columnEnds.push(item.endMin)
        } else {
            columnEnds[column] = item.endMin
        }
        return { ...item, column, columnCount: 0 }
    })

    return placed.map((item) => ({ ...item, columnCount: columnEnds.length }))
}
