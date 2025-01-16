"use client";
import {
    NotificationPayload,
    NotificationPayloadData,
} from "@/Models/NotificationPayload";
import { getMessaging, onMessage } from "firebase/messaging";
import {
    createContext,
    Dispatch,
    ReactNode,
    SetStateAction,
    useEffect,
    useState,
} from "react";
import { toast } from "react-toastify";
import firebaseApp from "@/Config/Firebase";
import { router } from "@inertiajs/react";

export const NotificationsHandlersContext = createContext<Dispatch<
    SetStateAction<Handler[]>
> | null>(null);

export interface Handler {
    fn: (payload: NotificationPayload) => void;
    key?: string;
    is_active: boolean;
    is_permenant: boolean;
}

const NotificationProvider = ({
    children,
    handle = undefined,
}: {
    handle?: (payload: NotificationPayload) => void;
    children?: ReactNode;
}) => {
    const [handlers, setHandlers] = useState<Handler[]>([]);

    // eslint-disable-next-line react-hooks/rules-of-hooks
    useEffect(() => {
        if (typeof window !== "undefined" && "serviceWorker" in navigator) {
            const messaging = getMessaging(firebaseApp);
            const unsubscribe = onMessage(messaging, (payload) => {
                console.log(payload);
                const notification = new NotificationPayload(
                    payload.collapseKey,
                    payload?.data as NotificationPayloadData | undefined,
                    payload.from,
                    payload.messageId,
                );

                handlers.forEach((handler) => {
                    handler.fn(notification);
                });
                if (notification.isNotification()) {
                    toast.success(notification.data?.message, {
                        onClick: () => {
                            router.visit(notification.getUrl());
                        },
                        bodyClassName: "cursor-pointer",
                    });
                }

                if (handle) {
                    handle(notification);
                }
            });
            return () => {
                unsubscribe(); // Unsubscribe from the onMessage event
            };
        }
    }, [handlers]);

    return (
        <NotificationsHandlersContext.Provider value={setHandlers}>
            {children}
        </NotificationsHandlersContext.Provider>
    );
};

export default NotificationProvider;
