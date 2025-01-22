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
    const authRole = role();
    const sidebarItems = [
        {
            href: route(`v1.web.${authRole}.index`),
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
            href: route(`v1.web.${authRole}.groups.index`),
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

    const handleItemClick = () => {
        if (window.innerWidth < 768) {
            toggleSidebar();
        }
    };

    return (
        <div
            className={`fixed top-0 left-0 w-full h-full bg-white-secondary dark:bg-dark-secondary z-50 transition-transform duration-300 ease-in-out transform ${
                isOpen ? "translate-y-0" : "-translate-y-full"
            } sm:translate-y-0 sm:static sm:flex sm:flex-col sm:max-h-screen sm:overflow-y-scroll`}
        >
            {/* Header */}
            <div
                className={`flex ${
                    isOpen ? "justify-between" : "justify-center"
                } items-center sticky top-0 bg-white-secondary dark:bg-dark-secondary p-[17px] max-h-20 sm:shadow-sm`}
            >
                <div className={`flex items-center justify-center gap-1`}>
                    <img
                        src={asset("/images/logo.png")}
                        width="40px"
                        alt="Logo"
                    />
                    {isOpen && (
                        <a
                            href="#"
                            className="px-2 w-full text-brand dark:text-white hover:underline"
                        >
                            Ultimate file manager
                        </a>
                    )}
                </div>

                {isOpen && (
                    <button type="button" onClick={toggleSidebar}>
                        <XMark className="w-8 h-8 text-brand dark:text-white" />
                    </button>
                )}
            </div>

            {/* Sidebar Items */}
            <div
                id="sidebar-list"
                className="bg-white-secondary dark:bg-dark-secondary w-full mt-6 gap-1 px-4"
            >
                {sidebarItems.map((item, index) =>
                    (authRole && item.role?.includes(authRole)) ||
                    !item.role ? (
                        <SidebarItem
                            key={index}
                            href={item.href}
                            title={item.title}
                            icon={item.icon}
                            isOpen={isOpen}
                            onClick={handleItemClick}
                        />
                    ) : null,
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
    onClick,
}: {
    href: string;
    title: string;
    isOpen: boolean;
    icon?: () => ReactNode;
    onClick: () => void;
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
                onClick={onClick} // Close sidebar on item click
            >
                {icon ? icon() : ""}
                {isOpen && <span>{title}</span>}
            </Link>
        </div>
    );
};
