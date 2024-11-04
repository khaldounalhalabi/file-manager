import { useForm } from "@inertiajs/react";
import { FormEvent } from "react";
import PageCard from "@/Components/ui/PageCard";
import Form from "@/Components/form/Form";

import Input from "@/Components/form/fields/Input";

import { Group } from "@/Models/Group";

const Edit = ({ group }: { group: Group }) => {
    const { post, setData, errors, processing } = useForm<{
        name: string;
        _method?: "PUT" | "POST";
    }>({
        name: group.name,
        _method: "PUT",
    });

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setData("_method", "PUT");
        post(route("v1.web.customer.groups.update", group.id));
    };

    return (
        <PageCard title="Edit Group">
            <Form onSubmit={onSubmit} processing={processing}>
                <div
                    className={`grid grid-cols-1 md:grid-cols-2 gap-5 items-end`}
                >
                    <Input
                        name={"name"}
                        label={"Name"}
                        onChange={(e) => setData("name", e.target.value)}
                        type={"text"}
                        required={true}
                        defaultValue={group.name}
                    />
                </div>
            </Form>
        </PageCard>
    );
};

export default Edit;
