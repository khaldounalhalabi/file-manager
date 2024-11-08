import PageCard from "@/Components/ui/PageCard";
import { useInfiniteQuery } from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import { Directory } from "@/Models/Directory";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";
import { Folder } from "lucide-react";
import ExplorerHeader from "@/Components/FilesAndDirectories/ExplorerHeader";
import { GET } from "@/Modules/Http";
import FolderOptions from "@/Components/FilesAndDirectories/FolderOptions";
import { Link } from "@inertiajs/react";

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
            <ExplorerHeader refetch={refetch} />
            <div
                className={
                    "max-h-[80vh] max-w-full flex flex-col items-start justify-between gap-3 overflow-y-scroll"
                }
                onScroll={handleDataScrolling}
            >
                {isFetching && !isFetchingNextPage ? (
                    <div className={"flex justify-center items-center w-full"}>
                        <LoadingSpinner />
                    </div>
                ) : (
                    data?.pages?.map((page) =>
                        page?.data ? (
                            page?.data?.map((dir, index) => (
                                <div
                                    className={
                                        "flex items-center justify-between p-3 bg-gray-200 hover:bg-gray-300 w-full gap-1 rounded-md h-full"
                                    }
                                >
                                    <Link
                                        key={index}
                                        href={route(
                                            "v1.web.customer.directories.show",
                                            dir.id,
                                        )}
                                        className={"cursor-pointer w-[90%] border-r border-r-black"}
                                    >
                                        <div
                                            className={
                                                "flex items-center justify-between w-full"
                                            }
                                        >
                                            <div
                                                className={
                                                    "flex items-center gap-2 w-full"
                                                }
                                            >
                                                <Folder
                                                    className={"w-12 h-12"}
                                                />
                                                <div
                                                    className={
                                                        "flex flex-col items-start"
                                                    }
                                                >
                                                    <span>{dir.name}</span>
                                                    Last modified :{" "}
                                                    {dir.updated_at}
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                    <FolderOptions
                                        directory={dir}
                                        refetch={refetch}
                                    />
                                </div>
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
                        <LoadingSpinner />
                    </div>
                )}
            </div>
        </PageCard>
    );
};
export default Index;
