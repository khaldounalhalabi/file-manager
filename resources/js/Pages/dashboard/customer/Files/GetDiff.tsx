import { useEffect, useState } from "react";
import { Decoration, Diff, Hunk, parseDiff } from "react-diff-view";

const GetDiff = ({
    first_file_stream_url,
    second_file_stream_url,
}: {
    first_file_stream_url: string;
    second_file_stream_url: string;
}) => {
    const [files, setFiles] = useState<any[]>([]);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchDiff = async () => {
            try {
                const [firstFileResponse, secondFileResponse] =
                    await Promise.all([
                        fetch(first_file_stream_url).then((res) => res.text()),
                        fetch(second_file_stream_url).then((res) => res.text()),
                    ]);

                const diffText = generateUnifiedDiff(
                    firstFileResponse,
                    secondFileResponse,
                );
                const parsedFiles = parseDiff(diffText);
                setFiles(parsedFiles);
            } catch (err: any) {
                setError(err.message || "Error fetching files.");
            }
        };

        fetchDiff();
    }, [first_file_stream_url, second_file_stream_url]);

    const generateUnifiedDiff = (
        oldContent: string,
        newContent: string,
    ): string => {
        const oldLines = oldContent.split("\n");
        const newLines = newContent.split("\n");

        const diffText = [`--- File1`, `+++ File2`];

        let i = 0;
        let oldLineNumber = 1;
        let newLineNumber = 1;

        while (i < Math.max(oldLines.length, newLines.length)) {
            const oldLine = oldLines[i] ?? "";
            const newLine = newLines[i] ?? "";

            if (oldLine !== newLine) {
                diffText.push(`@@ -${oldLineNumber} +${newLineNumber} @@`);
                if (oldLine) {
                    diffText.push(`-${oldLine}`);
                }
                if (newLine) {
                    diffText.push(`+${newLine}`);
                }
            } else {
                diffText.push(` ${oldLine}`);
            }

            if (oldLine) oldLineNumber++;
            if (newLine) newLineNumber++;
            i++;
        }

        return diffText.join("\n");
    };

    const renderHunk = (hunk: any) => [
        <Decoration key={`decoration-${hunk.content}`}>
            <div className="bg-blue-100 text-blue-700 p-2 rounded-lg font-semibold w-full">
                Summary: {hunk.content.trim()}
            </div>
        </Decoration>,
        <Hunk
            key={`hunk-${hunk.content}`}
            hunk={hunk}
        />,
    ];

    if (error) {
        return <div className="text-red-500">Error: {error}</div>;
    }

    if (!files.length) {
        return <div>Loading...</div>;
    }

    return (
        <div className="p-6 bg-gray-50 w-full">
            <h1 className="text-3xl font-bold mb-6 text-center w-full">
                File Differences
            </h1>
            {files.map(({ oldRevision, newRevision, type, hunks }) => (
                <div
                    key={`${oldRevision}-${newRevision}`}
                    className="border rounded-lg shadow-md bg-white p-4 mb-6 w-full"
                >
                    <h2 className="text-lg font-semibold mb-4">
                        Comparing:{" "}
                        <span className="text-blue-600">{oldRevision}</span> →{" "}
                        <span className="text-green-600">{newRevision}</span>
                    </h2>
                    <Diff
                        viewType="split"
                        diffType={type}
                        hunks={hunks}
                        className={"w-full"}
                    >
                        {(hunks) => hunks.flatMap(renderHunk)}
                    </Diff>
                </div>
            ))}
        </div>
    );
};

export default GetDiff;
