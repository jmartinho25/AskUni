let time = 0;

function showLoadingIndicator(container) {
    container.innerHTML = '<p><i class="fas fa-circle-notch fa-spin"></i></p>';
}

function displayResults(data, resultsContainer) {
    resultsContainer.innerHTML = '';
    if (data.length === 0) {
        resultsContainer.innerHTML = '<p>No results found.</p>';
    } else {
        data.forEach(user => {
            const tr = document.createElement('tr');
            if (user.deleted_at) {
                tr.classList.add('table-danger');
            }

            tr.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>
                    ${user.deleted_at ? `
                        <span class="text-danger">Deleted</span>
                        <form action="/users/restore/${user.id}" method="POST" style="display:inline-block;">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to restore this user?')">
                                Restore
                            </button>
                        </form>
                    ` : `
                        ${!user.is_admin && ${Auth::user()->id} != user.id ? `
                            <form action="/admin/users/destroy/${user.id}" method="POST" style="display:inline-block;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </form>
                        ` : `
                            <span class="text-muted">Cannot delete admin</span>
                        `}
                    `}
                </td>
            `;

            resultsContainer.appendChild(tr);
        });
    }
}

function showError(resultsContainer) {
    resultsContainer.innerHTML = '<p>Error loading results. Please try again later.</p>';
}

async function fetchAndDisplay(url, resultsContainer) {
    showLoadingIndicator(resultsContainer);

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        displayResults(data, resultsContainer);
    } catch (error) {
        console.error('Error fetching data:', error);
        showError(resultsContainer);
    }
}

function performSearch() {
    const query = document.getElementById('user-search-input').value;
    const url = `/admin/users/search?query=${encodeURIComponent(query)}`;

    const resultsContainer = document.getElementById('users-table-body');

    // Clear previous results
    resultsContainer.innerHTML = '';

    fetchAndDisplay(url, resultsContainer);
}

function searchWithDelay() {
    clearTimeout(time);
    time = setTimeout(function() {
        performSearch();
    }, 300); //300 ms
}

document.getElementById('user-search-input').addEventListener('input', searchWithDelay);
document.getElementById('user-search-bar').addEventListener('submit', function(event) {
    event.preventDefault();
    performSearch();
});