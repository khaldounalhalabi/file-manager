import { AvailableLocales } from "@/Models/Translatable";
import { User } from "@/Models/User";
import { route as routeFn } from "ziggy-js";
import { PageProps as InertiaProps } from "@inertiajs/core";
import { RoleName } from "@/Models/Role";
import { Group } from "@/Models/Group";

export interface MiddlewareProps extends InertiaProps {
    authUser: User;
    availableLocales: AvailableLocales[] | string[];
    currentLocale: AvailableLocales | string;
    currentRoute: string;
    tinymceApiKey: string;
    asset: string;
    baseUrl: string;
    csrfToken: string;
    message?: string;
    success?: string;
    error?: string;
    role?: RoleName;
    user_groups?: Group[];
}

declare global {
    var route: typeof routeFn;
}
