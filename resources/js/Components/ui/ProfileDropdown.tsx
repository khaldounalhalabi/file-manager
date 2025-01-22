import { useEffect, useRef, useState } from "react";
import { Link } from "@inertiajs/react";
import { asset, role, user } from "@/helper";
import ChevronDown from "../icons/ChevronDown";

const ProfileDropdown = () => {
    const [open, setOpen] = useState(false);
    const authUser = user();
    const dropdownRef = useRef<HTMLDivElement>(null);
    const authRole = role();

    const handleClickOutside = (event: MouseEvent) => {
        if (
            dropdownRef.current &&
            !dropdownRef.current.contains(event.target as Node)
        ) {
            setOpen(false);
        }
    };

    useEffect(() => {
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    return (
        <div ref={dropdownRef} className="w-auto relative">
            <button
                className="focus:outline-none bg-transparent py-2 inline-flex dark:text-white justify-center items-center rounded-lg text-sm text-center"
                type="button"
                onClick={() => setOpen((prevState) => !prevState)}
            >
                <div className="mx-2 rounded-full">
                    <img
                        className="rounded-full max-w-12 max-h-12"
                        src={
                            authUser?.profile?.path ??
                            asset("/images/profile-img.jpg")
                        }
                        alt=""
                    />
                </div>
                {authUser?.first_name + " " + authUser?.last_name}
                <ChevronDown className="w-4 h-4 ms-3" />
            </button>

            <div
                className={`${
                    open ? "absolute" : "hidden"
                } z-10 start-5 bg-white-secondary dark:bg-dark-secondary rounded-lg shadow w-44`}
            >
                <ul className="shadow-md h-full text-gray-700 text-sm dark:text-white">
                    <li
                        onClick={() => {
                            setOpen((prevState) => !prevState);
                        }}
                    >
                        <Link
                            id="user-details"
                            href={route(`v1.web.${authRole}.user.details`)}
                            className="cursor-pointer block hover:bg-gray-100 dark:hover:text-black p-2 rounded-md"
                        >
                            My Profile
                        </Link>
                    </li>
                    <li
                        onClick={() => {
                            setOpen((prevState) => !prevState);
                        }}
                    >
                        <Link
                            id="logout"
                            href={route(`v1.web.${authRole}.logout`)}
                            className="cursor-pointer block hover:bg-gray-100 dark:hover:text-black p-2 rounded-md"
                        >
                            Sign Out
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    );
};

export default ProfileDropdown;
