document.addEventListener('DOMContentLoaded', function () {
    const loanAmountInput = document.getElementById('loan-amount-input');
    const loanAmountRange = document.getElementById('loan-amount-range');
    const interestRateInput = document.getElementById('rate');
    const interestRateRange = document.getElementById('rate-range');
    const loanTermInput = document.getElementById('year');
    const loanTermRange = document.getElementById('term-range');
    
    const monthlyPaymentElement = document.getElementById('monthly-payment');
    const totalInterestElement = document.getElementById('total-interest');
    const totalPaymentElement = document.getElementById('total-payment');
    
    const canvas = document.getElementById('loanChart');
    const ctx = canvas.getContext('2d');
    
    let loanChart;

    function initChart(principal, interest) {
        // Destroy the existing chart if it exists
        if (loanChart) {
            loanChart.destroy();
        }

        loanChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Principal', 'Interest'],
                datasets: [{
                    data: [principal, interest],
                    backgroundColor: ['crimson', '#ccc'],
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        labels: {
                            color: '#ccc',
                            font: {
                                size: 14,
                            },
                            padding: 10,
                        }
                    }
                }
            }
        });
    }

    function calculateLoan() {
        const principal = parseFloat(loanAmountInput.value);
        const interestRate = parseFloat(interestRateInput.value) / 100 / 12;
        const payments = parseFloat(loanTermInput.value) * 12;
        
        const x = Math.pow(1 + interestRate, payments);
        const monthly = (principal * interestRate * x) / (x - 1);
        
        if (isFinite(monthly)) {
            const totalPayment = (monthly * payments).toFixed(2);
            const totalInterest = (totalPayment - principal).toFixed(2);
            
            monthlyPaymentElement.textContent = ` ₹ ${monthly.toFixed(2)}`;
            totalInterestElement.textContent = ` ₹ ${totalInterest}`;
            totalPaymentElement.textContent = `₹ ${totalPayment}`;
            
            // Update the chart after calculating
            if (loanChart) {
                updateChart(principal, totalInterest);
            } else {
                initChart(principal, totalInterest); // Initialize if not already done
            }
        } else {
            monthlyPaymentElement.textContent = ` `;
            totalInterestElement.textContent = ` `;
            totalPaymentElement.textContent = ` `;
        }
    }

    function updateChart(principal, interest) {
        if (loanChart) {
            loanChart.data.datasets[0].data = [principal, interest];
            loanChart.update();  // Update the existing chart
        }
    }
    
    // Add event listeners for range and input changes
    loanAmountRange.addEventListener('input', function () {
        loanAmountInput.value = this.value;
        calculateLoan();
    });
    
    loanAmountInput.addEventListener('input', function () {
        loanAmountRange.value = this.value;
        calculateLoan();
    });
    
    interestRateRange.addEventListener('input', function () {
        interestRateInput.value = this.value;
        calculateLoan();
    });
    
    interestRateInput.addEventListener('input', function () {
        interestRateRange.value = this.value;
        calculateLoan();
    });
    
    loanTermRange.addEventListener('input', function () {
        loanTermInput.value = this.value;
        calculateLoan();
    });
    
    loanTermInput.addEventListener('input', function () {
        loanTermRange.value = this.value;
        calculateLoan();
    });

    // Initial calculation and chart initialization
    calculateLoan();
    const initialPrincipal = parseFloat(loanAmountInput.value);
    const initialInterest = (initialPrincipal * parseFloat(interestRateInput.value) / 100).toFixed(2);
    initChart(initialPrincipal, initialInterest);  // Initialize the chart once
});
