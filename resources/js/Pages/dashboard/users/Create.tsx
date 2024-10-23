import { useForm } from "@inertiajs/react";
import { FormEvent, useState } from "react";
import PageCard from "@/Components/ui/PageCard";
import Form from "@/Components/form/Form";

import Input from "@/Components/form/fields/Input";
import Select from "@/Components/form/fields/Select/Select";

const Create = () => {
    const { post, setData, errors, processing } = useForm<{
        first_name: string;
        last_name: string;
        email: string;
        password: string;
        password_confirmation: string;
        role: string;
        profile: File | string | undefined;
        group_name?: string;
        _method?: "PUT" | "POST";
    }>();

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route("v1.web.admin.users.store"));
    };

    const [selectedRole, setSelectedRole] = useState<string>("admin");

    return (
        <PageCard title="Create User">
            <Form onSubmit={onSubmit} processing={processing}>
                <div
                    className={`grid grid-cols-1 md:grid-cols-2 gap-5 items-end`}
                >
                    <Input
                        name={"first_name"}
                        label={"First Name"}
                        onChange={(e) => setData("first_name", e.target.value)}
                        type={"text"}
                        required={true}
                    />

                    <Input
                        name={"last_name"}
                        label={"Last Name"}
                        onChange={(e) => setData("last_name", e.target.value)}
                        type={"text"}
                        required={true}
                    />

                    <Input
                        name={"email"}
                        label={"Email"}
                        onChange={(e) => setData("email", e.target.value)}
                        type={"email"}
                        required={true}
                    />

                    <Input
                        name={"password"}
                        label={"Password"}
                        onChange={(e) => setData("password", e.target.value)}
                        type={"password"}
                        required={true}
                    />

                    <Input
                        name={"password_confirmation"}
                        label={"Confirm Password"}
                        onChange={(e) =>
                            setData("password_confirmation", e.target.value)
                        }
                        type={"password"}
                        required={true}
                    />

                    <Select
                        data={[{ role: "customer" }, { role: "admin" }]}
                        label={"Role"}
                        optionLabel={"role"}
                        optionValue={"role"}
                        onChange={(e) => {
                            setData("role", e.target.value);
                            setSelectedRole(e.target?.value);
                        }}
                    />

                    <Input
                        name={"profile"}
                        type={"file"}
                        onChange={(e) => {
                            setData("profile", e.target?.files?.[0]);
                        }}
                    />

                    {selectedRole == "customer" && (
                        <Input
                            name={"group_name"}
                            label={"Group name"}
                            onChange={(e) => {
                                setData("group_name", e.target.value);
                            }}
                        />
                    )}
                </div>

                <div className="my-2"></div>
            </Form>
        </PageCard>
    );
};

export default Create;
