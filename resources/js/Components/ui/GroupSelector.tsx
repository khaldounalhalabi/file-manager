import { useEffect, useRef, useState } from "react";
import { user, userGroups } from "@/helper";
import ChevronDown from "@/Components/icons/ChevronDown";
import { Link } from "@inertiajs/react";

const GroupSelector = () => {
    const [open, setOpen] = useState(false);
    const authUser = user();
    const groups = userGroups() ?? [];
    const dropdownRef = useRef<HTMLDivElement>(null);

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
                className="focus:outline-none bg-transparent py-2 px-5 inline-flex dark:text-white justify-center items-center rounded-lg text-sm text-center"
                type="button"
                onClick={() => setOpen((prevState) => !prevState)}
            >
                {authUser?.group?.name}
                <ChevronDown className="w-4 h-4 ms-3" />
            </button>

            <div
                className={`${
                    open ? "absolute" : "hidden"
                } z-10 start-5 bg-white-secondary dark:bg-dark-secondary rounded-lg shadow w-44`}
            >
                <ul className="shadow-md h-full text-gray-700 text-sm dark:text-white">
                    {groups.map(
                        (group) =>
                            group.id != authUser?.group_id && (
                                <li
                                    onClick={() => {
                                        setOpen((prevState) => !prevState);
                                    }}
                                >
                                    <Link
                                        href={route(
                                            "v1.web.customer.groups.change",
                                            group.id,
                                        )}
                                        className={`cursor-pointer block hover:bg-gray-200 dark:hover:text-black p-2 rounded-md ${group.id == authUser?.group_id ? "bg-gray-200" : ""}`}
                                    >
                                        {group.name}
                                    </Link>
                                </li>
                            ),
                    )}
                </ul>
            </div>
        </div>
    );
};

export default GroupSelector;
