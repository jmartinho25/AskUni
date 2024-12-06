document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sort');
    const questionsContainer = document.querySelector('.all-questions');
    const paginationContainer = document.querySelector('.pagination');

    sortSelect.addEventListener('change', function() {
        const tagName = sortSelect.dataset.tagName;
        const sortValue = sortSelect.value;

        fetchQuestions(tagName, sortValue);
    });

    function showLoadingIndicator(container, paginationContainer = null) {
        container.innerHTML = '<p><i class="fas fa-circle-notch fa-spin"></i></p>';
        if (paginationContainer) paginationContainer.innerHTML = '';
    }

    function fetchQuestions(tagName, sortValue, pageUrl = null) {
        const url = pageUrl ? pageUrl : `/api/tags/${tagName}?sort=${sortValue}`;

        showLoadingIndicator(questionsContainer, paginationContainer);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                questionsContainer.innerHTML = '';
                data.data.forEach(question => {
                    const questionCard = document.createElement('div');
                    questionCard.classList.add('question-card');

                    const title = document.createElement('h3');
                    const questionLink = document.createElement('a');
                    questionLink.href = `/questions/${question.posts_id}`;
                    questionLink.textContent = question.title;
                    questionLink.classList.add('result-title');
                    title.appendChild(questionLink);
                    questionCard.appendChild(title);

                    if (question.post.user) {
                        const userLink = document.createElement('a');
                        userLink.href = `/profile/${question.post.user.id}`;
                        userLink.textContent = question.post.user.name;
                        userLink.classList.add('question-user-name');
                        questionCard.appendChild(userLink);
                    } else {
                        const deletedUserSpan = document.createElement('span');
                        deletedUserSpan.textContent = 'Deleted User';
                        deletedUserSpan.classList.add('question-user-name');
                        questionCard.appendChild(deletedUserSpan);
                    }

                    const date = document.createElement('small');
                    date.textContent = `Published on: ${question.post.date}`;
                    date.classList.add('result-date');
                    questionCard.appendChild(date);

                    questionsContainer.appendChild(questionCard);
                });

                paginationContainer.innerHTML = data.links;
                addPaginationEventListeners(tagName, sortValue);
            })
            .catch(error => {
                console.error('Error fetching questions:', error);
                questionsContainer.innerHTML = '<p>Error loading questions. Please try again later.</p>';
            });
    }

    function addPaginationEventListeners(tagName, sortValue) {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const url = new URL(this.href);
                if (!url.pathname.startsWith('/api')) {
                    url.pathname = `/api${url.pathname}`;
                }
                url.searchParams.set('sort', sortValue);

                fetchQuestions(tagName, sortValue, url.toString());
            });
        });
    }

    const initialTagName = sortSelect.dataset.tagName;
    const initialSortValue = sortSelect.value;
    addPaginationEventListeners(initialTagName, initialSortValue);


    const followButton = document.getElementById('follow-button-tags');
    if (followButton) {
        followButton.addEventListener('click', function() {
            const tagName = followButton.getAttribute('data-tag-name');
            followButton.disabled = true;
            fetch(`/tags/${tagName}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                followButton.textContent = data.following ? 'Unfollow' : 'Follow';
                followButton.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                followButton.disabled = false;
            });
        });
    }
});