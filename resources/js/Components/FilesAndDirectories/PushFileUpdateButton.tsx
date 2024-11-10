import { File as FileModel } from "@/Models/File";
import {
    InfiniteData,
    QueryObserverResult,
    RefetchOptions,
} from "@tanstack/react-query";
import { PaginatedResponse } from "@/Models/Response";
import { FileStatusEnum } from "@/Enums/FileStatusEnum";
import { ArrowUpFromLine } from "lucide-react";
import React, { FormEvent, useEffect, useState } from "react";
import Modal from "@/Components/ui/Modal";
import Form from "@/Components/form/Form";
import { useForm } from "@inertiajs/react";
import Input from "@/Components/form/fields/Input";

const PushFileUpdateButton = ({
    file,
    refetch,
}: {
    file: FileModel;
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
    const [modalOpen, openModal] = useState<boolean>(false);
    const { post, setData, processing, wasSuccessful, transform, progress } =
        useForm<{
            file?: File;
            file_id: number;
            _method: "POST" | "PUT";
        }>();

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        transform((data) => ({ ...data, file_id: file.id, _method: "PUT" }));
        post(route("v1.web.customer.files.update"));
    };

    useEffect(() => {
        if (wasSuccessful) {
            if (refetch) {
                openModal(false);
                refetch();
            }
        }
    }, [wasSuccessful]);

    return (
        <>
            <button
                type={"button"}
                className={
                    "hover:bg-white-secondary p-1 rounded-md disabled:cursor-not-allowed text-brand disabled:text-white disabled:bg-gray-300"
                }
                disabled={file.status == FileStatusEnum.UNLOCKED}
                onClick={() => openModal(true)}
            >
                <ArrowUpFromLine className={"w-5 h-5"} />
            </button>
            <Modal isOpen={modalOpen} onClose={() => openModal(false)}>
                <Form
                    onSubmit={handleSubmit}
                    processing={processing}
                    backButton={false}
                >
                    <Input
                        type={"file"}
                        name={"file"}
                        label={`File : ${file.name}`}
                        required={true}
                        onChange={(e) => {
                            setData("file", e.target?.files?.[0]);
                        }}
                    />
                    <div className={"flex items-center justify-start"}></div>
                </Form>
            </Modal>
        </>
    );
};

export default PushFileUpdateButton;
