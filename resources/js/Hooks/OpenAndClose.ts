import React from "react";

const OpenAndClose = (isOpen: boolean, setOpen: React.Dispatch<boolean>) => {
    setOpen(!isOpen);
};

export default OpenAndClose;
