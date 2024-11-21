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

    document.getElementById('mark-all-as-read').addEventListener('click', function(event) {
        event.preventDefault();
        markAllNotificationsRead();
    });

    setInterval(fetchNotifications, 15000);
});

function fetchNotifications() {
    isLoading = true;
    fetch('/api/notifications')
        .then(response => response.json())
        .then(data => {
            notificationsData = data;
            isLoading = false;
            updateNotificationsIcon();
            populateNotifications();
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            isLoading = false;
        });
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
                <button class="btn btn-primary btn-sm mark-as-read" data-id="${notification.id}">Mark as Read</button>
            `;
            notificationsList.appendChild(listItem);
        });

        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                let notificationId = this.getAttribute('data-id');

                notificationsData = notificationsData.filter(notification => notification.id != notificationId);
                populateNotifications();
                updateNotificationsIcon();

                fetch(`/api/notifications/${notificationId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.message) {
                        fetchNotifications();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    fetchNotifications();
                });
            });
        });
    }
}

function markAllNotificationsRead() {
    notificationsData = [];
    populateNotifications();
    updateNotificationsIcon();

    fetch('/api/notifications/mark-all-read', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.message) {
            fetchNotifications();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        fetchNotifications();
    });
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