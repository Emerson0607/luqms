{{-- this is from the window's queueing --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fetchInterval;

        function fetchUsers() {
            $.get('/client', function(client) {
                console.log(client);

                // Ensure the response is an array
                if (Array.isArray(client)) {
                    // Limit the list to 10 items (if more than 10)
                    const limitedClientList = client.slice(0, 10);

                    let userListHtml = '';

                    // Generate list items with numbers, up to 10
                    for (let i = 0; i < 10; i++) {
                        const listItemClass = i === 0 ?
                            'fw-bold text-start queue-waiting p-3 bg-opacity-75 list-group-item text-start' :
                            'text-start list-group-item pl-6  pt-3 pb-3 text-start';

                        // If we have data for this index, show it
                        if (i < limitedClientList.length) {
                            userListHtml +=
                                `<li class="${listItemClass}">${i + 1}. ${limitedClientList[i].name} - ${limitedClientList[i].number}</li>`;
                        } else {
                            // If no data for this index, show an empty row with the number
                            userListHtml +=
                                `<li class="${listItemClass}">${i + 1}. </li>`;
                        }
                    }

                    // Update the user list
                    $('#user-list').html(userListHtml);

                    // Stop refreshing if we have 10 items
                    // if (limitedClientList.length >= 10) {
                    //     clearInterval(fetchInterval);
                    // }
                } else {
                    console.error('Expected an array of users, but got:', client);
                }
            }).fail(function() {
                console.error('Error fetching users');
            });
        }

        // Start fetching users every 3 seconds
        function startAutoRefresh() {
            fetchInterval = setInterval(fetchUsers, 2000);
        }

        startAutoRefresh();
    });
</script>

{{-- for all window queue --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fetchIntervalWindow;

        function fetchWindows() {
            $.get('/windows', function(window_queue) {
                console.log(window_queue);


                // Clear existing window data
                $('#window-container').empty();

                // Loop through each window in the window_queue
                window_queue.forEach(function(window) {
                    // Create a new window card dynamically
                    const windowCard =
                        `
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                             <p class="text-start w-100">
                                    <span class="window-name" style="font-size: 12px;">${window.window_name || '---'}</span>
                                </p>
                            <div class="d-flex flex-column justify-content-center align-items-center queue-window text-center">
                               
                                <h5 style="font-size: 24px;">${window.status || 'Waiting...'}</h5>
                                <h1 style="font-size: 48px;">
                                    <span class="window-number">${window.number || '---'}</span>
                                </h1>
                                <h1 style="font-size: 24px;">
                                    <span class="window-number">${window.name || '---'}</span>
                                </h1>
                                <h6 style="font-size: 16px;">${window.department || 'window-department'}</h6>
                            </div>
                        </div>
                    </div>

                    `;

                    // Append the new window card to the container
                    $('#window-container').append(windowCard);
                });

            }).fail(function() {
                console.error('Error fetching windows');
            });
        }

        // Start fetching windows every 2 seconds
        function startAutoRefresh() {
            fetchIntervalWindow = setInterval(fetchWindows, 1000);
        }

        startAutoRefresh();
    });
</script>

{{-- display queueing window --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Retrieve stored client data from localStorage and display it on page load
        const storedClientName = localStorage.getItem('clientName') || '---';
        const storedClientNumber = localStorage.getItem('clientNumber') || '---';
        document.getElementById('client-name').innerText = storedClientName;
        document.getElementById('client-number').innerText = storedClientNumber;
        document.getElementById('client-status').innerText = storedClientNumber === '---' ? 'Waiting...' :
            'Now Serving';

        // Restore user list
        const storedUserList = localStorage.getItem('userList');
        if (storedUserList) {
            document.getElementById('user-list').innerHTML = storedUserList;
        }


        document.getElementById('fetch-oldest-client').addEventListener('click', function() {
            fetch('/get-oldest-client')
                .then(response => response.json())
                .then(data => {

                    // Check if data is available
                    if (!data || !data.name || !data.number) {
                        alert("No client data available.");
                        return;
                    }
                    // If data exists, update the HTML, otherwise set to '---'
                    const clientName = data.name || '---';
                    const clientNumber = data.number || '---';
                    const clientStatus = data.status || 'Waiting...';

                    // Update client info on the page
                    document.getElementById('client-name').innerText = clientName;
                    document.getElementById('client-number').innerText = clientNumber;
                    document.getElementById('client-status').innerText = clientStatus;

                    // Change 'Now Serving' to 'Waiting' if client number is '---'
                    const nowServingElement = document.getElementById('client-status');
                    if (clientNumber === '---') {
                        nowServingElement.innerText = 'Waiting...';
                    } else {
                        nowServingElement.innerText = 'Now Serving';
                    }

                    // Save client data to localStorage
                    localStorage.setItem('clientName', clientName);
                    localStorage.setItem('clientNumber', clientNumber);
                    localStorage.setItem('clientStatus', clientStatus);

                    let fetchInterval;

                    function fetchUsers() {
                        $.get('/client', function(client) {
                            console.log(client);

                            if (Array.isArray(client)) {
                                const limitedClientList = client.slice(0, 10);
                                let userListHtml = '';

                                for (let i = 0; i < 10; i++) {
                                    const listItemClass = i === 0 ?
                                        'fw-bold text-start queue-waiting p-3 bg-opacity-75 list-group-item text-start' :
                                        'text-start list-group-item pl-6 pt-3 pb-3 text-start';

                                    if (i < limitedClientList.length) {
                                        userListHtml +=
                                            `<li class="${listItemClass}">${i + 1}. ${limitedClientList[i].name} - ${limitedClientList[i].number}</li>`;
                                    } else {
                                        userListHtml +=
                                            `<li class="${listItemClass}">${i + 1}. </li>`;
                                    }
                                }

                                // Update the user list
                                document.getElementById('user-list').innerHTML =
                                    userListHtml;

                                // Save user list HTML to localStorage
                                localStorage.setItem('userList', userListHtml);

                                // Stop refreshing if we have 10 items
                                // if (limitedClientList.length >= 10) {
                                //     clearInterval(fetchInterval);
                                // }
                            } else {
                                console.error('Expected an array of users, but got:',
                                    client);
                            }
                        }).fail(function() {
                            console.error('Error fetching users');
                        });
                    }

                    // Start fetching users every 3 seconds
                    function startAutoRefresh() {
                        fetchInterval = setInterval(fetchUsers, 2000);
                    }

                    startAutoRefresh();

                })
                .catch(error => {
                    console.error('Error fetching client data:', error);
                    document.getElementById('client-name').innerText = '---';
                    document.getElementById('client-number').innerText = '---';
                    document.getElementById('now-serving').innerText = 'Waiting...';

                    // Clear localStorage on error
                    localStorage.removeItem('clientName');
                    localStorage.removeItem('clientNumber');
                    localStorage.removeItem('userList');
                });
        });
    });
