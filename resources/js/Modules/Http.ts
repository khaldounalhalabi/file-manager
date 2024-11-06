const baseHeaders = {
    accept: "application/html",
    "Content-Type": "application/html",
};

const POST = async (
    url: string,
    data: BodyInit | undefined,
    headers: Record<string, any> | undefined,
) => {
    return await fetch(url, {
        headers: { ...baseHeaders, ...headers },
        method: "POST",
        body: data,
    }).then((response) => response.json());
};

export const GET = async (
    url: string,
    queryParams?: Record<string, string>,
    headers?: Record<string, string>,
) =>
    await fetch(url + new URLSearchParams(queryParams), {
        headers: { ...baseHeaders, ...headers },
        method: "GET",
    }).then((response) => response.json());
