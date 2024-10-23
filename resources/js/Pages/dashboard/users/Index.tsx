import ActionsButtons from "@/Components/Datatable/ActionsButtons";
import DataTable from "@/Components/Datatable/DataTable";
import { User } from "@/Models/User";
import { PaginatedResponse } from "@/Models/Response";

const Index = ({ exportables }: { exportables: string[] }) => {
    return (
        <DataTable
            title="User Table"
            createUrl={route("v1.web.admin.users.create")}
            importRoute={route("v1.web.admin.users.import")}
            exportRoute={route("v1.web.admin.users.export")}
            importExampleRoute={route("v1.web.admin.users.get.example")}
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
            ): Promise<PaginatedResponse<User>> =>
                fetch(
                    route("v1.web.admin.users.data", {
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
                    render: (_data, user, setHidden, revalidate) => (
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
