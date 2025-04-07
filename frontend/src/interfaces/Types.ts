export interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role: string;
    status: string;
    active: boolean;
    avatar?: File;
}

export interface LoginFormData {
    email: string;
    password: string;
}