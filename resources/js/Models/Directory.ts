import { Group } from "./Group";
import { User } from "@/Models/User";

export interface Directory {
    id?: number;
    name: string;
    group_id: number;
    path: string;
    parent_id?: number;
    owner_id: number;
    group?: Group;
    owner?: User;
    parent?: Directory;
    subDirectories?: Directory[];
}