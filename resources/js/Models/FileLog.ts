import { File } from "./File";

import { User } from "./User";

export interface FileLog {
    id?: number;
    file_id: number;
    event_type: string;
    user_id: number;
    happened_at: string;

    file?: File;
    user?: User;
}
