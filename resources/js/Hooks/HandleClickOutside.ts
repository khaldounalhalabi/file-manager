import React from "react";

const HandleClickOutSide = (
    ref: React.RefObject<HTMLDivElement> | React.RefObject<HTMLUListElement>,
    setOpenPopProfile: React.Dispatch<boolean>,
) => {
    let handleClickOutSide = (e: MouseEvent) => {
        if (ref.current && !ref.current.contains(e.target as Node)) {
            setOpenPopProfile(false);
        }
    };
    document.addEventListener("mousedown", handleClickOutSide);
    return () => {
        document.removeEventListener("mousedown", handleClickOutSide);
    };
};

export default HandleClickOutSide;
