let modal = document.querySelectorAll(".modal");
let btn = document.querySelectorAll("button.modal-button");
let closer = document.getElementsByClassName("close");

for (let i = 0; i < btn.length; i++) {
    btn[i].onclick = function() {
        modal[i].style.display = "block";
    }
}

for (let i = 0; i < closer.length; i++) {
    closer[i].onclick = function() {
        modal[i].style.display = "none";
        const historyDetailsContainer = document.querySelectorAll('.edit-history-details');
        historyDetailsContainer.forEach(item => item.innerHTML = 'Select an edit history to view details.');
    }
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        const historyDetailsContainer = document.querySelectorAll('.edit-history-details');
        historyDetailsContainer.forEach(item => item.innerHTML = 'Select an edit history to view details.');
        for (let index in modal) {
            if (typeof modal[index].style !== 'undefined') modal[index].style.display = "none"; 
        }
    }
}


document.querySelectorAll('.date-item').forEach(item => {
    item.addEventListener('click', function() {
        const historyId = this.getAttribute('data-history-id');
        fetchEditHistoryById(historyId);
    });
});

function escapeHTMLModal(str) {
    return str.replace(/[&<>"'`=\/]/g, function (s) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        }[s];
    });
}

function fetchEditHistoryById(id) {
    const historyDetailsContainer = document.querySelectorAll('.edit-history-details');
    historyDetailsContainer.forEach(item => item.innerHTML = '<p><i class="fas fa-circle-notch fa-spin"></i></p>');
    
    fetch(`/api/edit-history/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                const previousContent = escapeHTMLModal(data.previous_content);
                const newContent = escapeHTMLModal(data.new_content);
                for (let i = 0; i < historyDetailsContainer.length; i++) {
                    historyDetailsContainer[i].innerHTML = `
                        <h4> ${data.date} </h4>
                        <p><strong>Previous Content:</strong> ${previousContent}</p>
                        <p><strong>New Content:</strong> ${newContent}</p>
                        <hr>
                    `;
                }
            } else {
                for (let i = 0; i < historyDetailsContainer.length; i++) {
                    historyDetailsContainer[i].innerHTML = `<p>No edit history available.</p>`;
                }
            }
        })
        .catch(error => {
            console.error('Error fetching edit history:', error);
            historyDetailsContainer.innerHTML = `<p>An error occurred while fetching the edit history.</p>`;
        });
}
