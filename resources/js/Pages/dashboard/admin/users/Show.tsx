import Button from "@/Components/ui/Button";
import PageCard from "@/Components/ui/PageCard";
import { User } from "@/Models/User";
import { Link } from "@inertiajs/react";

import SmallTextField from "@/Components/Show/SmallTextField";

const Show = ({ user }: { user: User }) => {
    return (
        <PageCard
            title="User Details"
            actions={
                <div className="flex justify-between items-center">
                    <Link href={route("v1.web.admin.users.edit", user.id)}>
                        <Button>Edit</Button>
                    </Link>
                </div>
            }
        >
            <div className="gap-5 grid grid-cols-1 md:grid-cols-2">
                <SmallTextField label="First Name " value={user.first_name} />
                <SmallTextField label="Last Name " value={user.last_name} />
                <SmallTextField label="Email " value={user.email} />
                <SmallTextField
                    label={"Role"}
                    value={user.roles?.map((role) => role.name).join(" , ")}
                />
                {/*TODO:: never forget to add profile*/}
            </div>
        </PageCard>
    );
};

export default Show;
