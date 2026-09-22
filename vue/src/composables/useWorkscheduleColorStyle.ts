import { useTheme } from 'vuetify'

/**
 * Shared styling for workschedule/leave chips: colors by theme token per type,
 * falling back to `workschedule-default` when the type has no dedicated token.
 */
export function useWorkscheduleColorStyle() {
    const theme = useTheme()

    function workscheduleColorStyle(type: string, opacity = 0.14) {
        const colorToken =
            type && theme.current.value.colors[`workschedule-${type}`]
                ? `--v-theme-workschedule-${type}`
                : '--v-theme-workschedule-default'
        return {
            background: `rgba(var(${colorToken}), ${opacity})`,
            color: `color-mix(in srgb, rgb(var(${colorToken})) 70%, black 30%)`,
            borderColor: `rgb(var(${colorToken}))`,
        }
    }

    function acceptedLeaveColorStyle() {
        return {
            background: 'rgba(var(--v-theme-success, 76, 175, 80), 0.14)',
            color: 'rgb(var(--v-theme-success, 76, 175, 80))',
            borderColor: 'rgb(var(--v-theme-success, 76, 175, 80))',
        }
    }

    return { workscheduleColorStyle, acceptedLeaveColorStyle }
}
