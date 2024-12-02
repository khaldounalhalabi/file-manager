import { File } from "@/Models/File";
import PageCard from "@/Components/ui/PageCard";
import IconFile from "@/Components/FilesAndDirectories/FileIcon";
import dayjs from "dayjs";
import FileVersionsTable from "@/Components/FileVersion/FileVersionsTable";
import { Link, router } from "@inertiajs/react";
import React, { createContext, useState } from "react";
import FileLogsTable from "@/Components/file-logs/FileLogsTable";

export const SelectedDiffFilesContext = createContext<{
    selected: number[];
    setSelected: (
        value: ((prevState: number[]) => number[]) | number[],
    ) => void;
}>({ selected: [], setSelected: () => undefined });

const Show = ({ file }: { file: File }) => {
    const [selectedDiff, setSelectedDiff] = useState<number[]>([]);
    const [isVersions, setIsVersions] = useState(true);
    return (
        <SelectedDiffFilesContext.Provider
            value={{ selected: selectedDiff, setSelected: setSelectedDiff }}
        >
            <PageCard>
                <div
                    className={
                        "flex gap-5 items-center bg-gray-100 shadow-sm rounded-md p-5"
                    }
                >
                    <IconFile
                        extension={file?.last_version?.file_path?.extension}
                    />
                    <div className={"flex flex-col gap-1"}>
                        <span>Name : {file.name}</span>
                        <span>
                            Extension : {file.last_version?.file_path.extension}
                        </span>
                        <span>
                            Size : {file.last_version.file_path.size} KB
                        </span>
                    </div>
                    <div className={"flex flex-col gap-1"}>
                        <span>
                            Owner :{" "}
                            {file.owner?.first_name +
                                " " +
                                file.owner?.last_name}
                        </span>
                        <span>Version : {file.last_version?.version}</span>
                        <span>
                            Last Modified :{" "}
                            {dayjs(file.last_version.updated_at).format(
                                "YYYY-MM-DD",
                            )}
                        </span>
                    </div>
                    {selectedDiff.length == 2 && (
                        <button
                            type={"button"}
                            className={
                                "text-brand flex items-center gap-2 border p-3 border-gray-200 hover:shadow-md rounded-md"
                            }
                            onClick={() => {
                                router.post(route("v1.web.customer.get.diff"), {
                                    first_file_id: selectedDiff.shift(),
                                    second_file_id: selectedDiff.shift(),
                                });
                            }}
                        >
                            Compare Selected
                        </button>
                    )}
                    <div
                        className={
                            "w-full self-end flex items-center justify-end"
                        }
                    >
                        <Link
                            className={"text-brand hover:underline"}
                            href={route("v1.web.customer.directories.root")}
                        >
                            /root
                        </Link>
                        {file.directory &&
                            file.directory.path.map((path) => (
                                <Link
                                    href={route(
                                        "v1.web.customer.directories.show",
                                        path.id,
                                    )}
                                    key={path.id}
                                    className={"text-brand"}
                                >
                                    /{" "}
                                    <span className={"hover:underline"}>
                                        {path.name}
                                    </span>
                                </Link>
                            ))}
                    </div>
                </div>
            </PageCard>
            <div className={"mt-5 rounded-md p-5"}>
                <div
                    className={
                        "my-5 bg-primary rounded-md p-5 flex items-center gap-3"
                    }
                >
                    <div
                        className={`${isVersions ? "text-primary underline bg-white" : "text-white "} hover:cursor-pointer hover:text-primary hover:underline p-3 rounded-sm hover:bg-white`}
                        onClick={() => setIsVersions(true)}
                    >
                        Versions
                    </div>
                    <div
                        className={`${!isVersions ? "text-primary underline bg-white" : "text-white "} hover:cursor-pointer hover:text-primary hover:underline p-3 rounded-sm hover:bg-white`}
                        onClick={() => setIsVersions(false)}
                    >
                        Logs
                    </div>
                </div>
                {isVersions && <FileVersionsTable fileId={file.id} />}
                {!isVersions && <FileLogsTable fileId={file.id} />}
            </div>
        </SelectedDiffFilesContext.Provider>
    );
};
export default Show;
