import PdfIcon from "@/Components/icons/PdfIcon";
import MsWordIcon from "@/Components/icons/MsWordIcon";
import ExcelIcon from "@/Components/icons/ExcelIcon";
import { FileIcon, ImageIcon, TextIcon } from "lucide-react";
import React from "react";

const fileIconMapping: Record<string, any> = {
    pdf: <PdfIcon className={"h-12 w-12"} />,
    doc: <MsWordIcon className={"w-12 h-12"} />,
    docx: <MsWordIcon className={"w-12 h-12"} />,
    xls: <ExcelIcon className={"w-12 h-12"} />,
    xlsx: <ExcelIcon className={"w-12 h-12"} />,
    png: <ImageIcon className={"w-12 h-12"} />,
    jpg: <ImageIcon className={"w-12 h-12"} />,
    jpeg: <ImageIcon className={"w-12 h-12"} />,
    txt: <TextIcon className={"w-12 h-12"} />,
    default: <FileIcon className={"w-12 h-12"} />,
};

const getFileIcon = (extension: string) => {
    return fileIconMapping?.[extension] || fileIconMapping.default;
};

const IconFile = ({ fileName }: { fileName: string }) => {
    const fileExtension = fileName.split(".")?.pop()?.toLowerCase() ?? "";
    return <span>{getFileIcon(fileExtension)}</span>;
};

export default IconFile;