import { ApiResponse } from "@/Models/Response";
import { getCsrf } from "@/helper";
import { router } from "@inertiajs/react";

const csrf = getCsrf() ?? "";
const baseHeaders: HeadersInit = {
    accept: "application/html",
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": csrf,
    "X-Source": "Fetch-Api",
};

export const POST = async <T extends any = any>(
    url: string,
    data: Record<string, any> | undefined,
    headers: Record<string, any> | undefined = {},
): Promise<ApiResponse<T>> => {
    return await fetch(url, {
        headers: { ...baseHeaders, ...headers },
        method: "POST",
        body: JSON.stringify(data),
    }).then((response) => handler(response.json()));
};

export const GET = async <T extends any = any>(
    url: string,
    queryParams?: Record<string, any>,
    headers?: Record<string, string>,
): Promise<ApiResponse<T>> =>
    await fetch(url + "?" + new URLSearchParams(queryParams), {
        headers: { ...baseHeaders, ...headers },
        method: "GET",
    }).then((response) => handler(response.json()));

export const DELETE = async <T extends any = any>(
    url: string,
    queryParams?: Record<string, string>,
    headers?: Record<string, string>,
): Promise<ApiResponse<T>> => {
    return await fetch(url + new URLSearchParams(queryParams), {
        headers: { ...baseHeaders, ...headers },
        method: "DELETE",
    }).then((response) => handler(response.json()));
};

const handler = (response: any): ApiResponse<any> => {
    if (response.code == 409) {
        router.visit(route("v1.web.customer.user.groups"));
    } else if (response.code == 403 || response.code == 401) {
        router.visit(route("v1.web.public.customer.login.page"));
    }
    return response;
};
