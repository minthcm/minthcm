import { useAuthStore } from '@/store/auth'
import { useBackendStore } from '@/store/backend'

const auth = useAuthStore()
const backend = useBackendStore()

export default class NumberUtils {
    public static formatNumber(n, num_grp_sep = null, dec_sep = null, round = null, precision = null) {
        if (n == '') {
            return n
        }
        num_grp_sep = num_grp_sep ?? auth.user.preferences.num_grp_sep
        dec_sep = dec_sep ?? auth.user.preferences.dec_sep
        round = round ?? auth.user.preferences.default_currency_significant_digits
        precision = precision ?? auth.user.preferences.default_currency_significant_digits
        n = n ? n.toString() : ''
        if (n.split) n = n.split('.')
        else return n

        if (n.length > 2) return n.join('.') // that's not a num!
        // round
        if (typeof round != 'undefined') {
            if (round > 0 && n.length > 1) {
                // round to decimal
                n[1] = parseFloat('0.' + n[1])
                n[1] = Math.round(n[1] * Math.pow(10, round)) / Math.pow(10, round)
                n[1] = n[1] === '0' ? n[1] : n[1].toString().split('.')[1]
            }
            if (round <= 0) {
                // round to whole number
                n[0] = Math.round(parseInt(n[0], 10) * Math.pow(10, round)) / Math.pow(10, round)
                n[1] = ''
            }
        }

        if (typeof precision != 'undefined' && precision >= 0) {
            if (n.length > 1 && typeof n[1] != 'undefined') n[1] = n[1].substring(0, precision) // cut off precision
            else n[1] = ''
            if (n[1].length < precision) {
                for (let wp = n[1].length; wp < precision; wp++) n[1] += '0'
            }
        }

        const regex = /(\d+)(\d{3})/
        while (num_grp_sep != '' && regex.test(n[0])) n[0] = n[0].toString().replace(regex, '$1' + num_grp_sep + '$2')
        return n[0] + (n.length > 1 && n[1] != '' ? dec_sep + n[1] : '')
    }
    public static formatCurrency(n: string | number, currency_id: string | number) {
        const currency = backend.initData.global.currencies[currency_id] ?? backend.initData.global.currencies[-99]
        const number = NumberUtils.formatNumber(n)
        if (currency.currency_on_right) {
            return number + currency.symbol
        } else {
            return currency.symbol + number
        }
    }
}
