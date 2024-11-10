import { ApiResponse } from "@/Models/Response";
import { getCsrf } from "@/helper";

const csrf = getCsrf() ?? "";
const baseHeaders: HeadersInit = {
    accept: "application/html",
    "Content-Type": "application/html",
    "X-CSRF-TOKEN": csrf,
};

export const POST = async <T extends any = any>(
    url: string,
    data: BodyInit | undefined,
    headers: Record<string, any> | undefined,
): Promise<ApiResponse<T>> => {
    return await fetch(url, {
        headers: { ...baseHeaders, ...headers },
        method: "POST",
        body: data,
    }).then((response) => response.json());
};

export const GET = async <T extends any = any>(
    url: string,
    queryParams?: Record<string, string>,
    headers?: Record<string, string>,
): Promise<ApiResponse<T>> =>
    await fetch(url + new URLSearchParams(queryParams), {
        headers: { ...baseHeaders, ...headers },
        method: "GET",
    }).then((response) => response.json());

export const DELETE = async <T extends any = any>(
    url: string,
    queryParams?: Record<string, string>,
    headers?: Record<string, string>,
): Promise<ApiResponse<T>> => {
    return await fetch(url + new URLSearchParams(queryParams), {
        headers: { ...baseHeaders, ...headers },
        method: "DELETE",
    }).then((response) => response.json());
};
