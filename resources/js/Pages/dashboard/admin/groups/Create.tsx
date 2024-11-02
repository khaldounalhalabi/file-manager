import { useForm } from "@inertiajs/react";
import { FormEvent } from "react";
import PageCard from "@/Components/ui/PageCard";
import Form from "@/Components/form/Form";

import Input from "@/Components/form/fields/Input";
import ApiSelect from "@/Components/form/fields/Select/ApiSelect";
import { User } from "@/Models/User";
import { PaginatedResponse } from "@/Models/Response";

const Create = () => {
    const { post, setData, processing } = useForm<{
        name: string;
        owner_id: number;
        users?: number[] | string[];
        _method?: "PUT" | "POST";
    }>();

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route("v1.web.admin.groups.store"));
    };

    return (
        <PageCard title="Create Group">
            <Form onSubmit={onSubmit} processing={processing}>
                <div
                    className={`grid grid-cols-1 md:grid-cols-2 gap-5 items-end`}
                >
                    <Input
                        name={"name"}
                        label={"Name"}
                        onChange={(e) => setData("name", e.target.value)}
                        type={"text"}
                        required={true}
                    />
                    <ApiSelect
                        api={(
                            page?: number | undefined,
                            search?: string | undefined,
                        ): Promise<PaginatedResponse<User>> =>
                            fetch(
                                route("v1.web.admin.users.customers", {
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
                        name={"owner_id"}
                        label={"Owner"}
                        required={true}
                        onChange={(e) => {
                            setData("owner_id", Number(e.target.value ?? 0));
                        }}
                        getOptionLabel={(data) =>
                            `${data.first_name} ${data.last_name}`
                        }
                        optionValue={"id"}
                    />

                    <ApiSelect
                        api={(
                            page?: number | undefined,
                            search?: string | undefined,
                        ): Promise<PaginatedResponse<User>> =>
                            fetch(
                                route("v1.web.admin.users.customers", {
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
                        name={"users"}
                        label={"Users"}
                        onChange={(e) => {
                            setData("users", e.target.value?.split(","));
                        }}
                        getOptionLabel={(data) =>
                            `${data.first_name} ${data.last_name}`
                        }
                        optionValue={"id"}
                        isMultiple={true}
                        closeOnSelect={false}
                    />
                </div>
            </Form>
        </PageCard>
    );
};

export default Create;
