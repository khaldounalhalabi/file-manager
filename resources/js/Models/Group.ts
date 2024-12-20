import { File } from "./File";
import { Directory } from "./Directory";
import { User } from "./User";

export interface Group {
    id: number;
    name: string;
    owner_id: number;
    owner?: User;
    users?: User[];
    directories?: Directory[];

    files?: File[][];
}
