import DataTable from "@/Components/Datatable/DataTable";
import { FileLog } from "@/Models/FileLog";
import { PaginatedResponse } from "@/Models/Response";
import dayjs from "dayjs";
import { role } from "@/helper";

const exportables: string[] = [];
const FileLogsTable = ({ fileId }: { fileId: number }) => {
    const authRole = role();
    return (
        <DataTable
            title="Logs"
            exportRoute={route(`v1.web.${authRole}.files.logs.export`, {
                fileId: fileId,
            })}
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
            ): Promise<PaginatedResponse<FileLog>> =>
                fetch(
                    route(`v1.web.${authRole}.files.logs`, {
                        fileId: fileId,
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
                    label: "User name",
                    name: "user.first_name",
                    render: (firstName, log) =>
                        log?.user?.first_name + " " + log?.user?.last_name,
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
    );
};

export default FileLogsTable;
