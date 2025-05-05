class ChatManager {
    constructor(chatId) {
        this.chatId = parseInt(chatId);
        this.init();
    }

    init() {
        this.bindEvents();
        this.initFancybox();
        this.scrollToBottom();
    }

    bindEvents() {
        // Listen for new messages from ChatNotificationSystem
        window.addEventListener("chat-message-received", (event) => {
            const data = event.detail;
            console.log("ChatManager received message:", data);

            if (data.message && data.message.chat_id === this.chatId) {
                this.appendNewMessage(data.message);
            }
        });

        // File input handler
        const fileInput = document.querySelector(".custom-file-input");
        if (fileInput) {
            fileInput.addEventListener(
                "change",
                this.handleFileInput.bind(this)
            );
        }
    }

    appendNewMessage(message) {
        const messagesContainer = document.getElementById("chat-messages");
        if (!messagesContainer) {
            console.error("Messages container not found");
            return;
        }

        const messageDiv = this.createMessageElement(message);
        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
        this.initFancybox(); // Reinitialize Fancybox for new images
    }

    createMessageElement(message) {
        const div = document.createElement("div");
        div.className = `message-bubble ${
            message.is_admin ? "admin-message" : "user-message"
        }`;

        // Format the date properly
        const messageDate = this.formatDate(message.created_at);

        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <strong>${message.user_name}</strong>
            </div>
            <div class="message-content mt-2">${message.message}</div>
            ${this.createImagesHtml(message)}
            <div class="message-time text-muted">
                ${messageDate}
            </div>
        `;

        return div;
    }

    createImagesHtml(message) {
        if (!message.images?.length) return "";

        const messageDate = this.formatDate(message.created_at);

        const imagesHtml = message.images
            .map(
                (url) => `
            <a href="${url}" data-fancybox="gallery-${message.id}" 
               data-caption="${message.user_name} - ${messageDate}">
                <img src="${url}" alt="Chat image" style="max-width: 150px; height: auto;">
            </a>
        `
            )
            .join("");

        return `<div class="message-images">${imagesHtml}</div>`;
    }

    initFancybox() {
        try {
            // Make sure Fancybox is available
            if (typeof Fancybox === "undefined") {
                console.error("Fancybox is not loaded");
                return;
            }

            // Destroy existing instances first
            Fancybox.destroy();

            // Initialize with options
            Fancybox.bind("[data-fancybox]", {
                Carousel: {
                    infinite: false,
                    preload: 1,
                },
                Thumbs: {
                    autoStart: false,
                },
                Image: {
                    zoom: true,
                    fit: "contain",
                },
                on: {
                    initCarousel: (fancybox) => {
                        console.log("Fancybox initialized successfully");
                    },
                },
            });
        } catch (error) {
            console.error("Error initializing Fancybox:", error);
        }
    }

    handleFileInput(e) {
        const fileName = Array.from(this.files)
            .map((file) => file.name)
            .join(", ");
        const label = this.nextElementSibling;
        label.textContent = fileName || "აირჩიეთ სურათები";
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById("chat-messages");
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    formatDate(dateString) {
        try {
            // Handle both ISO string and timestamp formats
            const date =
                typeof dateString === "string"
                    ? new Date(dateString)
                    : new Date(dateString.date || dateString);

            if (isNaN(date.getTime())) {
                console.error("Invalid date:", dateString);
                return "Invalid date";
            }

            return date.toLocaleString("ka-GE", {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                hour12: false,
            });
        } catch (error) {
            console.error("Error formatting date:", error);
            return "Invalid date";
        }
    }
}
