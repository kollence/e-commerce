export default function formatCurrency(amount, currency = 'USD', locale = 'en-US') {
    // Check if the amount is a number
    if (isNaN(amount)) {
      throw new Error('Amount must be a number');
    }
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: currency,
    }).format(amount);
  }