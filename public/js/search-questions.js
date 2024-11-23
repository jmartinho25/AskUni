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
        data.results.forEach(result => {
            const questionCard = document.createElement('div');
            questionCard.classList.add('question-card');

            const title = document.createElement('h3');
            const questionLink = document.createElement('a');
            questionLink.href = `/questions/${result.posts_id}`;
            questionLink.textContent = result.title;
            questionLink.classList.add('result-title');
            title.appendChild(questionLink);
            questionCard.appendChild(title);

            const userLink = document.createElement('a');
            userLink.href = `/users/${result.user_id}`;
            userLink.textContent = result.username;
            userLink.classList.add('result-username');
            questionCard.appendChild(userLink);

            const date = document.createElement('small');
            date.textContent = ` Published on: ${result.date}`;
            date.classList.add('result-date');
            questionCard.appendChild(date);

            resultsContainer.appendChild(questionCard);
        });
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
