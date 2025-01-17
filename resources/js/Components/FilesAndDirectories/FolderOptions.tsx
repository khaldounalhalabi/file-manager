import Trash from "@/Components/icons/Trash";
import { role, swal, user as AuthUser } from "@/helper";
import { toast } from "react-toastify";
import React, { FormEvent, useEffect, useState } from "react";
import { useForm, usePage } from "@inertiajs/react";
import { MiddlewareProps } from "@/types";
import {
    InfiniteData,
    QueryObserverResult,
    RefetchOptions,
} from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import Pencil from "@/Components/icons/Pencil";
import Modal from "@/Components/ui/Modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/fields/Input";
import { Directory } from "@/Models/Directory";

const FolderOptions = ({
    directory,
    refetch,
}: {
    directory: Directory;
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
    const authRole = role();
    const csrf = usePage<MiddlewareProps>().props.csrfToken;
    const [openEdit, setOpenEdit] = useState<boolean>(false);
    const user = AuthUser();
    const { post, setData, processing, wasSuccessful } = useForm<{
        name?: string;
        _method: string;
    }>({
        name: directory.name,
        _method: "PUT",
    });

    const onSubmitUpdateFolder = (e: FormEvent<HTMLFormElement>) => {
        setData("_method", "PUT");
        e.preventDefault();
        post(route(`v1.web.${authRole}.directories.update`, directory.id));
    };

    useEffect(() => {
        if (wasSuccessful) {
            if (refetch) {
                refetch();
            }
            setOpenEdit(false);
        }
    }, [wasSuccessful]);

    return (
        <div className={"flex items-center justify-between px-5 gap-1"}>
            <button
                type={"button"}
                className="hover:bg-white-secondary p-1 rounded-md"
                onClick={() => {
                    setOpenEdit(true);
                }}
            >
                <Pencil className="w-5 h-5 text-success" />
            </button>
            <Modal
                isOpen={openEdit}
                onClose={() => {
                    setOpenEdit(false);
                }}
            >
                <Form
                    onSubmit={onSubmitUpdateFolder}
                    backButton={false}
                    processing={processing}
                >
                    <Input
                        name={"name"}
                        type={"text"}
                        label={"Folder Name"}
                        onChange={(e) => {
                            setData("name", e.target.value);
                        }}
                        defaultValue={directory.name}
                    />
                </Form>
            </Modal>
            {(directory.owner_id == user?.id ||
                user?.group?.owner_id == user?.id ||
                authRole == "admin") && (
                <button
                    className="hover:bg-white-secondary p-1 rounded-md"
                    type={"button"}
                >
                    <Trash
                        className="w-5 h-5 text-danger"
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
                                    if (directory) {
                                        fetch(
                                            route(
                                                `v1.web.${authRole}.directories.destroy`,
                                                directory.id,
                                            ),
                                            {
                                                method: "DELETE",
                                                headers: {
                                                    "X-CSRF-TOKEN": csrf,
                                                },
                                            },
                                        )
                                            .then(() => {
                                                toast.success("Deleted !");
                                                if (refetch) {
                                                    refetch();
                                                }
                                            })
                                            .catch(() => {
                                                toast.error(
                                                    "There Is Been An Error While Deleting",
                                                );
                                            });
                                    }
                                } else if (result.isDenied) {
                                    toast.info("Didn't Delete");
                                }
                            });
                        }}
                    />
                </button>
            )}
        </div>
    );
};

export default FolderOptions;
