import { usePage } from "@inertiajs/react";
import { MiddlewareProps } from "./types";
import Swal from "sweetalert2";
import withReactContent from "sweetalert2-react-content";
import { User } from "@/Models/User";
import { RoleName } from "@/Models/Role";

export const asset = (path: string) => {
    if (path.startsWith("/")) {
        path = path.replace("/", "");
    }

    return `${usePage<MiddlewareProps>().props.asset}${path}`;
};

export function getNestedPropertyValue(object: any, path: string): any {
    const properties = path.split(".");
    let value = object;
    for (const property of properties) {
        if (value?.hasOwnProperty(property)) {
            value = value[`${property}`];
        } else {
            return undefined;
        }
    }
    return value;
}

export const swal = withReactContent(Swal);

export function user(): User | null | undefined {
    const { authUser } = usePage<MiddlewareProps>().props;
    return authUser;
}

export function role(): RoleName | undefined {
    const { role } = usePage<MiddlewareProps>().props;
    return role;
}

export const getTheme = () =>
    window.localStorage.getItem("theme_mode") ?? "light";

export const setTheme = (theme: "light" | "dark") =>
    window.localStorage.setItem("theme_mode", theme);

export const getCsrf = () => window.localStorage.getItem("csrf_token");

export const setCsrf = (csrf_token: string) =>
    window.localStorage.setItem("csrf_token", csrf_token);

export const userGroups = () => {
    const { user_groups } = usePage<MiddlewareProps>().props;
    return user_groups;
};

export const getLocale = (): string | undefined => {
    const { currentLocale } = usePage<MiddlewareProps>().props;
    return currentLocale;
};
