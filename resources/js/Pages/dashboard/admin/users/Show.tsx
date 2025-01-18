import Button from "@/Components/ui/Button";
import PageCard from "@/Components/ui/PageCard";
import { User } from "@/Models/User";
import { Link } from "@inertiajs/react";

import SmallTextField from "@/Components/Show/SmallTextField";
import { PaginatedResponse } from "@/Models/Response";
import { FileLog } from "@/Models/FileLog";
import dayjs from "dayjs";
import DataTable from "@/Components/Datatable/DataTable";
import ImagePreview from "@/Components/Show/ImagePreview";

const Show = ({ user }: { user: User }) => {
    return (
        <>
            <PageCard
                title="User Details"
                actions={
                    <div className="flex justify-between items-center">
                        <Link href={route("v1.web.admin.users.edit", user.id)}>
                            <Button>Edit</Button>
                        </Link>
                    </div>
                }
            >
                <div className="gap-5 grid grid-cols-1 md:grid-cols-2">
                    <SmallTextField
                        label="First Name "
                        value={user.first_name}
                    />
                    <SmallTextField label="Last Name " value={user.last_name} />
                    <SmallTextField label="Email " value={user.email} />
                    <SmallTextField
                        label={"Role"}
                        value={user.roles?.map((role) => role.name).join(" , ")}
                    />
                    <ImagePreview src={user.profile?.path ?? ""} />
                </div>
            </PageCard>

            <div className={"mt-5"}>
                <DataTable
                    title="Logs"
                    // exportRoute={route(`v1.web.${authRole}.files.logs.export`, {
                    //     fileId: fileId,
                    // })}
                    getDataArray={(res) => res.data}
                    getTotalPages={(res) =>
                        res?.pagination_data?.total_pages ?? 0
                    }
                    getTotalRecords={(res) => res.pagination_data?.total ?? 0}
                    api={(
                        page?: number | undefined,
                        search?: string | undefined,
                        sortCol?: string | undefined,
                        sortDir?: string | undefined,
                        perPage?: number | undefined,
                        params?: object | undefined,
                    ): Promise<PaginatedResponse<FileLog>> =>
                        fetch(
                            route(`v1.web.admin.users.logs`, {
                                userId: user.id,
                                page: page,
                                search: search,
                                sort_col: sortCol,
                                sort_dir: sortDir,
                                limit: perPage,
                                ...params,
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
                    schema={[
                        {
                            name: "id",
                            label: "ID",
                            sortable: true,
                        },
                        {
                            label: "Event",
                            name: "event_type",
                            sortable: true,
                        },
                        {
                            label: "Happened At",
                            name: "happened_at",
                            sortable: true,
                            render: (happened_at) =>
                                dayjs(happened_at).format("YYYY-MM-DD h:i:s"),
                        },
                    ]}
                />
            </div>
        </>
    );
};

export default Show;
