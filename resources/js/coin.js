// resources/js/coin.js

document.addEventListener('DOMContentLoaded', function () {
    const refreshBtn = document.getElementById('refresh-coin-balance');
    const balanceSpan = document.getElementById('coin-balance');
    if (refreshBtn && balanceSpan) {
        refreshBtn.addEventListener('click', async function () {
            refreshBtn.disabled = true;
            refreshBtn.textContent = '...';
            try {
                const response = await fetch('/api/coins/balance', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    credentials: 'same-origin',
                });
                if (response.ok) {
                    const data = await response.json();
                    balanceSpan.textContent = data.balance;
                } else {
                    alert('Failed to fetch coin balance.');
                }
            } catch (e) {
                alert('Error fetching coin balance.');
            }
            refreshBtn.disabled = false;
            refreshBtn.textContent = 'Refresh';
        });
    }
});
