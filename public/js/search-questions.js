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

            if (result.user_id && result.username) {
                const userLink = document.createElement('a');
                userLink.href = `/users/${result.user_id}`;
                userLink.textContent = result.username;
                userLink.classList.add('result-username');
                questionCard.appendChild(userLink);
            } else {
                const deletedUserSpan = document.createElement('span');
                deletedUserSpan.textContent = 'Deleted User';
                deletedUserSpan.classList.add('question-user-name');
                questionCard.appendChild(deletedUserSpan);
            }

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
    const order = document.getElementById('order').value;
    const url = `/api/questions/search?query=${encodeURIComponent(query)}&exact_match=${exactMatch ? 1 : 0}&order=${order}`;

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

document.addEventListener('DOMContentLoaded', function() {
    const queryInput = document.getElementById('search-input');
    const exactMatch = document.getElementById('exact-match');

    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('query');
    const exactMatchValue = urlParams.get('exact_match');

    if (query) {
        queryInput.value = query;
    }
    if (exactMatchValue) {
        exactMatch.checked = exactMatchValue === 'on';
    }

    queryInput.addEventListener('input', searchWithDelay);
    exactMatch.addEventListener('change', performSearch);
    document.getElementById('order').addEventListener('change', performSearch);
    document.getElementById('search-bar').addEventListener('submit', function(event) {
        event.preventDefault();
        navigateToSearchPage();
    });

    document.getElementById('search-button').addEventListener('click', function(event) {
        event.preventDefault();
        navigateToSearchPage();
    });
});

function navigateToSearchPage() {
    const query = document.getElementById('search-input').value;
    const exactMatch = document.getElementById('exact-match').checked;
    const order = document.getElementById('order').value;
    const searchPageUrl = `/questions/search?query=${encodeURIComponent(query)}&exact_match=${exactMatch ? 1 : 0}&order=${order}`;

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
            const order = urlParams.get('order');
            const page = urlParams.get('page');
            const url = `/api/questions/search?query=${encodeURIComponent(query)}&exact_match=${exactMatch}&order=${order}&page=${page}`;

            const resultsContainer = document.getElementById('results-container');
            const paginationContainer = document.getElementById('pagination-container');

            await fetchAndDisplay(url, '', resultsContainer, paginationContainer);
        });
    });
}

addPaginationEventListeners();
