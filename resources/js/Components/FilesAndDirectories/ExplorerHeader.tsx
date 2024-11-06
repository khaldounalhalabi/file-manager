import { Plus } from "lucide-react";
import { FormEvent, useEffect, useState } from "react";
import Modal from "@/Components/ui/Modal";
import Form from "@/Components/form/Form";
import { useForm } from "@inertiajs/react";
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
    refetch?: (
        options?: RefetchOptions,
    ) => Promise<
        QueryObserverResult<InfiniteData<PaginatedResponse<any>, any>, Error>
    >;
    directory?: Directory;
}) => {
    const [openNewFolder, setOpenNewFolder] = useState<boolean>(false);
    const { post, setData, processing, wasSuccessful } = useForm<{
        name: string;
        parent_id?: number;
    }>();

    const onSubmitNewFolder = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (directory) {
            setData("parent_id", directory.id);
        }
        post(route("v1.web.customer.directories.store"));
    };

    useEffect(() => {
        if (wasSuccessful) {
            if (refetch) {
                refetch();
            }
            setOpenNewFolder(false);
        }
    }, [wasSuccessful]);
    return (
        <div
            className={
                "flex items-center justify-items-start gap-5 w-full border-b border-b-brand mb-5 p-5"
            }
        >
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
                        onChange={(e) => {
                            setData("name", e.target.value);
                        }}
                        required={true}
                        label={"Folder name"}
                    />
                </Form>
            </Modal>

            {directory ? (
                <button
                    className={
                        "text-brand flex items-center gap-2 border p-3 border-gray-200 hover:shadow-md rounded-md"
                    }
                    type={"button"}
                >
                    New File <Plus />
                </button>
            ) : undefined}
        </div>
    );
};

export default ExplorerHeader;
