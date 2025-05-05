if (typeof ChatNotificationSystem === "undefined") {
    class ChatNotificationSystem {
        constructor() {
            this.audioContext = null;
            this.audioBuffer = null;
            this.hasNotificationPermission = false;
            this.pusher = null;
            this.channel = null;
            this.init();
        }

        init() {
            this.initializePusher();
            this.bindEvents();
            this.requestInitialPermissions();
        }

        initializePusher() {
            this.pusher = new Pusher(window.pusherConfig.key, {
                cluster: window.pusherConfig.cluster,
                encrypted: true,
            });
            this.channel = this.pusher.subscribe("chat");

            this.pusher.connection.bind("connected", () => {
                console.log("Pusher connected");
            });
        }

        async requestInitialPermissions() {
            if (Notification.permission !== "granted") {
                const permission = await Notification.requestPermission();
                this.hasNotificationPermission = permission === "granted";
            } else {
                this.hasNotificationPermission = true;
            }
            await this.requestPermissions();

            // Add click event listener for permission
            document.addEventListener(
                "click",
                () => this.requestPermissions(),
                {
                    once: true,
                }
            );
        }

        async requestPermissions() {
            try {
                if ("Notification" in window) {
                    if (Notification.permission === "granted") {
                        this.hasNotificationPermission = true;
                    } else {
                        const permission =
                            await Notification.requestPermission();
                        this.hasNotificationPermission =
                            permission === "granted";
                    }
                }

                if (!this.audioContext) {
                    this.audioContext = new (window.AudioContext ||
                        window.webkitAudioContext)();
                    await this.loadNotificationSound();
                }

                return this.hasNotificationPermission;
            } catch (error) {
                console.error("Error requesting permissions:", error);
                return false;
            }
        }

        async loadNotificationSound() {
            try {
                const response = await fetch("/notification.wav");
                const arrayBuffer = await response.arrayBuffer();
                this.audioBuffer = await this.audioContext.decodeAudioData(
                    arrayBuffer
                );
            } catch (error) {
                console.error("Error loading notification sound:", error);
            }
        }

        async playNotificationSound() {
            try {
                if (this.audioContext && this.audioBuffer) {
                    if (this.audioContext.state === "suspended") {
                        await this.audioContext.resume();
                    }
                    const source = this.audioContext.createBufferSource();
                    source.buffer = this.audioBuffer;
                    source.connect(this.audioContext.destination);
                    source.start(0);
                }
            } catch (error) {
                console.error("Error playing sound:", error);
            }
        }

        bindEvents() {
            this.channel.bind("new-message", async (data) => {
                // Emit a custom event that ChatManager can listen to
                const event = new CustomEvent("chat-message-received", {
                    detail: data,
                });
                window.dispatchEvent(event);

                await this.handleNewMessage(data);
            });
        }

        async handleNewMessage(data) {
            this.showInPageNotification(data);
            await this.showBrowserNotification(data);
            this.updateBadgeCount();
        }

        showInPageNotification(data) {
            const inPageNotification = document.getElementById("notification");
            if (inPageNotification) {
                inPageNotification.textContent = `ახალი შეტყობინება: ${data.message.user_name}`;
                inPageNotification.style.display = "block";
                setTimeout(
                    () => (inPageNotification.style.display = "none"),
                    5000
                );
            }
        }

        async showBrowserNotification(data) {
            if (
                this.hasNotificationPermission ||
                Notification.permission === "granted"
            ) {
                try {
                    const notification = new Notification("ახალი შეტყობინება", {
                        body: `${data.message.user_name}: ${data.message.message}`,
                        icon: "/notification.png",
                        badge: "/notification.png",
                        tag: "chat-message",
                        requireInteraction: true,
                        vibrate: [200, 100, 200],
                        renotify: true,
                        silent: false,
                        data: {
                            url: `/chats/${data.message.chat_id}`,
                        },
                    });

                    notification.onclick = (event) => {
                        event.preventDefault();
                        if (window.innerWidth <= 768) {
                            window.location.href = notification.data.url;
                        } else {
                            window.focus();
                            window.location.href = notification.data.url;
                        }
                        notification.close();
                    };

                    await this.playNotificationSound();
                } catch (error) {
                    console.error("Error showing notification:", error);
                }
            }
        }

        updateBadgeCount() {
            const badge = document.querySelector("#chat-unread-badge");
            if (badge) {
                const currentCount = parseInt(badge.textContent || "0");
                badge.textContent = currentCount + 1;
                badge.style.display = "inline";
            }
        }
    }

    // Make it globally available
    window.ChatNotificationSystem = ChatNotificationSystem;
} else {
    console.log("ChatNotificationSystem already initialized");
}
