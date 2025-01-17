import { Group } from "@/Models/Group";
import { Mail } from "lucide-react";
import React, { useEffect, useState } from "react";
import Modal from "@/Components/ui/Modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/fields/Input";
import { useForm } from "@inertiajs/react";
import { PaginatedResponse } from "@/Models/Response";
import { User } from "@/Models/User";
import ApiSelect from "@/Components/form/fields/Select/ApiSelect";

const SendInvitationModal = ({ group }: { group: Group }) => {
    const [open, setOpen] = useState(false);
    const { post, setData, processing, wasSuccessful, transform } = useForm<{
        email?: string;
        group_id: number;
        user_id?: number;
    }>();

    const onSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        transform((data) => ({
            ...data,
            group_id: group.id,
        }));

        post(route("v1.web.customer.groups.invite"));
    };

    useEffect(() => {
        if (wasSuccessful) {
            setOpen(false);
        }
    }, [wasSuccessful]);

    return (
        <>
            <button
                onClick={() => {
                    setOpen(true);
                }}
            >
                <Mail className={"w-5 h-5 text-warning"} />
            </button>
            <Modal
                isOpen={open}
                onClose={() => {
                    setOpen(false);
                }}
                containerClasses={"min-h-[400px]"}
            >
                <Form
                    onSubmit={onSubmit}
                    backButton={false}
                    processing={processing}
                >
                    <div
                        className={
                            "flex flex-col items-start justify-between gap-10 h-full"
                        }
                    >
                        <Input
                            name={"email"}
                            label={"User Email"}
                            type={"email"}
                            onChange={(e) => {
                                setData("email", e.target.value);
                            }}
                        />
                        <ApiSelect
                            api={(
                                page?: number | undefined,
                                search?: string | undefined,
                            ): Promise<PaginatedResponse<User>> =>
                                fetch(
                                    route("v1.web.customer.users.customers", {
                                        page: page,
                                        search: search,
                                    }),
                                    {
                                        method: "GET",
                                        headers: {
                                            accept: "application/html",
                                            "Content-Type": "application/html",
                                        },
                                    },
                                ).then((res) => res.json())
                            }
                            getDataArray={(response: PaginatedResponse<User>) =>
                                response.data
                            }
                            getIsLast={(res) =>
                                res.pagination_data?.is_last ?? true
                            }
                            getTotalPages={(res) =>
                                res.pagination_data?.total_pages ?? 1
                            }
                            name={"user_id"}
                            label={"Or just select an existing user : "}
                            required={true}
                            onChange={(e) => {
                                setData("user_id", Number(e.target.value ?? 0));
                            }}
                            getOptionLabel={(data) =>
                                `${data.first_name} ${data.last_name}`
                            }
                            optionValue={"id"}
                        />
                    </div>
                </Form>
            </Modal>
        </>
    );
};

export default SendInvitationModal;
