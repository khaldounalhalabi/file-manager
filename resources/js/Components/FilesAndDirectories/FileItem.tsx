import { File } from "@/Models/File";
import DownloadFile from "@/Hooks/DownloadFile";
import IconFile from "@/Components/FilesAndDirectories/FileIcon";
import dayjs from "dayjs";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";
import FileOptions from "@/Components/FilesAndDirectories/FileOptions";

const FileItem = ({ file, refetch }: { file: File; refetch: () => void }) => {
    const { downloadFile, isLoading } = DownloadFile();

    return (
        <div
            className={
                "flex items-center justify-between p-3 bg-gray-100 w-full gap-1 rounded-md h-full"
            }
        >
            <div
                className={
                    "cursor-pointer w-[90%] border-r border-r-black hover:bg-gray-300 rounded-l-md p-2"
                }
                onClick={() => {
                    downloadFile(
                        () => fetch(file?.last_version?.file_path?.path),
                        `${file?.name}.${file?.last_version?.file_path?.extension}`,
                    );
                }}
            >
                <div className={"flex items-center justify-between w-full"}>
                    <div className={"flex items-center gap-2 w-3/4"}>
                        <IconFile
                            extension={file?.last_version?.file_path?.extension}
                        />
                        <div className={"flex flex-col items-start"}>
                            <span>{file.name}</span>
                            <span>
                                Last modified :{" "}
                                {dayjs(file?.last_version?.created_at).format(
                                    "YYYY-MM-DD",
                                )}
                            </span>
                            <span>Version: {file?.last_version?.version}</span>
                        </div>
                    </div>
                    {isLoading && <LoadingSpinner />}
                </div>
            </div>
            <FileOptions file={file} refetch={refetch} />
        </div>
    );
};

export default FileItem;
