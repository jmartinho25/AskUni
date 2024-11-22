let time = 0;

function showLoadingIndicator(container, paginationContainer = null) {
    container.innerHTML = '<p><i class="fas fa-circle-notch fa-spin"></i></p>';
    if (paginationContainer) paginationContainer.innerHTML = '';
}

function displayResults(data, query, resultsContainer, paginationContainer) {
    resultsContainer.innerHTML = '';
    if (data.results.length === 0) {
        resultsContainer.innerHTML = `<p>No results found for "${query}".</p>`;
    } else {
        const ul = document.createElement('ul');
        ul.classList.add('results-list');
        data.results.forEach(result => {
            const li = document.createElement('li');
            li.classList.add('result-item');

            const questionLink = document.createElement('a');
            questionLink.href = `/questions/${result.posts_id}`;
            questionLink.textContent = result.title;
            questionLink.classList.add('result-title');
            li.appendChild(questionLink);

            const userLink = document.createElement('a');
            userLink.href = `/users/${result.user_id}`;
            userLink.textContent = result.username;
            userLink.classList.add('result-username');
            li.appendChild(userLink);

            const date = document.createElement('small');
            date.textContent = ` Published on: ${result.date}`;
            date.classList.add('result-date');
            li.appendChild(date);

            ul.appendChild(li);
        });
        resultsContainer.appendChild(ul);
    }
    paginationContainer.innerHTML = data.pagination;
    addPaginationEventListeners();
}

function showError(resultsContainer) {
    resultsContainer.innerHTML = '<p>Error loading results. Please try again later.</p>';
}

async function fetchAndDisplay(url, query, resultsContainer, paginationContainer) {
    showLoadingIndicator(resultsContainer, paginationContainer);

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
        displayResults(data, query, resultsContainer, paginationContainer);
    } catch (error) {
        console.error('Error fetching data:', error);
        showError(resultsContainer);
    }
}

function performSearch() {
    const query = document.getElementById('search-input').value;
    const exactMatch = document.getElementById('exact-match').checked;
    const url = `/api/questions/search?query=${encodeURIComponent(query)}&exact_match=${exactMatch ? 1 : 0}`;

    const resultsContainer = document.getElementById('results-container');
    const paginationContainer = document.getElementById('pagination-container');

    fetchAndDisplay(url, query, resultsContainer, paginationContainer);
}

function searchWithDelay() {
    clearTimeout(time);
    time = setTimeout(function() {
        performSearch();
    }, 300); //300 ms
}

document.getElementById('search-input').addEventListener('input', searchWithDelay);
document.getElementById('exact-match').addEventListener('change', performSearch);
document.getElementById('search-bar').addEventListener('submit', function(event) {
    event.preventDefault();
    navigateToSearchPage();
});

document.getElementById('search-button').addEventListener('click', function(event) {
    event.preventDefault();
    navigateToSearchPage();
});

function navigateToSearchPage() {
    const query = document.getElementById('search-input').value;
    const exactMatch = document.getElementById('exact-match').checked;
    const searchPageUrl = `/questions/search?query=${encodeURIComponent(query)}&exact_match=${exactMatch ? 1 : 0}`;

    if (window.location.pathname !== '/questions/search') {
        window.location.href = searchPageUrl;
    } else {
        performSearch();
    }
}

function addPaginationEventListeners() {
    document.querySelectorAll('#pagination-container a').forEach(link => {
        link.addEventListener('click', async function (event) {
            event.preventDefault();
            const urlParams = new URLSearchParams(this.search);
            const query = urlParams.get('query');
            const exactMatch = urlParams.get('exact_match');
            const page = urlParams.get('page');
            const url = `/api/questions/search?query=${encodeURIComponent(query)}&exact_match=${exactMatch}&page=${page}`;

            const resultsContainer = document.getElementById('results-container');
            const paginationContainer = document.getElementById('pagination-container');

            await fetchAndDisplay(url, '', resultsContainer, paginationContainer);
        });
    });
}

addPaginationEventListeners();
