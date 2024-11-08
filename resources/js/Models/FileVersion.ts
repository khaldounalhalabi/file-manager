import { File } from "./File";
import { Media } from "@/Models/Media";

export interface FileVersion {
    id?: number;
    file_path: Media;
    file_id: number;
    version: number;
    file?: File;
    updated_at: string;
    created_at: string;
}
