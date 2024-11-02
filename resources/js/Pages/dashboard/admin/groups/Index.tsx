import ActionsButtons from "@/Components/Datatable/ActionsButtons";
import DataTable from "@/Components/Datatable/DataTable";
import { Group } from "@/Models/Group";
import { PaginatedResponse } from "@/Models/Response";

const Index = ({ exportables }: { exportables: string[] }) => {
    return (
        <DataTable
            title="Group Table"
            createUrl={route("v1.web.admin.groups.create")}
            exportRoute={route("v1.web.admin.groups.export")}
            exportables={exportables}
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
                fetch(
                    route("v1.web.admin.groups.data", {
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
                    label: "Name",
                    name: "name",
                    sortable: true,
                },
                {
                    label: "Owner",
                    name: "owner.first_name",
                    render: (name, group) =>
                        `${group?.owner?.first_name} ${group?.owner?.last_name}`,
                },
                {
                    label: "Options",
                    render: (_data, group, setHidden, revalidate) => (
                        <ActionsButtons
                            buttons={["delete", "edit", "show"]}
                            baseUrl={route("v1.web.admin.groups.index")}
                            id={group?.id ?? 0}
                            setHidden={setHidden}
                        />
                    ),
                },
            ]}
        />
    );
};

export default Index;