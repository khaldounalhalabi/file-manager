import { FileLog } from "./FileLog";
import { Group } from "./Group";
import { Role } from "@/Models/Role";
import {Media} from "@/Models/Media";

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    profile?: Media;
    email_verified_at?: string;
    reset_password_code?: string;
    fcm_token?: string;
    group_id: number;
    roles: Role[];
    groups?: Group[];
    ownedGroups?: Group[];
    group?: Group;
    file_logs?: FileLog[];
}
