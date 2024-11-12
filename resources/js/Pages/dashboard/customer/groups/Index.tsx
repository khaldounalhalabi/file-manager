import ActionsButtons from "@/Components/Datatable/ActionsButtons";
import DataTable from "@/Components/Datatable/DataTable";
import { Group } from "@/Models/Group";
import { PaginatedResponse } from "@/Models/Response";
import { Link } from "@inertiajs/react";
import { ArrowLeftRight } from "lucide-react";
import { user } from "@/helper";
import SendInvitationModal from "@/Components/groups/SendInvitationModal";
import { GET } from "@/Modules/Http";

const Index = () => {
    return (
        <DataTable
            title="Group Table"
            createUrl={route("v1.web.customer.groups.create")}
            getDataArray={(res) => res.data}
            getTotalPages={(res) => res?.pagination_data?.total_pages ?? 0}
            getTotalRecords={(res) => res.pagination_data?.total ?? 0}
            api={(
                page?: number | undefined,
                search?: string | undefined,
                sortCol?: string | undefined,
                sortDir?: string | undefined,
                perPage?: number | undefined,
                params?: object | undefined,
            ): Promise<PaginatedResponse<Group>> =>
                GET(
                    route("v1.web.customer.groups.data", {
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
                    label: "Name",
                    name: "name",
                    sortable: true,
                },
                {
                    label: "Options",
                    render: (_data, group, setHidden) => (
                        <ActionsButtons
                            buttons={
                                group && group.owner_id == user()?.id
                                    ? ["delete", "edit", "show"]
                                    : ["show"]
                            }
                            baseUrl={route("v1.web.customer.groups.index")}
                            id={group?.id ?? 0}
                            setHidden={setHidden}
                        >
                            <>
                                {user()?.group_id != group?.id ? (
                                    <Link
                                        href={route(
                                            "v1.web.customer.groups.change",
                                            group?.id ?? 0,
                                        )}
                                    >
                                        <ArrowLeftRight
                                            className={"w-5 h-5 text-brand"}
                                        />
                                    </Link>
                                ) : (
                                    <></>
                                )}
                                {group && group.owner_id == user()?.id && (
                                    <SendInvitationModal group={group} />
                                )}
                            </>
                        </ActionsButtons>
                    ),
                },
            ]}
        />
    );
};

export default Index;
