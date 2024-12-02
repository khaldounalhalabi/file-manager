import "./bootstrap";
import "../css/cubeta-starter.css";
import { createRoot } from "react-dom/client";
import { createInertiaApp } from "@inertiajs/react";
import React, { Suspense } from "react";
import Layout from "@/Components/layouts/Layout";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const authPages = [
    "Login",
    "ForgetPassword",
    "ResetPasswordCodeForm",
    "ResetPassword",
    "Register",
    "GroupSelector",
];

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const pages = import.meta.glob("./Pages/**/*.tsx");
        // @ts-ignore
        let page = (await pages[`./Pages/${name}.tsx`]()).default;

        // Assign layout conditionally
        page.layout =
            page.layout ||
            (!authPages.includes(page.name ?? "undefined")
                ? (
                      page:
                          | string
                          | number
                          | boolean
                          | React.ReactElement<
                                any,
                                string | React.JSXElementConstructor<any>
                            >
                          | Iterable<React.ReactNode>
                          | React.ReactPortal
                          | null
                          | undefined,
                  ) => <Layout children={page} />
                : null);

        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <Suspense fallback={<div>Loading...</div>}>
                <App {...props} />
            </Suspense>,
        );
    },
    progress: {
        color: "#4B5563",
    },
});

// @ts-ignore
const dark = "dark" == window.localStorage.getItem("theme_mode") ?? "light";
const htmlTag = document.querySelector("html");
if (dark) {
    htmlTag?.classList.add("dark");
    htmlTag?.classList.remove("light");
} else {
    htmlTag?.classList.add("light");
    htmlTag?.classList.remove("dark");
}
