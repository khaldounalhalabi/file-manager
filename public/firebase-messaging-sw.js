// File: firebase-messaging-sw.js
// eslint-disable-next-line no-undef
importScripts("https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js");
// eslint-disable-next-line no-undef
importScripts(
    "https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js",
);

// Set Firebase configuration, once available
self.addEventListener("fetch", () => {
    try {
        const urlParams = new URLSearchParams(location.search);
        self.firebaseConfig = Object.fromEntries(urlParams);
    } catch (err) {
        console.error("Failed to add event listener", err);
    }
});

// "Default" Firebase configuration (prevents errors)
const defaultConfig = {
    apiKey: true,
    projectId: true,
    messagingSenderId: true,
    appId: true,
};

// Initialize Firebase app
// eslint-disable-next-line no-undef
firebase.initializeApp(self.firebaseConfig || defaultConfig);
let messaging;
try {
    // eslint-disable-next-line no-undef
    messaging = firebase.messaging.isSupported() ? firebase.messaging() : null;
} catch (err) {
    console.error("Failed to initialize Firebase Messaging");
}

function getNestedPropertyValue(object, path) {
    const properties = path.split("."); // Split the path string by dot to get individual property names
    let value = object;
    for (const property of properties) {
        if (value?.hasOwnProperty(property)) {
            value = value[`${property}`]; // Access the property dynamically
        } else {
            return undefined; // Property doesn't exist
        }
    }
    return value;
}

function getFromData(data, key) {
    return getNestedPropertyValue(JSON.parse(data ?? "{}"), key);
}

// To dispaly background notifications
if (messaging) {
    try {
        messaging.onBackgroundMessage((payload) => {
            const notificationTitle =
                payload?.data?.title ?? "New Notification";
            const notificationOptions = {
                body: payload?.data?.message ?? "New Notification",
                icon: "/next.svg",
            };
            //
            // function getObjectAttributesAsString(obj, prefix = "") {
            //   let result = "";
            //
            //   for (let key in obj) {
            //     if (obj.hasOwnProperty(key)) {
            //       let value = obj[key];
            //       let currentPath = prefix ? `${prefix}.${key}` : key;
            //
            //       if (
            //         typeof value === "object" &&
            //         value !== null &&
            //         !Array.isArray(value)
            //       ) {
            //         // Recursively handle nested objects
            //         result += getObjectAttributesAsString(value, currentPath);
            //       } else if (Array.isArray(value)) {
            //         // Handle arrays by converting them to strings
            //         result += `${currentPath}: ${value?.join(",")}\n`;
            //       } else {
            //         // Handle primitive values
            //         result += `${currentPath}: ${String(value)}\n`;
            //       }
            //     }
            //   }
            //
            //   return result;
            // }

            if (payload.data.type.includes("RealTime")) {
                return;
            }

            self.addEventListener("notificationclick", function (event) {
                let url = "http://localhost:3000/en";

                if (
                    payload.data.type ===
                    "Clinic\\NewOnlineAppointmentNotification"
                ) {
                    url = `http://localhost:3000/en/doctor/appointment/${getFromData(payload.data.data, "appointment_id")}`;
                } else if (
                    payload.data.type.includes(
                        "Customer\\AppointmentRemainingTimeNotification",
                    ) ||
                    payload.data.type.includes(
                        "Customer\\CustomerAppointmentChangedNotification",
                    )
                ) {
                    url = `http://localhost:3000/en/customer/appointments/${getFromData(payload.data.data, "appointment_id")}`;
                }

                event.waitUntil(
                    clients
                        .matchAll({
                            type: "window",
                        })
                        .then((clientList) => {
                            for (const client of clientList) {
                                if (client.url === url && "focus" in client)
                                    return client.focus();
                            }
                            if (clients.openWindow)
                                return clients.openWindow(url);
                        }),
                );
            });

            return self.registration.showNotification(
                notificationTitle,
                notificationOptions,
            );
        });
    } catch (err) {
        console.log(err);
    }
}
