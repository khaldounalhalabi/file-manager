import Button from "@/Components/ui/Button";
import PageCard from "@/Components/ui/PageCard";
import { Group } from "@/Models/Group";
import { Link } from "@inertiajs/react";

import SmallTextField from "@/Components/Show/SmallTextField";
import { PaginatedResponse } from "@/Models/Response";
import ActionsButtons from "@/Components/Datatable/ActionsButtons";
import DataTable from "@/Components/Datatable/DataTable";
import { User } from "@/Models/User";
import { GET } from "@/Modules/Http";

const Show = ({ group }: { group: Group }) => {
    return (
        <>
            <PageCard
                title="Group Details"
                actions={
                    <div className="flex justify-between items-center">
                        <Link
                            href={route("v1.web.admin.groups.edit", group.id)}
                        >
                            <Button>Edit</Button>
                        </Link>
                    </div>
                }
            >
                <div className="gap-5 grid grid-cols-1 md:grid-cols-2">
                    <SmallTextField label="Name " value={group.name} />
                    <SmallTextField
                        label="Owner"
                        value={
                            group.owner?.first_name +
                            " " +
                            group.owner?.last_name
                        }
                    />
                </div>
            </PageCard>
            <div className={"my-10"}>
                <DataTable
                    title="Group users"
                    exportRoute={route("v1.web.admin.groups.export")}
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
                    ): Promise<PaginatedResponse<User>> =>
                        GET(
                            route("v1.web.admin.groups.users", {
                                groupId: group.id,
                                page: page,
                                search: search,
                                sort_col: sortCol,
                                sort_dir: sortDir,
                                limit: perPage,
                                ...params,
                            }),
                        )
                    }
                    schema={[
                        {
                            name: "id",
                            label: "ID",
                            sortable: true,
                        },
                        {
                            label: "Email",
                            name: "email",
                            sortable: true,
                        },
                        {
                            label: "Name",
                            name: "first_name",
                            sortable: true,
                            render: (name, user) =>
                                `${user?.first_name} ${user?.last_name}`,
                        },
                        {
                            label: "Options",
                            render: (_data, group, setHidden) => (
                                <ActionsButtons
                                    buttons={["edit", "show"]}
                                    baseUrl={route("v1.web.admin.users.index")}
                                    id={group?.id ?? 0}
                                    setHidden={setHidden}
                                />
                            ),
                        },
                    ]}
                    isLast={(res) => res.pagination_data?.is_last ?? true}
                    isFirst={(res) => res.pagination_data?.is_first ?? true}
                />
            </div>
        </>
    );
};

export default Show;
