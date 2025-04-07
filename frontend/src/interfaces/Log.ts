import type { User } from "./User";

export interface Log {
    id: number;
    user_id: number;
    details: LogDetails;
    created_at: string;
    updated_at: string;
    user: User;
}

export interface LogDetails {
    ip: string;
    agent: string;
    action: string;
    avatar_url?: string;
}
