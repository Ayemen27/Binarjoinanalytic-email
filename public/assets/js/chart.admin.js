const rootStyles = getComputedStyle(document.documentElement);

            // Fetch CSS variables
            const primaryColor = rootStyles.getPropertyValue('--primary_color').trim();
            const primaryOpacity = rootStyles.getPropertyValue('--primary_opacity').trim();
            const secondaryColor = rootStyles.getPropertyValue('--secondary_color').trim();
            const textColor = rootStyles.getPropertyValue('--text_color').trim();
            const primaryGradient = rootStyles.getPropertyValue('--primary_gradient').trim();


            //alert(BASE_URL + '/get-data');
            $.ajax({
                url: BASE_URL + '/get-data', // Laravel route to get data from
                method: 'GET',
                success: function(response) {
                    // Base chart options to be reused
                    const baseDatasetOptions = {
                        fill: true,
                        backgroundColor: [primaryOpacity],
                        pointBackgroundColor: [primaryColor],
                        borderColor: [primaryColor],
                        lineTension: 0,
                        borderWidth: 2,
                    };

                    // Function to generate chart configurations
                    function createChartConfig(type, labels, data, label , stepSize) {
                        return {
                            type: type,
                            data: {
                                labels: labels, // Use the labels from the server
                                datasets: [{
                                    ...baseDatasetOptions, // Reuse base options
                                    data: data, // Data from the server
                                    label: label,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    },
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            stepSize: stepSize, // Customize step size
                                        }
                                    }
                                }

                            }
                        };
                    }

                    // Create chart configurations
                    const user_config = createChartConfig('line', response.user_labels, response.user_data,
                        "New Users" , 5);
                    const email_config = createChartConfig('bar', response.email_labels, response.email_data,
                        "New Emails" , 10);
                    const message_config = createChartConfig('bar', response.message_labels, response.message_data,
                        "New Messages" , 10);

                    // Render the charts
                    const UserChart = new Chart(document.getElementById('users-chart'), user_config);
                    const EmailChart = new Chart(document.getElementById('email-chart'), email_config);
                    const MessageChart = new Chart(document.getElementById('message-chart'), message_config);
                }
            });
