import Form from "@/Components/form/Form";
import Input from "@/Components/form/fields/Input";
import PageCard from "@/Components/ui/PageCard";
import { asset } from "@/helper";
import { Link, useForm } from "@inertiajs/react";
import { FormEvent } from "react";

const Login = () => {
    const { post, setData, errors, processing } = useForm<{
        email: string;
        password: string;
    }>();

    const onSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route("v1.web.public.customer.login"));
    };

    return (
        <div className="md:grid grid-cols-3 my-20">
            <div className="md:col-start-2 md:col-end-3">
                <div className="flex flex-col items-center">
                    <div className="flex items-center my-2 gap-1">
                        <img src={asset("images/logo.png")} width={"35px"} />
                        <h1 className="text-brand md:text-4xl font-bold">
                            Ultimate file manager
                        </h1>
                    </div>
                    <PageCard>
                        <div className="flex flex-col my-5">
                            <div className="flex justify-center items-center">
                                <h1 className="font-semibold text-3xl text-brand">
                                    Welcome Back
                                </h1>
                            </div>
                            <div className="flex justify-center items-center dark:text-white">
                                <p>Please Login To Your Account</p>
                            </div>
                        </div>
                        <Form
                            onSubmit={onSubmit}
                            processing={processing}
                            buttonText="Login"
                            backButton={false}
                        >
                            <div className="flex flex-col gap-5 my-5 w-full">
                                <Input
                                    name="email"
                                    onChange={(e) =>
                                        setData("email", e.target.value)
                                    }
                                    label="Email"
                                    required={true}
                                    type="email"
                                />

                                <Input
                                    name="password"
                                    onChange={(e) =>
                                        setData("password", e.target.value)
                                    }
                                    label="Password"
                                    required={true}
                                    type="password"
                                />
                            </div>
                            <p className="md:text-lg dark:text-white">
                                Forgot Your Password ?{" "}
                                <span>
                                    <Link
                                        href={route(
                                            "v1.web.public.customer.request.reset.password.code-page",
                                        )}
                                        className="text-blue-700 hover:text-primary"
                                    >
                                        Reset Your Password
                                    </Link>
                                </span>
                            </p>
                            <p className="md:text-lg dark:text-white">
                                Don't have an account ?{" "}
                                <span>
                                    <Link
                                        href={route(
                                            "v1.web.public.customer.register.page",
                                        )}
                                        className="text-blue-700 hover:text-primary"
                                    >
                                        Create new one
                                    </Link>
                                </span>
                            </p>
                        </Form>
                    </PageCard>
                </div>
            </div>
        </div>
    );
};

export default Login;
