const Ziggy = {
    url: "http://localhost",
    port: null,
    defaults: {},
    routes: {
        "cubeta.starter.settings": {
            uri: "cubeta-starter/settings",
            methods: ["GET", "HEAD"],
        },
        "cubeta.starter.settings.set": {
            uri: "cubeta-starter/settings",
            methods: ["POST"],
        },
        "cubeta.starter.add.actor": {
            uri: "cubeta-starter/add-actor",
            methods: ["POST"],
        },
        "cubeta.starter.clear.logs": {
            uri: "cubeta-starter/clear-logs",
            methods: ["GET", "HEAD"],
        },
        "cubeta.starter.generate.page": {
            uri: "cubeta-starter",
            methods: ["GET", "HEAD"],
        },
        "cubeta.starter.generate": {
            uri: "cubeta-starter/generate",
            methods: ["POST"],
        },
        "v1.web.public.login": { uri: "v1/dashboard/login", methods: ["POST"] },
        "v1.web.public.request.reset.password.code": {
            uri: "v1/dashboard/request-reset-password-code",
            methods: ["POST"],
        },
        "v1.web.public.validate.reset.password.code": {
            uri: "v1/dashboard/validate-reset-password-code",
            methods: ["POST"],
        },
        "v1.web.public.change.password": {
            uri: "v1/dashboard/change-password",
            methods: ["POST"],
        },
        "v1.web.public.register": {
            uri: "v1/dashboard/register",
            methods: ["POST"],
        },
        "v1.web.protected.update.user.data": {
            uri: "v1/dashboard/update-user-data",
            methods: ["PUT"],
        },
        "v1.web.protected.user.details": {
            uri: "v1/dashboard/user-details",
            methods: ["GET", "HEAD"],
        },
        "v1.web.protected.logout": {
            uri: "v1/dashboard/logout",
            methods: ["GET", "HEAD"],
        },
        "storage.local": {
            uri: "storage/{path}",
            methods: ["GET", "HEAD"],
            wheres: { path: ".*" },
            parameters: ["path"],
        },
    },
};
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
