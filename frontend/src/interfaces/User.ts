export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    role: string;
    status: string;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    avatar_url: string | null;
    logs: UserLog[];
}

export interface UserLog {
    id: number;
    user_id: number;
    details: {
        ip: string;
        agent: string;
        action: string;
    };
    created_at: string;
    updated_at: string;
}