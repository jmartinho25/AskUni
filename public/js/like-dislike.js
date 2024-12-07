document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-btn');
    const dislikeButtons = document.querySelectorAll('.dislike-btn');

    likeButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Previne o envio do formulário
            const postId = this.getAttribute('data-post-id');
            const isLiked = this.classList.contains('btn-like-active');

            fetch(`/posts/${postId}/like`, {
                method: isLiked ? 'DELETE' : 'POST', // Use DELETE se já estiver curtido
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok'); // Lança um erro se a resposta não for ok
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Verifique a resposta da API

                if (data.success) {
                    // Alterna o estado do botão de like
                    this.classList.toggle('btn-like-active');
                    this.querySelector('i').classList.toggle('fas');
                    this.querySelector('i').classList.toggle('far');
                    
                    // Atualiza o número de likes
                    const likeCountElement = this.querySelector('.like-count');
                    if (likeCountElement) {
                        likeCountElement.textContent = data.likesCount; // Atualiza o número de likes
                    }
            
                    // Remove dislike se estava ativo
                    const dislikeButton = document.querySelector(`.dislike-btn[data-post-id="${postId}"]`);
                    if (dislikeButton && dislikeButton.classList.contains('btn-like-active')) {
                        dislikeButton.classList.remove('btn-like-active');
                        dislikeButton.querySelector('i').classList.remove('fas');
                        dislikeButton.querySelector('i').classList.add('far');
                        const dislikeCountElement = dislikeButton.querySelector('.dislike-count');
                        if (dislikeCountElement) {
                            dislikeCountElement.textContent = data.dislikesCount; // Atualiza o número de dislikes
                        }
                    }
                } else {
                    alert('Failed to update like status.'); // Mensagem de erro se a resposta não for bem-sucedida
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                alert(`An error occurred: ${error.message || 'Unknown error'}`);
            });
        });
    });

    dislikeButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Previne o envio do formulário
            const postId = this.getAttribute('data-post-id');
            const isDisliked = this.classList.contains('btn-like-active');

            fetch(`/posts/${postId}/dislike`, {
                method: isDisliked ? 'DELETE' : 'POST', // Use DELETE se já estiver descurtido
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok'); // Lança um erro se a resposta não for ok
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Alterna o estado do botão de dislike
                    this.classList.toggle('btn-like-active');
                    this.querySelector('i').classList.toggle('fas');
                    this.querySelector('i').classList.toggle('far');
                    this.querySelector('.dislike-count').textContent = data.dislikesCount; // Atualiza o número de dislikes

                    // Remove like se estava ativo
                    const likeButton = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
                    if (likeButton && likeButton.classList.contains('btn-like-active')) {
                        likeButton.classList.remove('btn-like-active');
                        likeButton.querySelector('i').classList.remove('fas');
                        likeButton.querySelector('i').classList.add('far');
                        likeButton.querySelector('.like-count').textContent = data.likesCount; // Atualiza o número de likes
                    }
                } else {
                    alert('Failed to update dislike status.'); // Mensagem de erro se a resposta não for bem-sucedida
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                alert(`An error occurred: ${error.message || 'Unknown error'}`);
            });
        });
    });
});