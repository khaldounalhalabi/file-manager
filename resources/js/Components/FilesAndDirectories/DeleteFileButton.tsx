import { File } from "@/Models/File";
import {
    InfiniteData,
    QueryObserverResult,
    RefetchOptions,
} from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import { FileStatusEnum } from "@/Enums/FileStatusEnum";
import { swal } from "@/helper";
import { DELETE } from "@/Modules/Http";
import { ResponseCodeEnum } from "@/Enums/ResponseCodeEnum";
import { toast } from "react-toastify";
import Trash from "@/Components/icons/Trash";
import React from "react";

const DeleteFileButton = ({
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
    return (
        <button
            className="hover:bg-white-secondary p-1 rounded-md text-danger disabled:text-white disabled:bg-gray-300"
            type={"button"}
            disabled={file?.status == FileStatusEnum.LOCKED}
            onClick={() => {
                swal.fire({
                    title: "Do you want to Delete this item ?",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: `No`,
                    confirmButtonColor: "#007BFF",
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (file) {
                            DELETE<boolean>(
                                route("v1.web.customer.files.destroy", file.id),
                            )
                                .then((res) => {
                                    if (res.code == ResponseCodeEnum.OK) {
                                        toast.success("Deleted !");
                                        if (refetch) {
                                            refetch();
                                        }
                                    } else {
                                        toast.error(
                                            "There Is Been An Error While Deleting",
                                        );
                                    }
                                })
                                .catch((e) => {
                                    toast.error(
                                        "There Is Been An Error While Deleting",
                                    );
                                    console.log(e);
                                });
                        }
                    } else if (result.isDenied) {
                        toast.info("Didn't Delete");
                    }
                });
            }}
        >
            <Trash className="w-5 h-5" />
        </button>
    );
};

export default DeleteFileButton;
