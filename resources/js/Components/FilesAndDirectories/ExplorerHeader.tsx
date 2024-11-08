import { Plus } from "lucide-react";
import { FormEvent, useEffect, useState } from "react";
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

const ExplorerHeader = ({
    refetch,
    directory,
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
}) => {
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
        if (directory) {
            transform((data) => ({
                ...data,
                parent_id: directory.id,
            }));
        }
        console.log(data);
        post(route("v1.web.customer.directories.store"));
    };

    const onSubmitNewFile = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (directory) {
            transformFile((data) => ({
                ...data,
                directory_id: directory.id,
            }));
        }
        postFile(route("v1.web.customer.files.store"));
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
    }, [wasSuccessful, successFile]);
    return (
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
                                        setFile("file", e.target.files?.[0]);
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
            </div>
            <div className={"w-full flex items-center justify-end"}>
                <Link
                    className={"text-brand hover:underline"}
                    href={route("v1.web.customer.directories.root")}
                >
                    /root
                </Link>
                {directory &&
                    directory.path.map((path) => (
                        <Link
                            href={route(
                                "v1.web.customer.directories.show",
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
    );
};

export default ExplorerHeader;
