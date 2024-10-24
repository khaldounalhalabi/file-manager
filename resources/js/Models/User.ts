import { Role } from "@/Models/Role";

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    profile?: string;
    email_verified_at?: string;
    reset_password_code?: string;
    fcm_token?: string;
    roles: Role[];
}
