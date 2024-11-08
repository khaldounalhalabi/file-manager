import { File } from "./File";
import { Group } from "./Group";
import { User } from "@/Models/User";

export interface Directory {
    updated_at: string;
    id: number;
    name: string;
    group_id: number;
    path: DirectoryPath[];
    parent_id?: number;
    owner_id: number;
    group?: Group;
    owner?: User;
    parent?: Directory;
    sub_directories: Directory[];
    files: File[];
}

export interface DirectoryPath {
    name: string;
    id: number;
}