</script>

{{-- this is for wait button function --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener for the "Wait" button
        document.getElementById('wait-button').addEventListener('click', function() {

            fetch('/waitingQueue')
                .then(response => response.json())
                .then(data => {

                    // Check if data is available
                    if (!data) {
                        alert("No client data available.");
                        return;
                    }
                    // If data exists, update the HTML, otherwise set to '---'
                    const clientName = data.name || '---';
                    const clientNumber = data.number || '---';
                    const clientStatus = data.status || 'Waiting...';

                    // Update client info on the page
                    document.getElementById('client-name').innerText = clientName;
                    document.getElementById('client-number').innerText = clientNumber;
                    document.getElementById('client-status').innerText = clientStatus;

                    // Change 'Now Serving' to 'Waiting' if client number is '---'
                    const nowServingElement = document.getElementById('client-status');
                    if (clientNumber === '---') {
                        nowServingElement.innerText = 'Waiting...';
                    } else {
                        nowServingElement.innerText = 'Now Serving';
                    }

                    // Save client data to localStorage
                    localStorage.setItem('clientName', clientName);
                    localStorage.setItem('clientNumber', clientNumber);
                    localStorage.setItem('clientStatus', clientStatus);

                    let fetchInterval;

                    function fetchUsers() {
                        $.get('/client', function(client) {
                            console.log(client);

                            if (Array.isArray(client)) {
                                const limitedClientList = client.slice(0, 10);
                                let userListHtml = '';

                                for (let i = 0; i < 10; i++) {
                                    const listItemClass = i === 0 ?
                                        'fw-bold text-start queue-waiting p-3 bg-opacity-75 list-group-item text-start' :
                                        'text-start list-group-item pl-6 pt-3 pb-3 text-start';

                                    if (i < limitedClientList.length) {
                                        userListHtml +=
                                            `<li class="${listItemClass}">${i + 1}. ${limitedClientList[i].name} - ${limitedClientList[i].number}</li>`;
                                    } else {
                                        userListHtml +=
                                            `<li class="${listItemClass}">${i + 1}. </li>`;
                                    }
                                }

                                // Update the user list
                                document.getElementById('user-list').innerHTML =
                                    userListHtml;

                                // Save user list HTML to localStorage
                                localStorage.setItem('userList', userListHtml);

                                // Stop refreshing if we have 10 items
                                // if (limitedClientList.length >= 10) {
                                //     clearInterval(fetchInterval);
                                // }
                            } else {
                                console.error('Expected an array of users, but got:',
                                    client);
                            }
                        }).fail(function() {
                            console.error('Error fetching users');
                        });
                    }

                    // Start fetching users every 3 seconds
                    function startAutoRefresh() {
                        fetchInterval = setInterval(fetchUsers, 2000);
                    }

                    startAutoRefresh();

                })
                .catch(error => {
                    console.error('Error fetching client data:', error);
                    document.getElementById('client-name').innerText = '---';
                    document.getElementById('client-number').innerText = '---';
                    document.getElementById('client-status').innerText = 'Waiting...';

                    // Clear localStorage on error
                    localStorage.removeItem('clientName');
                    localStorage.removeItem('clientNumber');
                    localStorage.removeItem('userList');
                });


        });
    });
</script>

{{-- for notify button --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifyButton = document.getElementById('notify-button');
        if (notifyButton) {
            notifyButton.addEventListener('click', function() {
                const clientNumber = document.getElementById('client-number').innerText;

                if (clientNumber === '---') {
                    alert("No client to notify.");
                    return;
                }

                const message = `The next client number is ${clientNumber}`;
                const speech = new SpeechSynthesisUtterance(message);
                speech.voice = speechSynthesis.getVoices()[0];
                speech.rate = 1;
                speech.pitch = 1;
                speechSynthesis.speak(speech);
            });
        }
    });
</script>
