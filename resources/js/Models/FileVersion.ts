import { File } from "./File";

export interface FileVersion {
    id?: number;
    file_path: string;
    file_id: number;
    version: number;
    file?: File;
}
