let currentPage = 1;
let limit = 10; // Set how many records per page
let totalPages = 1; // Will be updated dynamically
let apiUrl = 'http://localhost/api/api.php'; // Update with your API endpoint

// Function to fetch data and update the UI
async function fetchData(page = 1) {
    try {
        let response = await fetch(`${apiUrl}?page=${page}&limit=${limit}`);
        let data = await response.json();

        // Update the totalPages variable
        totalPages = Math.ceil(data.total / limit);

        // Update user table body with fetched data
        const userTableBody = document.getElementById('userTableBody');
        userTableBody.innerHTML = ''; // Clear existing rows

        data.data.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.age}</td>
                <td><a href="/api/php/phpupdate.php?id=${user.id}" class="btn-link update-link">Update</a></td>
                <td><a href="/api/php/phpsubmit.php?id=${user.id}&method=DELETE" class="btn-link delete-link">Delete</a></td>
            `;
            userTableBody.appendChild(row);
        });

        // Update pagination info
        document.getElementById('pageInfo').textContent = `Page ${page} of ${totalPages}`;

        // Enable/disable pagination buttons
        document.getElementById('prevPage').disabled = page <= 1;
        document.getElementById('nextPage').disabled = page >= totalPages;

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// Function to change the page
function changePage(direction) {
    if (direction === 'next' && currentPage < totalPages) {
        currentPage++;
    } else if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    }

    fetchData(currentPage);
}

// Initial fetch to load the first page
fetchData(currentPage);
