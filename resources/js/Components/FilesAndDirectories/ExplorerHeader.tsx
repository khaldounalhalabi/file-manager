import { Plus } from "lucide-react";
import React, { createContext, FormEvent, useEffect, useState } from "react";
import Modal from "@/Components/ui/Modal";
import Form from "@/Components/form/Form";
import { Link, useForm } from "@inertiajs/react";
import Input from "@/Components/form/fields/Input";
import {
    InfiniteData,
    QueryObserverResult,
    RefetchOptions,
} from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import { Directory } from "@/Models/Directory";
import { POST } from "@/Modules/Http";
import DownloadFile from "@/Hooks/DownloadFile";
import { getCsrf, role } from "@/helper";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";

export const SelectedFilesContext = createContext<{
    selected: number[];
    setSelected: (
        value: ((prevState: number[]) => number[]) | number[],
    ) => void;
}>({ selected: [], setSelected: () => undefined });

const ExplorerHeader = ({
    refetch,
    directory,
    children = undefined,
    groupId = undefined,
}: {
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
    directory?: Directory;
    children: any;
    groupId?: number;
}) => {
    const authRole = role();
    const [selectedFiles, setSelectedFiles] = useState<number[]>([]);
    const [openNewFolder, setOpenNewFolder] = useState<boolean>(false);
    const [openNewFile, setOpenNewFile] = useState<boolean>(false);
    const { post, setData, processing, wasSuccessful, data, transform } =
        useForm<{
            name: string;
            parent_id?: number;
        }>();

    const {
        post: postFile,
        setData: setFile,
        processing: processingFile,
        wasSuccessful: successFile,
        transform: transformFile,
    } = useForm<{
        file?: File;
        directory_id: number;
    }>();

    const onSubmitNewFolder = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        transform((data) => {
            const additions: Record<string, any> = {};
            if (authRole == "admin") {
                additions.group_id = groupId;
            }

            if (directory) {
                additions.parent_id = directory.id;
            }

            return {
                ...data,
                ...additions,
            };
        });

        post(route(`v1.web.${authRole}.directories.store`));
    };

    const { downloadFile, isLoading } = DownloadFile();
    const [isDownloadingMultiple, setIsDownloadingMultiple] = useState(false);
    const onClickUpdateMultipleFilesButton = () => {
        setIsDownloadingMultiple(true);
        POST<{ url: string }>(route("v1.web.customer.files.edit.multiple"), {
            files_ids: selectedFiles,
        })
            .then((res) => {
                if (res.data.url) {
                    downloadFile(
                        () =>
                            fetch(res.data.url ?? "", {
                                method: "GET",
                                headers: {
                                    "X-CSRF-TOKEN": getCsrf() ?? "",
                                    "Content-Type": "application/html",
                                },
                            }),
                        "ultimate-file-manager",
                    ).then(() => {
                        if (refetch) {
                            refetch();
                        }
                        setIsDownloadingMultiple(false);
                    });
                } else {
                    setIsDownloadingMultiple(false);
                    console.error(res);
                }
            })
            .catch((error) => {
                setIsDownloadingMultiple(false);
                console.error(error);
            });
    };

    const onSubmitNewFile = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (directory) {
            transformFile((data) => ({
                ...data,
                directory_id: directory.id,
            }));
        }
        postFile(route(`v1.web.${authRole}.files.store`));
    };

    useEffect(() => {
        if (wasSuccessful) {
            if (refetch) {
                refetch();
            }
            setOpenNewFolder(false);
        }

        if (successFile) {
            setOpenNewFile(false);
        }

        setSelectedFiles([]);
    }, [wasSuccessful, successFile, isLoading, directory]);

    return (
        <SelectedFilesContext.Provider
            value={{ selected: selectedFiles, setSelected: setSelectedFiles }}
        >
            <div
                className={
                    "flex items-center justify-items-start w-full border-b border-b-brand mb-5 p-5"
                }
            >
                <div className={"flex items-center justify-start gap-5 w-full"}>
                    <button
                        className={
                            "text-brand flex items-center gap-2 border p-3 border-gray-200 hover:shadow-md rounded-md"
                        }
                        onClick={() => setOpenNewFolder(true)}
                        type={"button"}
                    >
                        New Folder <Plus />
                    </button>

                    <Modal
                        isOpen={openNewFolder}
                        onClose={() => setOpenNewFolder(false)}
                    >
                        <Form
                            onSubmit={onSubmitNewFolder}
                            processing={processing}
                            backButton={false}
                        >
                            <Input
                                name={"name"}
                                type={"text"}
                                onInput={(e) => {
                                    setData("name", e.target.value);
                                }}
                                required={true}
                                label={"Folder name"}
                            />
                        </Form>
                    </Modal>

                    {directory ? (
                        <>
                            <button
                                className={
                                    "text-brand flex items-center gap-2 border p-3 border-gray-200 hover:shadow-md rounded-md"
                                }
                                type={"button"}
                                onClick={() => {
                                    setOpenNewFile(true);
                                }}
                            >
                                New File
                                <Plus />
                            </button>
                            <Modal
                                isOpen={openNewFile}
                                onClose={() => {
                                    setOpenNewFile(false);
                                }}
                            >
                                <Form
                                    onSubmit={onSubmitNewFile}
                                    processing={processingFile}
                                    backButton={false}
                                >
                                    <Input
                                        name={"File"}
                                        type={"file"}
                                        onChange={(e) => {
                                            setFile(
                                                "file",
                                                e.target.files?.[0],
                                            );
                                        }}
                                        required={true}
                                        label={"File"}
                                    />
                                    <Input
                                        name={"directory_id"}
                                        className={"hidden"}
                                    />
                                </Form>
                            </Modal>
                        </>
                    ) : undefined}

                    {selectedFiles.length > 0 && (
                        <button
                            className={
                                "text-brand flex items-center gap-2 border p-3 border-gray-200 hover:shadow-md rounded-md"
                            }
                            onClick={(e) => {
                                e.preventDefault();
                                onClickUpdateMultipleFilesButton();
                            }}
                            type={"button"}
                        >
                            Edit ({selectedFiles.length}) files
                            {isDownloadingMultiple && <LoadingSpinner className={"dark:text-white"}/>}
                        </button>
                    )}
                </div>
                <div className={"w-full flex items-center justify-end"}>
                    <Link
                        className={"text-brand hover:underline"}
                        href={
                            authRole == "admin"
                                ? route(
                                      "v1.web.admin.groups.directories",
                                      groupId,
                                  )
                                : route("v1.web.customer.directories.root")
                        }
                    >
                        /root
                    </Link>
                    {directory &&
                        directory.path.map((path) => (
                            <Link
                                href={route(
                                    `v1.web.${authRole}.directories.show`,
                                    path.id,
                                )}
                                key={path.id}
                                className={"text-brand"}
                            >
                                /{" "}
                                <span className={"hover:underline"}>
                                    {path.name}
                                </span>
                            </Link>
                        ))}
                </div>
            </div>

            {children}
        </SelectedFilesContext.Provider>
    );
};

export default ExplorerHeader;
