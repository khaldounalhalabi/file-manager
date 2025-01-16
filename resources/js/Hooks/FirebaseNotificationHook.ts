import { useEffect, useState } from "react";
import { getMessaging, getToken } from "firebase/messaging";
import firebaseApp from "@/Config/Firebase";
import { GET, POST } from "@/Modules/Http";

const useFcmToken = () => {
    const [token, setToken] = useState("");
    const [notificationPermissionStatus, setNotificationPermissionStatus] =
        useState("");
    console.log(token);

    useEffect(() => {
        const retrieveToken = async () => {
            try {
                if (
                    typeof window !== "undefined" &&
                    "serviceWorker" in navigator
                ) {
                    const messaging = getMessaging(firebaseApp);

                    const permission = await Notification.requestPermission();
                    setNotificationPermissionStatus(permission);

                    if (permission === "granted") {
                        const currentToken = await getToken(messaging, {
                            vapidKey:
                                "BMafV2ACMZgBjYmEcT5jZC2kJfGOAd1-F8keDYKsSNQ7k-qtAJTiBQFjkuQPyNMDJm1ltRdF7cTs8PxnMB6Ka8E",
                        });

                        let prevToken = await GET<{ fcm_token: string }>(
                            route("fcm.get.token"),
                        ).then((res) => {
                            return res?.data?.fcm_token;
                        });
                        if (currentToken != prevToken) {
                            await POST(route("fcm.store.token"), {
                                fcm_token: currentToken,
                            });
                            setToken(currentToken);
                        } else {
                            setToken(currentToken);
                        }
                    }
                }
            } catch (error) {
                console.log("An error occurred while retrieving token:", error);
            }
        };

        retrieveToken();
    }, []);

    return { fcmToken: token, notificationPermissionStatus };
};

export default useFcmToken;
