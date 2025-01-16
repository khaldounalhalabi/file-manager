import { NotificationPayload } from "@/Models/NotificationPayload";
import { useContext } from "react";
import {
    Handler,
    NotificationsHandlersContext,
} from "@/Contexts/NotificationProvider";

export const HandleNotification = (
    handleFunction: (payload: NotificationPayload) => void,
    isPermenant: boolean,
    key?: string,
) => {
    const setHandlers = useContext(NotificationsHandlersContext);

    const process = () => {
        if (!key) {
            key = handleFunction.toString().replace(/\s+/g, "");
        }

        const filterHandlers = (handlers: Handler[]) => {
            if (
                handlers.filter(
                    (handler) => handler.key == key && handler.is_active,
                ).length > 0
            ) {
                return handlers;
            }

            const filteredHandlers = handlers
                .map((item) =>
                    item.is_active && !item.is_permenant
                        ? { ...item, is_active: false }
                        : item,
                )
                .filter((item) => item.is_permenant || item.is_active);

            return [
                ...filteredHandlers,
                {
                    fn: handleFunction,
                    is_active: true,
                    key: key,
                    is_permenant: isPermenant,
                },
            ];
        };

        if (setHandlers) {
            setHandlers((prev) => filterHandlers(prev));
        }
    };

    return { process };
};
