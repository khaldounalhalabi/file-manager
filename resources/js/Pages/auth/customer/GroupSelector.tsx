import { Group } from "@/Models/Group";
import { asset } from "@/helper";
import PageCard from "@/Components/ui/PageCard";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/fields/Input";
import { Link, useForm } from "@inertiajs/react";
import { FormEvent } from "react";

const GroupSelector = ({ groups }: { groups: Group[] }) => {
    const { post, setData, errors, processing } = useForm<{
        name: string;
    }>();

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route("v1.web.customer.groups.store"));
    };
    return (
        <div className="grid grid-cols-3 my-20">
            <div className="col-start-2 col-end-3">
                <div className="flex flex-col items-center">
                    <div className="flex items-center my-2 gap-1">
                        <img src={asset("images/logo.png")} width={"35px"} />
                        <h1 className="text-brand text-4xl font-bold">
                            Ultimate file manager
                        </h1>
                    </div>
                    <PageCard>
                        <div className="flex flex-col my-5">
                            <div className="flex justify-center items-center dark:text-white">
                                <h2 className={"text-xl "}>
                                    Please select a group to proceed
                                </h2>
                            </div>
                        </div>
                        <div
                            className={
                                "max-h-[75vh] min-h-[40vh] overflow-y-scroll p-5 w-full flex flex-col gap-2"
                            }
                        >
                            {groups.length > 0 ? (
                                groups.map((group) => (
                                    <Link
                                        href={route(
                                            "v1.web.customer.groups.select",
                                            group.id,
                                        )}
                                        className={
                                            "flex flex-col items-start justify-between w-full px-5 py-2 rounded-md bg-gray-200 hover:bg-gray-300 cursor-pointer"
                                        }
                                    >
                                        <h2 className={"text-lg text-primary"}>
                                            {group.name}
                                        </h2>
                                        <p>
                                            Owner : {group.owner?.first_name}{" "}
                                            {group.owner?.last_name}
                                        </p>
                                    </Link>
                                ))
                            ) : (
                                <div
                                    className={
                                        "flex flex-col items-start gap-2 w-full"
                                    }
                                >
                                    <p>There is no groups to show</p>
                                    <p className={"text-brand"}>
                                        Create one to proceed
                                    </p>
                                    <Form
                                        onSubmit={onSubmit}
                                        processing={processing}
                                        buttonText={"Create"}
                                    >
                                        <Input
                                            name={"name"}
                                            label={"Group name"}
                                            type={"text"}
                                            onChange={(e) => {
                                                setData("name", e.target.value);
                                            }}
                                        />
                                    </Form>
                                </div>
                            )}
                        </div>
                    </PageCard>
                </div>
            </div>
        </div>
    );
};

export default GroupSelector;
