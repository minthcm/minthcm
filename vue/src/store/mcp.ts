import { ref } from 'vue'
import { defineStore } from 'pinia'
import { mintApi } from '@/api/api'

export const useMcpStore = defineStore('mcp', () => {
    const flowId = ref('')
    const clientName = ref('')
    const scope = ref('')
    const authenticatedUser = ref('')
    const infoLoaded = ref(false)
    const consentToken = ref('')

    function setFlowId(id: string) {
        flowId.value = id
    }

    /**
     * Fetch the pending OAuth authorization details for the current flow
     * (client_name, scope, consent_token, authenticated_user). The
     * consent_token is an anti-CSRF token that must be echoed back to
     * /mcp/authorize and /mcp/deny.
     */
    async function fetchOauthInfo(): Promise<boolean> {
        if (!flowId.value) {
            return false
        }
        try {
            const response = await mintApi.get('mcp/oauth-info', {
                params: { flow: flowId.value },
                rawError: true,
            })
            clientName.value = String(response.data?.client_name ?? '')
            scope.value = String(response.data?.scope ?? '')
            consentToken.value = String(response.data?.consent_token ?? '')
            authenticatedUser.value = String(response.data?.authenticated_user ?? '')
            infoLoaded.value = true
            return Boolean(consentToken.value)
        } catch {
            infoLoaded.value = true
            return false
        }
    }

    async function postDecision(endpoint: 'authorize' | 'deny'): Promise<boolean> {
        if (!flowId.value || !consentToken.value) {
            return false
        }
        try {
            const response = await mintApi.post(
                `mcp/${endpoint}`,
                { flow: flowId.value, consent_token: consentToken.value },
                { rawError: true },
            )
            const redirectUrl = response.data?.redirect_url
            if (typeof redirectUrl !== 'string' || !redirectUrl) {
                return false
            }
            consentToken.value = ''
            window.location.href = redirectUrl
            return true
        } catch {
            return false
        }
    }

    const authorize = () => postDecision('authorize')
    const deny = () => postDecision('deny')

    return {
        flowId,
        clientName,
        scope,
        authenticatedUser,
        infoLoaded,
        setFlowId,
        fetchOauthInfo,
        authorize,
        deny,
    }
})
