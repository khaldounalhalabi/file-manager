import { Group } from "@/Models/Group";
import { Mail } from "lucide-react";
import React, { useEffect, useState } from "react";
import Modal from "@/Components/ui/Modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/fields/Input";
import { useForm } from "@inertiajs/react";

const SendInvitationModal = ({ group }: { group: Group }) => {
    const [open, setOpen] = useState(false);
    const { post, setData, processing, wasSuccessful, transform } = useForm<{
        email: string;
        group_id: number;
    }>();

    const onSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        transform((data) => ({
            ...data,
            group_id: group.id,
        }));

        post(route("v1.web.customer.groups.invite"));
    };

    useEffect(() => {
        if (wasSuccessful) {
            setOpen(false);
        }
    }, [wasSuccessful]);

    return (
        <>
            <button
                onClick={() => {
                    setOpen(true);
                }}
            >
                <Mail className={"w-5 h-5 text-warning"} />
            </button>
            <Modal
                isOpen={open}
                onClose={() => {
                    setOpen(false);
                }}
            >
                <Form
                    onSubmit={onSubmit}
                    backButton={false}
                    processing={processing}
                >
                    <Input
                        name={"email"}
                        label={"User Email"}
                        type={"email"}
                        onChange={(e) => {
                            setData("email", e.target.value);
                        }}
                        required={true}
                    />
                </Form>
            </Modal>
        </>
    );
};

export default SendInvitationModal;
