import { User } from '@/store/auth'
import { Languages } from '@/store/languages'
import { MintReaction } from '../MintReactions/MintReactions'

export interface InitialResponse {
    user: User
    languages: Languages
    kudos: MintKudos[]
    users: MintKudosUser[]
}

export interface MintKudosUser {
    id: string
    first_name?: string
    full_name: string
    photo: string
}

export interface MintKudos {
    id: string
    name: string
    description: string | null
    announcement_date: string
    created_by: string
    announced: boolean
    private: boolean
    assigned_user: MintKudosUser
    employee: MintKudosUser
    current_user_is_gifted: boolean
    current_user_is_author: boolean
    current_user_is_admin: boolean
    current_user_access: boolean
    reactions: MintReaction[]
    users: MintKudosUser[]
}

export interface Form {
    text: string
    user: MintKudosUser
    error: boolean
    private: boolean
    loading: boolean
    kudosId: string
}

export type Views = 'kudos-list' | 'user-list' | 'form' | 'success'

export type NavOption = 'all' | 'received' | 'given'
