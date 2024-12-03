document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('results-container');
    const paginationContainer = document.getElementById('pagination-container');

    async function fetchUsers(query = '', page = 1) {
        resultsContainer.innerHTML = '<p>Loading...</p>';
        paginationContainer.innerHTML = '';

        try {
            const response = await fetch(`/admin/users/search?query=${encodeURIComponent(query)}&page=${page}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            displayResults(data);
        } catch (error) {
            console.error('Error fetching users:', error);
            resultsContainer.innerHTML = '<p>Error loading results. Please try again later.</p>';
        }
    }

    function displayResults(data) {
        resultsContainer.innerHTML = '';

        if (data.data.length === 0) {
            resultsContainer.innerHTML = '<p>No users found.</p>';
            return;
        }

        data.data.forEach(user => {
            const row = document.createElement('div');
            row.classList.add('user-row');
            row.innerHTML = `
                <p><strong>Name:</strong> ${user.name}</p>
                <p><strong>Email:</strong> ${user.email}</p>
            `;
            resultsContainer.appendChild(row);
        });

        setupPagination(data);
    }

    function setupPagination(data) {
        paginationContainer.innerHTML = '';

        if (data.total > data.per_page) {
            for (let page = 1; page <= data.last_page; page++) {
                const pageLink = document.createElement('button');
                pageLink.textContent = page;
                pageLink.classList.add('pagination-button');
                if (page === data.current_page) {
                    pageLink.disabled = true;
                }
                pageLink.addEventListener('click', () => fetchUsers(searchInput.value, page));
                paginationContainer.appendChild(pageLink);
            }
        }
    }

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();
        fetchUsers(query);
    });

    fetchUsers();
});