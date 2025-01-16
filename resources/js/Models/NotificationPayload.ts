import { getLocale, getNestedPropertyValue, user } from "@/helper";
import { GET } from "@/Modules/Http";

export class NotificationPayload {
    public collapseKey?: string;
    public data?: NotificationPayloadData | Record<string, any>;
    public id?: string;
    public type?: string;
    public message_en?: string;
    public message_ar?: string;
    public message?: string;
    public body?: {
        body: string;
        body_ar: string;
    };
    public read_at?: string;
    public created_at?: string;
    public notificationData?: Record<string, any>;
    public title?: string;
    private from?: string;
    private messageId?: string;

    constructor(
        collapseKey?: string,
        data?: NotificationPayloadData,
        from?: string,
        messageId?: string,
        message?: string,
        read_at?: string,
        created_at?: string,
        type?: string,
        id?: string,
    ) {
        this.collapseKey = collapseKey;
        this.body = {
            body:
                getNestedPropertyValue(data, "body") ??
                getNestedPropertyValue(data, "body_en"),
            body_ar: getNestedPropertyValue(data, "body_ar"),
        };

        this.message = getNestedPropertyValue(data, "message_en");

        try {
            this.message_en =
                (message
                    ? getNestedPropertyValue(
                          JSON.parse(message) ?? {},
                          "message_en",
                      )
                    : (getNestedPropertyValue(data, "message") ??
                      getNestedPropertyValue(data, "message_en"))) ?? message;
        } catch (e) {
            this.message_en = message;
        }

        try {
            this.message_ar = this.message_en =
                (message
                    ? getNestedPropertyValue(
                          JSON.parse(message) ?? {},
                          "message_ar",
                      )
                    : getNestedPropertyValue(data, "message_ar")) ?? message;
        } catch (e) {
            this.message_ar = message;
        }

        this.title = getNestedPropertyValue(data, "title");
        this.notificationData = JSON.parse(data?.data ?? "{}");
        this.read_at = read_at;
        this.created_at = created_at;
        this.type = type;
        this.data = data;
        this.from = from;
        this.messageId = messageId;
        this.id = id;
    }

    /**
     * getNotificationType
     */
    public getNotificationType(): string | undefined {
        return this.data?.type ?? this.type;
    }

    /**
     * getFromData
     */
    public getFromData(key: string): string | undefined {
        const data = JSON.parse(this.data?.data ?? "{}");
        return (
            getNestedPropertyValue(data, key) ??
            getNestedPropertyValue(this.data, key)
        );
    }

    public isNotification() {
        if (this.getNotificationType() != undefined) {
            return (Object.values(NotificationsType) as Array<string>).includes(
                String(this.getNotificationType()),
            );
        } else {
            return false;
        }
    }

    public getMessage() {
        const locale = getLocale() ?? "en";
        if (locale == "ar") {
            return this.message_ar;
        } else {
            return this.message;
        }
    }

    public getUrl(): string {
        const type = this.getNotificationType();
        switch (type) {
            case NotificationsType.FileUpdatedNotification:
                return route(
                    "v1.web.customer.files.show",
                    getNestedPropertyValue(this.data, "file_id"),
                );
            default:
                return "#";
        }
    }
}

export interface NotificationPayloadData {
    body?: string;
    body_ar?: string;
    data?: string; // json data
    message?: string;
    message_ar?: string;
    title?: string;
    type: string;
}

export enum NotificationsType {
    FileUpdatedNotification = "Customer\\FileUpdatedNotification",
}

export interface Notification {
    id: string;
    data: string;
    type: string;
    message: string;
    read_at: string;
    created_at: string;
}
