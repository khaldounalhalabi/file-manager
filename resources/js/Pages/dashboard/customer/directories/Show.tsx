import { Directory } from "@/Models/Directory";
import ExplorerHeader from "@/Components/FilesAndDirectories/ExplorerHeader";
import PageCard from "@/Components/ui/PageCard";
import { router } from "@inertiajs/react";
import FileItem from "@/Components/FilesAndDirectories/FileItem";
import FolderItem from "@/Components/FilesAndDirectories/FolderItem";

const Show = ({ directory }: { directory: Directory }) => {
    const refetch = () => {
        router.visit(route("v1.web.customer.directories.show", directory.id));
    };

    return (
        <PageCard>
            <ExplorerHeader directory={directory} refetch={refetch}>
                <div
                    className={
                        "max-h-[80vh] max-w-full flex flex-col items-start justify-between gap-3 overflow-y-scroll"
                    }
                >
                    {directory.sub_directories?.map((dir, index) => (
                        <FolderItem
                            key={index}
                            directory={dir}
                            refetch={refetch}
                        />
                    ))}
                    {directory.files?.map((file, index) => (
                        <FileItem key={index} file={file} refetch={refetch} />
                    ))}
                </div>
            </ExplorerHeader>
        </PageCard>
    );
};

export default Show;
