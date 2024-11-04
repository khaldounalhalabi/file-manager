import { useForm } from "@inertiajs/react";
import { FormEvent } from "react";
import PageCard from "@/Components/ui/PageCard";
import Form from "@/Components/form/Form";

import Input from "@/Components/form/fields/Input";

const Create = () => {
    const { post, setData, processing } = useForm<{
        name: string;
        _method?: "PUT" | "POST";
    }>();

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route("v1.web.customer.groups.store"));
    };

    return (
        <PageCard title="Create Group">
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
                    />
                </div>
            </Form>
        </PageCard>
    );
};

export default Create;
