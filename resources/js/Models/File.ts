import { Group } from "./Group";
import { Directory } from "./Directory";
import { User } from "@/Models/User";

export interface File {
    id?: number;
    group_id: number;
    directory_id: number;
    status: string;
    group?: Group;
    directory?: Directory;
    owner?: User;
    owner_id?: number;
}
