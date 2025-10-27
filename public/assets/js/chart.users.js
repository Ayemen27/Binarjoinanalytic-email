const rootStyles = getComputedStyle(document.documentElement);


        // Fetch CSS variables
        const primaryColor = rootStyles.getPropertyValue('--primary_color').trim();
        const primaryOpacity = rootStyles.getPropertyValue('--primary_opacity').trim();
        const secondaryColor = rootStyles.getPropertyValue('--secondary_color').trim();
        const textColor = rootStyles.getPropertyValue('--text_color').trim();
        const primaryGradient = rootStyles.getPropertyValue('--primary_gradient').trim();
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

                const email_config = createChartConfig('line', response.email_labels, response.email_data,
                    "New Emails" , 10);

                // Render the charts
                const EmailChart = new Chart(document.getElementById('email-chart'), email_config);
            }
        });
