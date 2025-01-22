import React, { useEffect, useRef, useState } from "react";
import {
    Notification,
    NotificationPayload,
} from "@/Models/NotificationPayload";
import { useInfiniteQuery, useQuery } from "@tanstack/react-query";
import { GET } from "@/Modules/Http";
import HandleClickOutSide from "@/Hooks/HandleClickOutside";
import { NotificationHandler } from "@/Contexts/NotificationHandler";
import OpenAndClose from "@/Hooks/OpenAndClose";
import { Bell, CircleCheck } from "lucide-react";
import LoadingSpinner from "@/Components/icons/LoadingSpinner";
import { Link } from "@inertiajs/react";

const NotificationsPopover = () => {
    const [openPopNot, setOpenPopNot] = useState<boolean>(false);

    const fetchNotifications = async ({ pageParam = 0 }) =>
        await GET<Notification[]>(route("v1.web.customer.notifications"), {
            limit: 5,
            page: pageParam,
        });
    const {
        data: notifications,
        fetchNextPage,
        hasNextPage,
        isFetching,
        isFetchingNextPage,
        refetch,
    } = useInfiniteQuery({
        queryKey: ["Notifications"],
        queryFn: fetchNotifications,
        getNextPageParam: (lastPage) => {
            return !lastPage.pagination_data?.is_last
                ? lastPage.pagination_data?.currentPage
                    ? lastPage.pagination_data?.currentPage + 1
                    : null
                : null;
        },
        initialPageParam: 0,
    });

    const {
        data: notificationsCount,
        isFetching: isFetchingCount,
        refetch: refetchCount,
    } = useQuery({
        queryKey: ["notifications_count"],
        queryFn: async () =>
            await GET<number>(
                route("v1.web.customer.notifications.unread.count"),
            ),
    });

    const ref: React.RefObject<HTMLDivElement> = useRef<HTMLDivElement>(null);
    useEffect(() => {
        HandleClickOutSide(ref, setOpenPopNot);
    }, []);

    return (
        <div
            ref={ref}
            className={`${openPopNot ? " relative" : "overflow-clip relative"} max-h-72`}
        >
            <NotificationHandler
                handle={(payload) => {
                    if (payload.isNotification()) {
                        refetch();
                        refetchCount();
                    }
                }}
                isPermenant={true}
            />
            <div
                className={"relative w-8 h-full cursor-pointer"}
                onClick={() => OpenAndClose(openPopNot, setOpenPopNot)}
            >
                {(notificationsCount?.data ?? 0) > 0 ? (
                    <span
                        className={
                            "absolute right-0 -top-1 border-red-500 rounded-full text-red-500"
                        }
                    >
                        {notificationsCount?.data}
                    </span>
                ) : (
                    ""
                )}
                <Bell
                    className={
                        openPopNot
                            ? `h-6 w-6 cursor-pointer text-[#909CA6] fill-blue-500`
                            : "h-6 w-6 cursor-pointer text-[#909CA6] fill-[#909CA6]"
                    }
                />
            </div>

            <div
                className={
                    openPopNot
                        ? "absolute md:end-0 -left-16 md:w-[360px] w-[80vw] z-20 mt-2 top-10 divide-y divide-gray-100 rounded-2xl bg-white opacity-100  transition-x-0 ease-in-out  duration-500 "
                        : "absolute transition-x-[-200px] opacity-0 ease-in-out duration-500 "
                }
                role="menu"
                style={{
                    boxShadow:
                        " 0px 5px 5px -3px rgba(145, 158, 171, 0.2)" +
                        ", 0px 8px 10px 1px rgba(145, 158, 171, 0.14)" +
                        ", 0px 3px 14px 2px rgba(145, 158, 171, 0.12)",
                }}
            >
                <div className="px-5 py-4">
                    <h2>Notifications</h2>
                    <p className="opacity-[0.6]">
                        {isFetchingCount ? (
                            <LoadingSpinner className={"dark:text-white"}/>
                        ) : (
                            `You have ${notificationsCount?.data ?? 0} unread messages`
                        )}
                    </p>
                </div>

                <div className="max-h-72 overflow-y-scroll">
                    {isFetching && !isFetchingNextPage ? (
                        <p className="text-center">Loading notifications ...</p>
                    ) : (
                        notifications?.pages?.map((item) =>
                            item?.data?.map((notification, index) => {
                                const n = new NotificationPayload(
                                    undefined,
                                    JSON.parse(notification.data),
                                    undefined,
                                    undefined,
                                    notification.message,
                                    notification.read_at,
                                    notification.created_at,
                                    notification.type,
                                    notification.id,
                                );
                                return (
                                    <div
                                        key={index}
                                        className="flex justify-between items-center mx-2"
                                    >
                                        <Link
                                            href={n.getUrl()}
                                            className="p-3 w-full cursor-pointer hover:bg-gray-300 border-b-gray-100 rounded-md"
                                            onClick={() => {
                                                GET(
                                                    route(
                                                        "v1.web.customer.notifications.mark.as.read",
                                                        {
                                                            notificationId:
                                                                notification.id,
                                                        },
                                                    ),
                                                );
                                            }}
                                        >
                                            {n.getMessage()}
                                        </Link>
                                        <button
                                            className=" hover:bg-gray-300 p-3 rounded-md"
                                            onClick={() => {
                                                if (!n.read_at) {
                                                    GET(
                                                        route(
                                                            "v1.web.customer.notifications.mark.as.read",
                                                            {
                                                                notificationId:
                                                                    notification.id,
                                                            },
                                                        ),
                                                    );
                                                    refetch();
                                                    refetchCount();
                                                }
                                            }}
                                        >
                                            <CircleCheck
                                                className={`h-6 w-6 text-success ${n.read_at ? "fill-success cursor-not-allowed" : "cursor-pointer"}`}
                                            />
                                        </button>
                                    </div>
                                );
                            }),
                        )
                    )}
                    {isFetchingNextPage ? (
                        <p className="text-center py-3">
                            Loading notifications ...
                        </p>
                    ) : (
                        ""
                    )}
                </div>
                <div className="px-8 py-6 flex items-center justify-center w-full">
                    {hasNextPage ? (
                        <button
                            className="btn text-pom font-bold text-[0.875rem] flex items-center justify-between"
                            onClick={() => {
                                fetchNextPage();
                            }}
                        >
                            Show More
                            {isFetchingNextPage ? <LoadingSpinner className={"dark:text-white"}/> : ""}
                        </button>
                    ) : (
                        ""
                    )}
                </div>
            </div>
        </div>
    );
};

export default NotificationsPopover;
