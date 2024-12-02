import React from "react";

const MsWordIcon: React.FunctionComponent<
    React.SVGAttributes<SVGSVGElement>
> = ({ className = "h-6 w-6", ...props }) => (
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="1em"
        height="1em"
        viewBox="0 0 16 16"
        className={className}
        {...props}
    >
        <g fill="currentColor">
            <path d="M5.485 6.879a.5.5 0 1 0-.97.242l1.5 6a.5.5 0 0 0 .967.01L8 9.402l1.018 3.73a.5.5 0 0 0 .967-.01l1.5-6a.5.5 0 0 0-.97-.242l-1.036 4.144l-.997-3.655a.5.5 0 0 0-.964 0l-.997 3.655L5.485 6.88z"></path>
            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"></path>
        </g>
    </svg>
);

export default MsWordIcon;
