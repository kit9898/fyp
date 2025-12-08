/**
 * Currency Manager
 * Handles currency selection, rate fetching, and UI updates.
 * Dependencies: Requires a backend proxy at ../api/currency_proxy.php
 */

const CurrencyManager = {
    rates: { USD: 1, MYR: 4.5, CNY: 7.2 }, // Default fallback
    selectedCurrency: localStorage.getItem('selected_currency') || 'MYR', // Default to MYR
    symbols: {
        'USD': '$',
        'MYR': 'RM',
        'CNY': '¥'
    },
    lastUpdated: 0,

    init: async function () {
        console.log('Initializing Currency Manager...');

        // Attempt to load cached rates
        const cachedRates = localStorage.getItem('currency_rates');
        if (cachedRates) {
            const data = JSON.parse(cachedRates);
            if (Date.now() - data.timestamp < 3600000) {
                this.rates = data.rates;
                console.log('Loaded cached rates:', this.rates);
            } else {
                await this.fetchRates();
            }
        } else {
            await this.fetchRates();
        }

        this.bindSelectors();
        this.updatePagePrices();
    },

    fetchRates: async function () {
        try {
            const response = await fetch('../api/currency_proxy.php');
            const data = await response.json();

            if (data.status === 'success') {
                this.rates = data.rates;
                localStorage.setItem('currency_rates', JSON.stringify({
                    rates: this.rates,
                    timestamp: Date.now()
                }));
            } else {
                console.warn('Currency API error:', data.message);
            }
        } catch (error) {
            console.error('Failed to fetch currency rates:', error);
        }
    },

    setCurrency: function (currency) {
        if (!this.rates[currency]) return;
        this.selectedCurrency = currency;
        localStorage.setItem('selected_currency', currency);
        this.updatePagePrices();

        document.querySelectorAll('.currency-selector').forEach(el => {
            el.value = currency;
        });
    },

    updatePagePrices: function () {
        const rate = this.rates[this.selectedCurrency];
        const symbol = this.symbols[this.selectedCurrency];

        document.querySelectorAll('.currency-price').forEach(el => {
            const basePrice = parseFloat(el.getAttribute('data-base-price'));
            if (!isNaN(basePrice)) {
                const converted = basePrice * rate;
                el.textContent = `${symbol} ${converted.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            }
        });
    },

    bindSelectors: function () {
        const selectors = document.querySelectorAll('.currency-selector');
        selectors.forEach(sel => {
            sel.value = this.selectedCurrency;
            sel.addEventListener('change', (e) => {
                this.setCurrency(e.target.value);
            });
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    CurrencyManager.init();
});
