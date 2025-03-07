import React, { useEffect, useState } from "react";
import Navbar from "@/Components/ui/Navbar";
import { Sidebar } from "@/Components/ui/Sidebar";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { usePage } from "@inertiajs/react";
import { MiddlewareProps } from "@/types";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { getTheme, setCsrf, user } from "@/helper";
import useFcmToken from "@/Hooks/FirebaseNotificationHook";
import NotificationProvider from "@/Contexts/NotificationProvider";

const Layout = ({ children }: { children?: React.ReactNode }) => {
    useFcmToken();
    const theme = getTheme();
    const authUer = user();
    const { csrfToken } = usePage<MiddlewareProps>().props;
    setCsrf(csrfToken);
    const [isOpen, setIsOpen] = useState(true);
    const toggleSidebar = () => {
        setIsOpen((prev) => !prev);
    };
    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth >= 768) {
                setIsOpen(true);
            } else {
                setIsOpen(false);
            }
        };
        handleResize();
        window.addEventListener("resize", handleResize);
        return () => window.removeEventListener("resize", handleResize);
    }, []);

    const queryClient = new QueryClient({});

    if (usePage<MiddlewareProps>().props.message) {
        toast.info(usePage<MiddlewareProps>().props.message);
        usePage<MiddlewareProps>().props.message = undefined;
    }

    if (usePage<MiddlewareProps>().props.success) {
        toast.success(usePage<MiddlewareProps>().props.success);
        usePage<MiddlewareProps>().props.success = undefined;
    }

    if (usePage<MiddlewareProps>().props.error) {
        toast.error(usePage<MiddlewareProps>().props.error);
        usePage<MiddlewareProps>().props.success = undefined;
    }

    return (
        <>
            <QueryClientProvider client={queryClient}>
                <div className={`flex h-screen overflow-y-scroll`}>
                    <ToastContainer
                        theme={theme}
                        rtl={
                            usePage<MiddlewareProps>().props.currentLocale ==
                            "ar"
                        }
                    />
                    {authUer ? (
                        <NotificationProvider>
                            <div
                                className={`bg-white-secondary shadow-lg dark:bg-dark-secondary h-full ${
                                    isOpen
                                        ? "slide-sidebar-right"
                                        : "w-0 slide-sidebar-left md:w-1/4"
                                }`}
                            >
                                <Sidebar
                                    isOpen={isOpen}
                                    toggleSidebar={toggleSidebar}
                                />
                            </div>
                            <div
                                className={`w-full h-full overflow-y-scroll bg-white dark:bg-dark`}
                            >
                                <Navbar
                                    isSidebarOpen={isOpen}
                                    toggleSidebar={toggleSidebar}
                                />
                                <main className={"m-5 bg-white dark:bg-dark"}>
                                    {children}
                                </main>
                            </div>
                        </NotificationProvider>
                    ) : (
                        <main className={"w-full h-full"}>{children}</main>
                    )}
                </div>
            </QueryClientProvider>
        </>
    );
};

export default Layout;
