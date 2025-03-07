export interface PaginatedResponse<T> {
    data: T[];
    pagination_data?: {
        currentPage: number;
        from: number;
        to: number;
        total: number;
        per_page: number;
        total_pages: number;
        is_first: boolean;
        is_last: boolean;
    };
}

export interface ApiResponse<T> {
    data: T;
    code: number;
    status: boolean;
    pagination_data?: {
        currentPage: number;
        from: number;
        to: number;
        total: number;
        per_page: number;
        total_pages: number;
        is_first: boolean;
        is_last: boolean;
    };
}
