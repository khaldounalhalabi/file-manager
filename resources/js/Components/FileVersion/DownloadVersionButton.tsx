import { FileVersion } from "@/Models/FileVersion";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";
import React from "react";
import DownloadFile from "@/Hooks/DownloadFile";
import { getCsrf } from "@/helper";
import { ArrowDownCircle } from "lucide-react";

const DownloadVersionButton = ({ version }: { version: FileVersion }) => {
    const { downloadFile, isLoading } = DownloadFile();
    return (
        <button
            type={"button"}
            className="hover:bg-white-secondary p-1 rounded-md disabled:cursor-not-allowed text-success disabled:text-white disabled:bg-gray-300"
            disabled={isLoading}
            onClick={() => {
                downloadFile(() =>
                    fetch(version.file_path.path, {
                        headers: {
                            "X-CSRF-TOKEN": getCsrf() ?? "",
                            "Content-Type": "application/html",
                        },
                    }),
                );
            }}
        >
            {isLoading ? (
                <LoadingSpinner className={"w-5 h-5 dark:text-white"} />
            ) : (
                <ArrowDownCircle className="w-5 h-5" />
            )}
        </button>
    );
};

export default DownloadVersionButton;
