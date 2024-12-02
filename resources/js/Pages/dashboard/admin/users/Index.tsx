import ActionsButtons from "@/Components/Datatable/ActionsButtons";
import DataTable from "@/Components/Datatable/DataTable";
import { User } from "@/Models/User";
import { PaginatedResponse } from "@/Models/Response";
import { GET } from "@/Modules/Http";

const Index = ({ exportables }: { exportables: string[] }) => {
    return (
        <DataTable
            title="User Table"
            createUrl={route("v1.web.admin.users.create")}
            exportRoute={route("v1.web.admin.users.export")}
            exportables={exportables}
            getDataArray={(res) => res.data}
            getTotalPages={(res) => res?.pagination_data?.total_pages ?? 0}
            getTotalRecords={(res) => res.pagination_data?.total ?? 0}
            isFirst={(res) => res.pagination_data?.is_first ?? true}
            isLast={(res) => res.pagination_data?.is_last ?? true}
            api={(
                page?: number | undefined,
                search?: string | undefined,
                sortCol?: string | undefined,
                sortDir?: string | undefined,
                perPage?: number | undefined,
                params?: object | undefined,
            ): Promise<PaginatedResponse<User>> =>
                GET(
                    route("v1.web.admin.users.data", {
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
                    label: "First Name",
                    name: "first_name",
                    sortable: true,
                },
                {
                    label: "Last Name",
                    name: "last_name",
                    sortable: true,
                },
                {
                    label: "Email",
                    name: "email",
                    sortable: true,
                },
                {
                    label: "Role",
                    render: (_data, user) => user?.roles?.[0]?.name,
                },
                {
                    label: "Options",
                    render: (_data, user, setHidden) => (
                        <ActionsButtons
                            buttons={["delete", "edit", "show"]}
                            baseUrl={route("v1.web.admin.users.index")}
                            id={user?.id ?? 0}
                            setHidden={setHidden}
                        />
                    ),
                },
            ]}
        />
    );
};

export default Index;
