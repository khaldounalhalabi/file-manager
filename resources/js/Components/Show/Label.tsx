import { ReactNode } from "react";

const Label = ({
    label,
    children,
}: {
    label: string;
    children?: ReactNode;
}) => {
    return (
        <label className="font-semibold text-lg p-4">
            {label} :{children}
        </label>
    );
};

export default Label;
