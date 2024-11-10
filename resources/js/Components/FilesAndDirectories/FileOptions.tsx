import React from "react";
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
    const handleEdit = () => {
        GET<string>(route("v1.web.customer.files.edit", file.id))
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
            .catch((e) => {
                toast.error("There is been an error");
                if (refetch) {
                    refetch();
                }
            });
    };

    return (
        <div className={"flex items-center justify-between px-5 gap-1"}>
            <PushFileUpdateButton file={file} refetch={refetch} />
            <button
                type={"button"}
                className="hover:bg-white-secondary p-1 rounded-md disabled:cursor-not-allowed text-success disabled:text-white disabled:bg-gray-300"
                disabled={isDownloading || file.status == FileStatusEnum.LOCKED}
                onClick={() => {
                    handleEdit();
                }}
            >
                {isDownloading ? (
                    <LoadingSpinner className={"w-5 h-5"} />
                ) : (
                    <Pencil className="w-5 h-5" />
                )}
            </button>

            <DeleteFileButton file={file} refetch={refetch} />
        </div>
    );
};

export default FileOptions;
