"use client";
import { NotificationPayload } from "@/Models/NotificationPayload";
import { ReactNode, useEffect } from "react";
import { HandleNotification } from "@/hooks/HandleNotification";

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
