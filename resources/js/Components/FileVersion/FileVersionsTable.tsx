import DataTable from "@/Components/Datatable/DataTable";
import { PaginatedResponse } from "@/Models/Response";
import { GET } from "@/Modules/Http";
import { FileVersion } from "@/Models/FileVersion";
import dayjs from "dayjs";
import DownloadVersionButton from "@/Components/FileVersion/DownloadVersionButton";
import VersionCheckbox from "@/Components/FileVersion/VersionCheckbox";
import { role } from "@/helper";

const FileVersionsTable = ({ fileId }: { fileId: number }) => {
    const authRole = role();
    return (
        <DataTable
            title="versions"
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
            ): Promise<PaginatedResponse<FileVersion>> =>
                GET(
                    route(`v1.web.${authRole}.files.versions`, {
                        fileId: fileId,
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
                    label: "Version",
                    name: "version",
                    sortable: true,
                },
                {
                    label: "Created at",
                    name: "created_at",
                    render: (created_at) =>
                        dayjs(created_at).format("YYYY-MM-DD"),
                },
                {
                    label: "Options",
                    render: (_data, version) => (
                        <div className={"flex items-center gap-1"}>
                            {version && (
                                <DownloadVersionButton version={version} />
                            )}
                            {version && <VersionCheckbox version={version} />}
                        </div>
                    ),
                },
            ]}
        />
    );
};
export default FileVersionsTable;
