import React, { useContext } from "react";
import Pencil from "@/Components/icons/Pencil";
import { toast } from "react-toastify";
import { File } from "@/Models/File";
import {
    InfiniteData,
    QueryObserverResult,
    RefetchOptions,
} from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import { GET } from "@/Modules/Http";
import DownloadFile from "@/Hooks/DownloadFile";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";
import { ResponseCodeEnum } from "@/Enums/ResponseCodeEnum";
import { FileStatusEnum } from "@/Enums/FileStatusEnum";
import DeleteFileButton from "@/Components/FilesAndDirectories/DeleteFileButton";
import PushFileUpdateButton from "@/Components/FilesAndDirectories/PushFileUpdateButton";
import { SelectedFilesContext } from "@/Components/FilesAndDirectories/ExplorerHeader";
import Eye from "@/Components/icons/Eye";
import { Link } from "@inertiajs/react";
import { role, user as AuthUser } from "@/helper";

const FileOptions = ({
    file,
    refetch,
}: {
    file: File;
    refetch?:
        | ((
              options?: RefetchOptions,
          ) => Promise<
              QueryObserverResult<
                  InfiniteData<PaginatedResponse<any>, any>,
                  Error
              >
          >)
        | (() => void);
}) => {
    const { downloadFile, isLoading: isDownloading } = DownloadFile();
    const user = AuthUser();
    const authRole = role();
    const handleEdit = () => {
        GET<string>(route(`v1.web.${authRole}.files.edit`, file.id))
            .then((res) => {
                if (res.code == ResponseCodeEnum.OK) {
                    downloadFile(
                        () => fetch(res.data),
                        `${file?.name}.${file?.last_version?.file_path?.extension}`,
                    );
                } else {
                    toast.error("The file is locked by another user");
                }
                if (refetch) {
                    refetch();
                }
            })
            .catch(() => {
                toast.error("There is been an error");
                if (refetch) {
                    refetch();
                }
            });
    };

    const { selected, setSelected } = useContext(SelectedFilesContext);

    return (
        <div className={"flex items-center justify-between px-5 gap-1"}>
            {user?.id == file.last_log?.user_id &&
                file.last_log?.event_type == "started_editing" && (
                    <PushFileUpdateButton file={file} refetch={refetch} />
                )}
            <button
                type={"button"}
                className="hover:bg-white-secondary p-1 rounded-md disabled:cursor-not-allowed text-success disabled:text-white disabled:bg-gray-300"
                disabled={isDownloading || file.status == FileStatusEnum.LOCKED}
                onClick={() => {
                    handleEdit();
                }}
            >
                {isDownloading ? (
                    <LoadingSpinner className={"w-5 h-5 dark:text-white"} />
                ) : (
                    <Pencil className="w-5 h-5" />
                )}
            </button>

            {(user?.id == file.owner_id ||
                file.directory?.owner_id == user?.id ||
                user?.group?.owner_id == user?.id) && (
                <DeleteFileButton file={file} refetch={refetch} />
            )}

            <Link
                href={route(`v1.web.${authRole}.files.show`, file.id)}
                className="hover:bg-white-secondary p-0.5 rounded-md"
            >
                <Eye className="w-5 h-5 text-info" />
            </Link>

            {file.status == FileStatusEnum.UNLOCKED && (
                <input
                    type={"checkbox"}
                    defaultChecked={selected?.includes(file.id)}
                    onChange={(e) => {
                        if (e.target?.checked) {
                            setSelected((prev) => [file?.id, ...prev]);
                        } else {
                            setSelected((prev) =>
                                prev.filter((fileId) => fileId != file.id),
                            );
                        }
                    }}
                />
            )}
        </div>
    );
};

export default FileOptions;
