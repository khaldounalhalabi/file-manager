import { useForm } from "@inertiajs/react";
import { FormEvent } from "react";
import PageCard from "@/Components/ui/PageCard";
import Form from "@/Components/form/Form";

import Input from "@/Components/form/fields/Input";

import { User } from "@/Models/User";

const Edit = ({ user }: { user: User }) => {
    const { post, setData, processing } = useForm<{
        first_name?: string;
        last_name?: string;
        email?: string;
        profile?: string | File;
        _method?: "PUT" | "POST";
    }>({
        first_name: user.first_name,
        last_name: user.last_name,
        email: user.email,
        _method: "PUT",
    });

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setData("_method", "PUT");
        post(route("v1.web.admin.users.update", user.id));
    };

    return (
        <PageCard title="Edit User">
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
                        defaultValue={user.first_name}
                    />

                    <Input
                        name={"last_name"}
                        label={"Last Name"}
                        onChange={(e) => setData("last_name", e.target.value)}
                        type={"text"}
                        required={true}
                        defaultValue={user.last_name}
                    />

                    <Input
                        name={"email"}
                        label={"Email"}
                        onChange={(e) => setData("email", e.target.value)}
                        type={"email"}
                        required={true}
                        defaultValue={user.email}
                    />

                    <Input
                        name={"profile"}
                        type={"file"}
                        onChange={(e) => {
                            setData("profile", e.target?.files?.[0]);
                        }}
                    />
                </div>

                <div className="my-2"></div>
            </Form>
        </PageCard>
    );
};

export default Edit;
