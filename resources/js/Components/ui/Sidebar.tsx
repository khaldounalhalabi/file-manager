import React, { ReactNode } from "react";
import { asset, role } from "@/helper";
import { Link } from "@inertiajs/react";
import XMark from "../icons/XMark";
import PresentationChart from "../icons/PresentationChart";
import { FolderOpen, Group, User } from "lucide-react";

export const Sidebar = ({
    toggleSidebar,
    isOpen,
}: {
    toggleSidebar: () => void;
    isOpen: boolean;
}) => {
    const sidebarItems = [
        {
            href: route(`v1.web.${role()}.index`),
            title: "Dashboard",
            icon: () => <PresentationChart />,
            role: ["admin", "customer"],
        },
        {
            href: route(`v1.web.admin.users.index`),
            title: "User",
            icon: () => <User />,
            role: ["admin"],
        },
        {
            href: route(`v1.web.${role()}.groups.index`),
            title: "Group",
            icon: () => <Group />,
            role: ["admin", "customer"],
        },
        {
            href: route(`v1.web.customer.directories.root`),
            title: "Files",
            icon: () => <FolderOpen />,
            role: ["customer"],
        },
    ];
    const userRole = role();
    return (
        <div
            className={`sticky flex flex-col bg-white-secondary dark:bg-dark-secondary max-h-screen overflow-y-scroll`}
        >
            <div
                className={`flex  ${isOpen ? " justify-between " : " justify-center "} items-center sticky top-0 bg-white-secondary dark:bg-dark-secondary p-[17px] max-h-20 ${isOpen ? "shadow-sm" : " "}`}
            >
                <div className={`flex items-center justify-center gap-1`}>
                    <img
                        src={asset("/images/logo.png")}
                        width={`${isOpen ? "40px" : "40px"}`}
                        alt=""
                    />
                    {isOpen && (
                        <a
                            href="#"
                            className={`px-2 w-full text-brand dark:text-white hover:underline`}
                        >
                            Ultimate file manager
                        </a>
                    )}
                </div>

                {isOpen && (
                    <button type={"button"} onClick={() => toggleSidebar()}>
                        <XMark className="w-8 h-8 text-brand dark:text-white" />
                    </button>
                )}
            </div>

            <div
                id="sidebar-list"
                className={
                    "bg-white-secondary dark:bg-dark-secondary w-full mt-6 gap-1 px-4"
                }
            >
                {sidebarItems.map((item, index) =>
                    (userRole && item.role?.includes(userRole)) ||
                    !item.role ? (
                        <SidebarItem
                            key={index}
                            href={item.href}
                            title={item.title}
                            icon={item.icon}
                            isOpen={isOpen}
                        />
                    ) : (
                        ""
                    ),
                )}
            </div>
        </div>
    );
};

export const SidebarItem = ({
    href,
    title,
    isOpen = false,
    icon = undefined,
}: {
    href: string;
    title: string;
    isOpen: boolean;
    icon?: () => ReactNode;
}) => {
    const selected = window.location.href.startsWith(href);

    return (
        <div className="mb-3">
            <Link
                className={`flex text-lg gap-5 items-center px-4 py-2 hover:bg-white text-brand hover:text-gray-700 rounded-lg ${
                    selected
                        ? "bg-sky-100 dark:bg-white-secondary dark:text-black"
                        : " dark:text-white"
                }`}
                href={href}
            >
                {icon ? icon() : ""}
                {isOpen && <span>{title}</span>}
            </Link>
        </div>
    );
};

export const CompactSidebarItem = ({
    title,
    children,
    baseRouteName,
}: {
    title: string;
    children?: React.ReactNode;
    baseRouteName?: string;
}) => {
    let selected = false;
    if (baseRouteName) {
        selected = route().current(`${baseRouteName}.*`);
    }
    return (
        <details
            className={`[&_summary::-webkit-details-marker]:hidden group`}
            open={selected}
        >
            <summary
                className={`flex text-lg justify-between items-center hover:bg-gray-100 px-4 py-2 rounded-lg text-brand hover:text-gray-700 cursor-pointer ${
                    selected
                        ? "bg-sky-100 dark:bg-white-secondary dark:text-black"
                        : " dark:text-white"
                }`}
            >
                <span> {title} </span>

                <span className="group-open:-rotate-180 transition duration-300 shrink-0">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="w-5 h-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fillRule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clipRule="evenodd"
                        />
                    </svg>
                </span>
            </summary>
            <ul className="space-y-1 mt-2 px-4">{children}</ul>
        </details>
    );
};
