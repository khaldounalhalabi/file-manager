export type RoleName = "customer" | "admin";

export interface Role {
    id: number;
    name: RoleName;
}
