/**
 * Currency Manager
 * Handles currency selection, rate fetching, and UI updates.
 * Dependencies: Requires a backend proxy at ../api/currency_proxy.php
 */

const CurrencyManager = {
    rates: { USD: 1, MYR: 4.5, CNY: 7.2 }, // Default fallback
    selectedCurrency: localStorage.getItem('selected_currency') || 'MYR', // Default to MYR as per user context
    symbols: {
        'USD': '$',
        'MYR': 'RM',
        'CNY': '¥'
    },
    lastUpdated: 0,

    init: async function () {
        console.log('Initializing Currency Manager...');

        // Attempt to load cached rates from storage to avoid flicker
        const cachedRates = localStorage.getItem('currency_rates');
        if (cachedRates) {
            const data = JSON.parse(cachedRates);
            // Valid for 1 hour
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
            // Assumes running from a subdirectory like /admin/ or /LaptopAdvisor/
            // Adjust path if strictly needed, but ../api/ works for 1-level deep apps
            const response = await fetch('../api/currency_proxy.php');
            const data = await response.json();

            if (data.status === 'success') {
                this.rates = data.rates;
                // Cache in localStorage
                localStorage.setItem('currency_rates', JSON.stringify({
                    rates: this.rates,
                    timestamp: Date.now()
                }));
            } else {
                console.warn('Currency API returned error, using fallbacks:', data.message);
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

        // Update all selector dropdowns to match
        document.querySelectorAll('.currency-selector').forEach(el => {
            el.value = currency;
        });
    },

    updatePagePrices: function () {
        const rate = this.rates[this.selectedCurrency];
        const symbol = this.symbols[this.selectedCurrency];

        // Update all elements with class 'currency-price'
        // They must have data-base-price attribute (USD value)
        document.querySelectorAll('.currency-price').forEach(el => {
            const basePrice = parseFloat(el.getAttribute('data-base-price'));
            if (!isNaN(basePrice)) {
                const converted = basePrice * rate;
                // Format: e.g. RM 4,500.00
                el.textContent = `${symbol} ${converted.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            }
        });

        console.log(`Updated prices to ${this.selectedCurrency} (Rate: ${rate})`);
    },

    bindSelectors: function () {
        // Find selectors (e.g. <select class="currency-selector">)
        const selectors = document.querySelectorAll('.currency-selector');
        selectors.forEach(sel => {
            sel.value = this.selectedCurrency;
            sel.addEventListener('change', (e) => {
                this.setCurrency(e.target.value);
            });
        });
    }
};

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    CurrencyManager.init();
});
