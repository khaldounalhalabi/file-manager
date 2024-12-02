import { Directory } from "@/Models/Directory";
import { Link } from "@inertiajs/react";
import { Folder } from "lucide-react";
import dayjs from "dayjs";
import FolderOptions from "@/Components/FilesAndDirectories/FolderOptions";

const FolderItem = ({
    directory,
    refetch,
}: {
    directory: Directory;
    refetch: () => void;
}) => {
    return (
        <div
            className={
                "flex items-center justify-between p-3 bg-gray-100 w-full gap-1 rounded-md h-full"
            }
        >
            <Link
                href={route("v1.web.customer.directories.show", directory.id)}
                className={
                    "cursor-pointer w-[90%] border-r border-r-black hover:bg-gray-300 rounded-l-md p-2"
                }
            >
                <div className={"flex items-center justify-between w-full"}>
                    <div className={"flex items-center gap-2 w-full"}>
                        <Folder className={"w-12 h-12"} />
                        <div className={"flex flex-col items-start"}>
                            <span>{directory.name}</span>
                            Last modified :{" "}
                            {dayjs(directory.updated_at).format("YYYY-MM-DD")}
                        </div>
                    </div>
                </div>
            </Link>
            <FolderOptions directory={directory} refetch={refetch} />
        </div>
    );
};

export default FolderItem;
