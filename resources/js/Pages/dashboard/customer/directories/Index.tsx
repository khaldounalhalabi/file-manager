import PageCard from "@/Components/ui/PageCard";
import { useInfiniteQuery } from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import { Directory } from "@/Models/Directory";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";
import ExplorerHeader from "@/Components/FilesAndDirectories/ExplorerHeader";
import { GET } from "@/Modules/Http";
import FolderItem from "@/Components/FilesAndDirectories/FolderItem";

const fetchDirectories = async ({
    pageParam,
}: {
    pageParam: number;
}): Promise<PaginatedResponse<Directory>> => {
    return await GET(
        route("v1.web.customer.directories.get.root", {
            page: pageParam,
        }),
    );
};

const Index = () => {
    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetching,
        isFetchingNextPage,
        refetch,
    } = useInfiniteQuery({
        queryKey: ["root_directories"],
        queryFn: fetchDirectories,
        initialPageParam: 1,
        getNextPageParam: (lastPage) =>
            lastPage.pagination_data?.is_last
                ? undefined
                : (lastPage.pagination_data?.currentPage ?? 0) + 1,
        retry: true,
    });

    const handleDataScrolling = (e: any) => {
        const { scrollTop, clientHeight, scrollHeight } = e.target;
        if (scrollHeight - scrollTop === clientHeight && hasNextPage) {
            fetchNextPage();
        }
    };
    return (
        <PageCard>
            <ExplorerHeader refetch={refetch} children={undefined} />
            <div
                className={
                    "max-h-[80vh] max-w-full flex flex-col items-start justify-between gap-3 overflow-y-scroll"
                }
                onScroll={handleDataScrolling}
            >
                {isFetching && !isFetchingNextPage ? (
                    <div className={"flex justify-center items-center w-full"}>
                        <LoadingSpinner className={"dark:text-white"} />
                    </div>
                ) : (
                    data?.pages?.map((page) =>
                        page?.data ? (
                            page?.data?.map((dir, index) => (
                                <FolderItem
                                    directory={dir}
                                    refetch={refetch}
                                    key={index}
                                />
                            ))
                        ) : (
                            <div
                                className={
                                    "flex justify-center items-center w-full p-5"
                                }
                            >
                                There is no data
                            </div>
                        ),
                    )
                )}
                {isFetchingNextPage && (
                    <div
                        className={
                            "flex items-center justify-center p-3  w-full"
                        }
                    >
                        <LoadingSpinner className={"dark:text-white"} />
                    </div>
                )}
            </div>
        </PageCard>
    );
};
export default Index;
