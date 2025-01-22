"use client";
import { HandleNotification } from "@/Hooks/HandleNotification";
import { NotificationPayload } from "@/Models/NotificationPayload";
import { ReactNode, useEffect } from "react";

export const NotificationHandler = ({
    handle,
    children,
    key = undefined,
    isPermenant = false,
}: {
    handle: (payload: NotificationPayload) => void;
    key?: string;
    isPermenant?: boolean;
    children?: ReactNode;
}) => {
    const process = HandleNotification(handle, isPermenant, key);
    useEffect(() => {
        process.process();
    }, []);
    return <>{children}</>;
};
