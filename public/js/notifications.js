let notificationsData = [];
let isLoading = false;
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


document.addEventListener('DOMContentLoaded', function() {
    fetchNotifications();

    document.getElementById('notification-bell').addEventListener('click', function(event) {
        event.preventDefault();
        let dropdown = document.getElementById('notifications-dropdown');
        dropdown.classList.toggle('active');
        if (dropdown.classList.contains('active')) {
            if (isLoading) {
                showLoadingIndicator();
            } else {
                populateNotifications();
            }
        }
    });

    document.addEventListener('click', function(event) {
        let dropdown = document.getElementById('notifications-dropdown');
        let bell = document.getElementById('notification-bell');
        if (!dropdown.contains(event.target) && !bell.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    document.getElementById('notifications-dropdown').addEventListener('click', function(event) {
        event.stopPropagation(); // prevent closing the dropdown
    });

    document.getElementById('mark-all-as-read').addEventListener('click', async function (event) {
        event.preventDefault();
        await markAllNotificationsRead();
    });

    setInterval(fetchNotifications, 15000);
});

async function fetchNotifications() {
    isLoading = true;
    try {
        const response = await fetch('/api/notifications');
        notificationsData = await response.json();
        updateNotificationsIcon();
        populateNotifications();
    } catch (error) {
        console.error('Error fetching notifications:', error);
    } finally {
        isLoading = false;
    }
}

function showLoadingIndicator() {
    let notificationsList = document.getElementById('notifications-list');
    notificationsList.innerHTML = '<li> <i class="fas fa-sync fa-spin"></i> </li>';
}

function populateNotifications() {
    let notificationsList = document.getElementById('notifications-list');
    notificationsList.innerHTML = '';

    notificationsData.sort((a, b) => new Date(b.date) - new Date(a.date));

    if (notificationsData.length === 0) {
        let listItem = document.createElement('li');
        listItem.innerHTML = 'No notifications';
        notificationsList.appendChild(listItem);
    } else {
        notificationsData.forEach(notification => {
            let listItem = document.createElement('li');
            listItem.innerHTML = `
                ${notification.content}
                ${notification.url ? `<a href="${notification.url}" class="btn btn-primary btn-sm">View</a>` : ''}
                <button id="mark-as-read" class="btn btn-primary btn-sm mark-as-read" data-id="${notification.id}">Mark as Read</button>
            `;
            notificationsList.appendChild(listItem);
        });

        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', async function(event) {
                event.preventDefault();
                event.stopPropagation();
                const notificationId = this.getAttribute('data-id');
                await markNotificationAsRead(notificationId);
            });
        });
    }
}

async function markNotificationAsRead(notificationId) {
    notificationsData = notificationsData.filter(notification => notification.id != notificationId);
    populateNotifications();
    updateNotificationsIcon();

    try {
        const response = await fetch(`/api/notifications/${notificationId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (!data.message) {
            await fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
        await fetchNotifications();
    }
}

async function markAllNotificationsRead() {
    notificationsData = [];
    populateNotifications();
    updateNotificationsIcon();

    try {
        const response = await fetch('/api/notifications/mark-all-read', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (!data.message) {
            await fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
        await fetchNotifications();
    }
}

function updateNotificationsIcon() {
    let len = notificationsData.length;
    let icon = document.getElementById('notification-icon');
    if (len > 0) {
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
    } else {
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
    }
}
