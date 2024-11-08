import { Directory } from "@/Models/Directory";
import ExplorerHeader from "@/Components/FilesAndDirectories/ExplorerHeader";
import PageCard from "@/Components/ui/PageCard";
import { Folder } from "lucide-react";
import FolderOptions from "@/Components/FilesAndDirectories/FolderOptions";
import { Link, router } from "@inertiajs/react";
import dayjs from "dayjs";
import IconFile from "@/Components/FilesAndDirectories/FileIcon";

const Show = ({ directory }: { directory: Directory }) => {
    const refetch = () => {
        router.visit(route("v1.web.customer.directories.show", directory.id));
    };
    console.log(directory);
    return (
        <PageCard>
            <ExplorerHeader directory={directory} refetch={refetch} />
            <div
                className={
                    "max-h-[80vh] max-w-full flex flex-col items-start justify-between gap-3 overflow-y-scroll"
                }
            >
                {directory.sub_directories?.map((dir, index) => (
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
                            className={
                                "cursor-pointer w-[90%] border-r border-r-black"
                            }
                        >
                            <div
                                className={
                                    "flex items-center justify-between w-full"
                                }
                            >
                                <div
                                    className={"flex items-center gap-2 w-full"}
                                >
                                    <Folder className={"w-12 h-12"} />
                                    <div
                                        className={"flex flex-col items-start"}
                                    >
                                        <span>{dir.name}</span>
                                        Last modified : {dir.updated_at}
                                    </div>
                                </div>
                            </div>
                        </Link>
                        <FolderOptions directory={dir} refetch={refetch} />
                    </div>
                ))}
                {directory.files?.map((file, index) => (
                    <Link
                        key={index}
                        href={file.last_version?.file_path?.path}
                        target={"_blank"}
                        className={
                            "flex flex-col items-start p-3 bg-gray-200 hover:bg-gray-300 w-full gap-1 rounded-md cursor-pointer h-full"
                        }
                    >
                        <div
                            className={
                                "flex items-center justify-between w-full"
                            }
                        >
                            <div className={"flex items-center gap-2 w-3/4"}>
                                <IconFile fileName={file.name} />
                                <div className={"flex flex-col items-start"}>
                                    <span>{file.name}</span>
                                    Last modified :{" "}
                                    {dayjs(
                                        file?.last_version?.created_at,
                                    ).format("YYYY-MM-DD")}
                                </div>
                            </div>
                        </div>
                    </Link>
                ))}
            </div>
        </PageCard>
    );
};

export default Show;
